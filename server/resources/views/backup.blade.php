@extends('layouts.app')

@section('title', 'Database Backup & Restore')
@section('page-title', 'Backup & Restore')
@section('page-subtitle', 'Advanced database preservation and recovery control panel')

@section('content')
<div x-data="backupControlPanel()" class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- Header / Quick Actions Banner --}}
    <div class="relative bg-white border border-slate-200/80 rounded-3xl p-6 lg:p-8 shadow-sm overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-blue-100/30 filter blur-[40px] pointer-events-none"></div>
        <div class="z-10 space-y-1.5">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2 font-outfit">
                Database Backup & Restore System
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
            </h3>
            <p class="text-sm text-slate-500 font-medium">Export a snapshot of the current state of the database, or restore the system to a previous state.</p>
        </div>
        <div class="z-10 flex flex-wrap gap-3 shrink-0 w-full md:w-auto">
            <form action="{{ route('backup.create') }}" method="POST" class="w-full md:w-auto">
                @csrf
                <button type="submit" class="btn-primary w-full md:w-auto shadow-sm !py-2.5 !px-5 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create New Backup
                </button>
            </form>
        </div>
    </div>

    {{-- System Status & Upload Restore Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Database Details Card --}}
        <div class="glass-card flex flex-col justify-between border-l-4 border-l-blue-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300 lg:col-span-1">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Database Server</span>
                    <span class="badge badge-info">Status</span>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase">Driver / Engine</p>
                        <p class="text-sm font-bold text-slate-900 font-mono capitalize">{{ $dbConnection }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase">Database Name</p>
                        <p class="text-sm font-bold text-slate-900 font-mono">{{ $dbName }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase">Total Database Tables</p>
                        <p class="text-sm font-bold text-slate-900 font-mono">{{ $tablesCount }} tables</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Total Backups: {{ count($backups) }}</span>
                <span>Active Connection</span>
            </div>
        </div>

        {{-- Direct File Upload Restore Card --}}
        <div class="glass-card border-l-4 border-l-indigo-500 relative overflow-hidden group hover:scale-[1.01] transition-transform duration-300 lg:col-span-2">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Restore Database File</span>
                    <span class="badge badge-purple">Upload SQL</span>
                </div>
                
                <form action="{{ route('backup.upload_restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return confirm('WARNING: Uploading and restoring a backup will overwrite the entire database! Are you sure you want to proceed?')">
                    @csrf
                    <div>
                        <label for="backup_file" class="form-label">Select SQL Backup File (.sql)</label>
                        <input type="file" name="backup_file" id="backup_file" accept=".sql" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 font-medium flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Uploading an SQL file executes all commands immediately. Restores overwrite current users, workshops, work orders, invoices, and settings. Proceed with caution.</span>
                    </div>
                    <div>
                        <button type="submit" class="w-full btn-primary bg-indigo-600 hover:bg-indigo-700 border-indigo-600 shadow-sm py-3 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload & Restore SQL
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Backups List Table --}}
    <div class="glass-card !p-0 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="text-base font-bold text-slate-900 font-outfit">Stored Database Backups</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Download, restore, or delete previous backups stored in storage. Files are listed newest first.</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-2 rounded-xl whitespace-nowrap shrink-0">{{ count($backups) }} stored backup files</span>
            </div>
        </div>

        @if(empty($backups))
        <div class="text-center py-16 px-6">
            <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17h8M8 17v4H6v-4m2 0H4.5A1.5 1.5 0 013 15.5v-3.194a1.5 1.5 0 01.138-.632L5 8h14l1.862 3.674c.09.2.138.416.138.632V15.5a1.5 1.5 0 01-1.5 1.5H16m0 0v4h-2v-4m0 0H8M7 11h.01M17 11h.01"/></svg>
            <p class="text-slate-600 font-semibold">No backup files found</p>
            <p class="text-sm text-slate-400 mt-1 mb-6">Create your first database snapshot to ensure system safety.</p>
            <form action="{{ route('backup.create') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary text-sm">Create First Backup</button>
            </form>
        </div>
        @else
        <div class="overflow-x-auto relative z-0 w-full">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Backup Filename</th>
                        <th>File Size</th>
                        <th>Created Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr>
                        <td class="font-mono font-bold text-slate-900 py-4 max-w-sm truncate" title="{{ $backup['filename'] }}">
                            {{ $backup['filename'] }}
                        </td>
                        <td class="font-semibold text-slate-700 py-4">{{ $backup['size'] }}</td>
                        <td class="font-medium text-slate-500 py-4">{{ $backup['created_at'] }}</td>
                        <td class="py-4 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2 pr-4">
                                {{-- Download --}}
                                <a href="{{ route('backup.download', $backup['filename']) }}" class="btn-secondary !py-1.5 !px-3 text-xs bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl" title="Download">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>

                                {{-- Restore --}}
                                <button type="button" @click="confirmRestore('{{ $backup['filename'] }}')" class="btn-secondary !py-1.5 !px-3 text-xs bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 hover:text-amber-800 rounded-xl" title="Restore">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Restore
                                </button>

                                {{-- Delete --}}
                                <button type="button" @click="confirmDelete('{{ $backup['filename'] }}')" class="btn-secondary !py-1.5 !px-3 text-xs bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 hover:text-red-700 rounded-xl" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Restore Confirmation Modal --}}
    <div x-show="showRestoreModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/40 backdrop-blur-sm" @click="showRestoreModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-blue-100 p-6 space-y-6">
                <div class="flex items-start gap-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-amber-500/10 text-amber-600 sm:mx-0">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="flex-1 space-y-2">
                        <h3 class="text-lg font-bold text-slate-900 leading-tight">Confirm Database Restore</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            You are about to restore the database to the state recorded in:<br>
                            <span class="font-mono font-bold text-blue-600 block mt-1 break-words" x-text="targetBackup"></span>
                        </p>
                        <p class="text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 rounded-xl p-3 leading-normal">
                            CAUTION: This will overwrite all modifications, user additions, invoices, workshops, and configurations created after this backup. This action CANNOT be undone.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="confirm_text" class="form-label text-xs uppercase tracking-wider text-slate-400">Type <span class="font-bold text-slate-700">RESTORE</span> below to confirm</label>
                    <input type="text" id="confirm_text" x-model="confirmText" placeholder="RESTORE" class="form-input text-center font-bold tracking-widest">
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="showRestoreModal = false" class="btn-secondary !py-2.5 !px-5">Cancel</button>
                    <form :action="'{{ url('/backup') }}/' + targetBackup + '/restore'" method="POST" class="m-0">
                        @csrf
                        <button type="submit" :disabled="confirmText.toUpperCase() !== 'RESTORE'" class="btn-primary !bg-amber-600 hover:!bg-amber-700 !border-amber-600 shadow-sm !py-2.5 !px-5 disabled:opacity-40 disabled:cursor-not-allowed">
                            Restore Database
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/40 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-blue-100 p-6 space-y-6">
                <div class="flex items-start gap-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-rose-500/10 text-rose-600 sm:mx-0">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div class="flex-1 space-y-1">
                        <h3 class="text-lg font-bold text-slate-900 leading-tight">Delete Backup File</h3>
                        <p class="text-sm text-slate-500 leading-normal">
                            Are you sure you want to permanently delete this backup file? This file will be removed from server storage forever.<br>
                            <span class="font-mono font-bold text-rose-600 block mt-2 break-words" x-text="targetBackup"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="showDeleteModal = false" class="btn-secondary !py-2.5 !px-5">Cancel</button>
                    <form :action="'{{ url('/backup') }}/' + targetBackup" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary !bg-red-600 hover:!bg-red-700 !border-red-600 shadow-sm !py-2.5 !px-5">
                            Delete Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function backupControlPanel() {
        return {
            showRestoreModal: false,
            showDeleteModal: false,
            targetBackup: '',
            confirmText: '',
            confirmRestore(filename) {
                this.targetBackup = filename;
                this.confirmText = '';
                this.showRestoreModal = true;
            },
            confirmDelete(filename) {
                this.targetBackup = filename;
                this.showDeleteModal = true;
            }
        }
    }
</script>
@endsection
