<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BackupController extends Controller
{
    /**
     * Display a listing of backups.
     */
    public function index()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        // Sort backups by latest first
        usort($backups, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        // Get DB details for display
        $dbName = DB::getDatabaseName();
        $dbConnection = config('database.default');
        
        // Count total tables
        $tables = DB::select('SHOW TABLES');
        $tablesCount = count($tables);

        return view('backup', compact('backups', 'dbName', 'dbConnection', 'tablesCount'));
    }

    /**
     * Create a new SQL backup file.
     */
    public function create()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.sql';
        $path = $backupDir . DIRECTORY_SEPARATOR . $filename;

        try {
            $database = DB::getDatabaseName();
            $tables = DB::select('SHOW TABLES');

            // Open stream to file to prevent loading all content into memory
            $handle = fopen($path, 'w');
            if (!$handle) {
                throw new \Exception("Could not open file for writing: {$path}");
            }

            fwrite($handle, "-- Suhaim Soft Workshop Database Backup\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Database: {$database}\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $table) {
                $tableName = current((array)$table);

                // Write Drop and Create Table
                fwrite($handle, "-- ------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for table `{$tableName}`\n");
                fwrite($handle, "-- ------------------------------------------------------\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $createTableSql = $createTable->{'Create Table'} ?? $createTable->{'create table'} ?? null;
                if ($createTableSql) {
                    fwrite($handle, $createTableSql . ";\n\n");
                }

                // Write Inserts in chunks
                fwrite($handle, "-- Dumping data for table `{$tableName}`\n");
                
                // Fetch and write rows in chunks
                $chunkSize = 250;
                $offset = 0;
                
                while (true) {
                    $rows = DB::table($tableName)->offset($offset)->limit($chunkSize)->get();
                    if ($rows->isEmpty()) {
                        break;
                    }

                    foreach ($rows as $row) {
                        $arrayRow = (array)$row;
                        $keys = array_map(function ($key) {
                            return "`{$key}`";
                        }, array_keys($arrayRow));

                        $values = array_map(function ($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return DB::getPdo()->quote($value);
                        }, array_values($arrayRow));

                        $insertSql = "INSERT INTO `{$tableName}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                        fwrite($handle, $insertSql);
                    }
                    
                    $offset += $chunkSize;
                }
                
                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // Log activity
            \App\Models\ActivityLog::log('database_backup', "Created database backup: {$filename}");

            return redirect()
                ->route('backup.index')
                ->with('success', "Database backup created successfully: {$filename}");

        } catch (\Exception $e) {
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()
                ->route('backup.index')
                ->with('error', "Backup generation failed: " . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download($filename)
    {
        // Safe filename check
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.sql$/', $filename)) {
            return redirect()->route('backup.index')->with('error', 'Invalid backup filename.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            return redirect()->route('backup.index')->with('error', 'Backup file not found.');
        }

        return response()->download($path);
    }

    /**
     * Restore database from a local backup file.
     */
    public function restore($filename)
    {
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.sql$/', $filename)) {
            return redirect()->route('backup.index')->with('error', 'Invalid backup filename.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            return redirect()->route('backup.index')->with('error', 'Backup file not found.');
        }

        try {
            $sqlContent = File::get($path);
            
            // Execute the SQL unprepared without transaction (implicit commits happen on DDL)
            DB::unprepared($sqlContent);

            // Log activity
            \App\Models\ActivityLog::log('database_restore', "Restored database from local file: {$filename}");

            return redirect()
                ->route('backup.index')
                ->with('success', "Database restored successfully from backup: {$filename}");

        } catch (\Exception $e) {
            return redirect()
                ->route('backup.index')
                ->with('error', "Restore failed: " . $e->getMessage());
        }
    }

    /**
     * Upload an SQL file and restore the database immediately.
     */
    public function uploadRestore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:20480', // limit to 20MB
        ]);

        $file = $request->file('backup_file');
        
        // Basic extension check
        if ($file->getClientOriginalExtension() !== 'sql') {
            return redirect()->route('backup.index')->with('error', 'Only SQL files (.sql) are allowed.');
        }

        try {
            $sqlContent = File::get($file->getRealPath());

            // Execute the SQL unprepared without transaction (implicit commits happen on DDL)
            DB::unprepared($sqlContent);

            // Log activity
            \App\Models\ActivityLog::log('database_restore', "Restored database from uploaded backup file");

            return redirect()
                ->route('backup.index')
                ->with('success', 'Database restored successfully from uploaded SQL file!');

        } catch (\Exception $e) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup file.
     */
    public function destroy($filename)
    {
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.sql$/', $filename)) {
            return redirect()->route('backup.index')->with('error', 'Invalid backup filename.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            
            // Log activity
            \App\Models\ActivityLog::log('database_backup_delete', "Deleted backup file: {$filename}");

            return redirect()
                ->route('backup.index')
                ->with('success', "Backup file deleted successfully.");
        }

        return redirect()
            ->route('backup.index')
            ->with('error', 'Backup file not found.');
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
