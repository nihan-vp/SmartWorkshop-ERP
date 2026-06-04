<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OfflineViewerController extends Controller
{
    /**
     * List backups and logs, and view selected file.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $backupDir = storage_path('app/backups');
        $logDir = storage_path('logs');

        // Ensure backup dir exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        $backups = [];
        if (File::exists($backupDir)) {
            $backupFiles = File::files($backupDir);
            foreach ($backupFiles as $file) {
                if ($file->getExtension() === 'sql') {
                    $backups[] = [
                        'filename' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        'timestamp' => $file->getMTime(),
                    ];
                }
            }
            usort($backups, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
        }

        $logs = [];
        if (File::exists($logDir)) {
            $logFiles = File::files($logDir);
            foreach ($logFiles as $file) {
                if ($file->getExtension() === 'log') {
                    $logs[] = [
                        'filename' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        'timestamp' => $file->getMTime(),
                    ];
                }
            }
            usort($logs, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
        }

        $activeTab = $request->get('tab', 'backups');
        $viewFile = $request->get('view');
        $viewType = $request->get('type');
        $fileContent = null;
        $fileLinesCount = 0;
        $isTruncated = false;

        if ($viewFile && $viewType) {
            // Security check to prevent directory traversal
            $pattern = $viewType === 'backup' ? '/^[a-zA-Z0-9_\-\.]+\.sql$/' : '/^[a-zA-Z0-9_\-\.]+\.log$/';
            if (preg_match($pattern, $viewFile)) {
                $dir = $viewType === 'backup' ? $backupDir : $logDir;
                $path = $dir . DIRECTORY_SEPARATOR . $viewFile;

                if (File::exists($path)) {
                    $size = File::size($path);
                    $maxSize = 1024 * 1024 * 1.5; // 1.5MB limit for direct full reading

                    if ($size > $maxSize) {
                        $isTruncated = true;
                        // Read last 500KB of the file
                        $fileContent = $this->readLastBytes($path, 1024 * 500);
                    } else {
                        $fileContent = File::get($path);
                    }
                    $fileLinesCount = substr_count($fileContent, "\n") + 1;
                } else {
                    return redirect()->route('offline-viewer.index')->with('error', 'Selected file does not exist.');
                }
            } else {
                return redirect()->route('offline-viewer.index')->with('error', 'Invalid file name or type.');
            }
        }

        return view('offline_viewer.index', compact('backups', 'logs', 'activeTab', 'viewFile', 'viewType', 'fileContent', 'fileLinesCount', 'isTruncated'));
    }

    /**
     * Download a log or backup file.
     */
    public function download(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $filename = $request->get('file');
        $type = $request->get('type');

        if (!$filename || !$type) {
            return back()->with('error', 'Missing file parameters.');
        }

        $pattern = $type === 'backup' ? '/^[a-zA-Z0-9_\-\.]+\.sql$/' : '/^[a-zA-Z0-9_\-\.]+\.log$/';
        if (!preg_match($pattern, $filename)) {
            return back()->with('error', 'Invalid filename.');
        }

        $dir = $type === 'backup' ? storage_path('app/backups') : storage_path('logs');
        $path = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($path)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($path);
    }

    /**
     * Delete a log or backup file.
     */
    public function destroy(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $filename = $request->get('file');
        $type = $request->get('type');

        if (!$filename || !$type) {
            return back()->with('error', 'Missing file parameters.');
        }

        $pattern = $type === 'backup' ? '/^[a-zA-Z0-9_\-\.]+\.sql$/' : '/^[a-zA-Z0-9_\-\.]+\.log$/';
        if (!preg_match($pattern, $filename)) {
            return back()->with('error', 'Invalid filename.');
        }

        $dir = $type === 'backup' ? storage_path('app/backups') : storage_path('logs');
        $path = $dir . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($path)) {
            // Secure validation: check if they are trying to delete the active laravel.log
            if ($type === 'log' && $filename === 'laravel.log') {
                // Instead of deleting, we can clear the file
                File::put($path, '');
                \App\Models\ActivityLog::log('file_clear', "Cleared active log file: {$filename}");
                return redirect()->route('offline-viewer.index', ['tab' => 'logs'])->with('success', 'Active log file has been cleared.');
            }

            File::delete($path);
            \App\Models\ActivityLog::log('file_delete', "Deleted {$type} file: {$filename}");
            return redirect()->route('offline-viewer.index', ['tab' => $type . 's'])->with('success', 'File deleted successfully.');
        }

        return back()->with('error', 'File not found.');
    }

    /**
     * Read the last N bytes of a file safely.
     */
    private function readLastBytes($path, $bytes)
    {
        $size = File::size($path);
        if ($size <= $bytes) {
            return File::get($path);
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return "";
        }

        fseek($handle, -$bytes, SEEK_END);
        
        // Skip first line fragment to start reading at a clean newline boundary
        fgets($handle);

        $data = fread($handle, $bytes);
        fclose($handle);

        return $data;
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
