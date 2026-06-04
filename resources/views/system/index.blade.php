@extends('layouts.app')

@section('title', 'System Settings & Info')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Manage workshop profile settings and view technical configuration details')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-sm font-bold">Please correct the following errors:</span>
        </div>
        <ul class="list-disc pl-9 mt-2 text-xs font-semibold space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Grid Columns (2/3 width): Workshop details form --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card shadow-sm border border-slate-200/80">
                <div class="border-b border-slate-100 pb-5 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 font-outfit">Workshop Profile Configuration</h3>
                    <p class="text-xs text-slate-500 mt-1">Configure your official business name, contact information, GSTIN, and invoice header logo.</p>
                </div>

                <form action="{{ route('system.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label font-bold text-slate-700">Workshop / Business Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $workshop->name) }}" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="phone" class="form-label font-bold text-slate-700">Contact Number *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $workshop->phone) }}" required 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="form-label font-bold text-slate-700">Business Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $workshop->email) }}" 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="gstin" class="form-label font-bold text-slate-700">GSTIN / Tax Registration Number</label>
                            <input type="text" name="gstin" id="gstin" value="{{ old('gstin', $workshop->gstin) }}" placeholder="e.g. 22AAAAA0000A1Z5" 
                                   class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 uppercase">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="form-label font-bold text-slate-700">Workshop Physical Address</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="form-input mt-1 w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('address', $workshop->address) }}</textarea>
                    </div>

                    {{-- Logo Upload with Preview --}}
                    <div class="border-t border-slate-100 pt-6">
                        <label class="form-label font-bold text-slate-700 block mb-2">Invoice / Estimate Header Logo</label>
                        <div class="flex flex-col sm:flex-row items-center gap-6 bg-slate-50 p-4 rounded-2xl border border-slate-150">
                            
                            {{-- Logo Preview Container --}}
                            <div class="w-32 h-32 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 relative shadow-inner">
                                @if($workshop->logo && file_exists(public_path('storage/' . $workshop->logo)))
                                    <img id="logo-preview-image" src="{{ asset('storage/' . $workshop->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                                @else
                                    <div id="logo-placeholder" class="text-center p-3 text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a1 1 0 011.414 0L16 17m0 0l1.414-1.414a1 1 0 011.414 0L20 17.586V19a2 2 0 01-2 2H6a2 2 0 01-2-2v-3.586l.086-.086z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-[9px] font-semibold uppercase tracking-wider">No Logo Set</span>
                                    </div>
                                    <img id="logo-preview-image" src="#" alt="Logo Preview" class="max-w-full max-h-full object-contain p-2 hidden">
                                @endif
                            </div>

                            <div class="space-y-2 text-center sm:text-left min-w-0">
                                <input type="file" name="logo" id="logo-file-input" accept="image/*" class="hidden" onchange="previewLogo(this)">
                                <button type="button" onclick="document.getElementById('logo-file-input').click()" 
                                        class="btn-primary bg-white hover:bg-slate-50 border-slate-200 text-slate-700 shadow-sm !py-2 !px-4 text-xs">
                                    Choose Logo Image
                                </button>
                                <p class="text-[10px] text-slate-400 font-semibold mt-1">Supports JPEG, PNG, JPG, or GIF. Max size: 2MB.</p>
                                <p class="text-[9px] text-blue-500 font-medium leading-normal bg-blue-50 border border-blue-100 p-2 rounded-xl mt-1">This logo will be displayed on the top of generated PDFs and invoices.</p>
                            </div>

                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6 flex justify-end">
                        <button type="submit" class="btn-primary !py-2.5 !px-6 text-sm shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Profile Settings
                        </button>
                    </div>
                </form>

            </div>

            {{-- Reset Database / Remove Demo Data Card --}}
            <div class="glass-card shadow-sm border border-slate-200/80 border-t-4 border-t-rose-500 mt-8">
                <div class="border-b border-slate-100 pb-5 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 font-outfit flex items-center gap-2">
                        Danger Zone: Reset Database Data
                        <span class="badge badge-rose text-[9px] uppercase">Reset Data</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Permanently remove seeded dummy data or empty all business tables for this workshop to start with a clean slate.</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-rose-50 border border-rose-100 text-rose-900 rounded-2xl p-4 text-xs font-semibold leading-relaxed flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <span class="block font-bold mb-1">WARNING: THIS IS IRREVERSIBLE!</span>
                            Wiping workshop records will delete all your customers, vehicles, products, services, employees, work orders, estimates, invoices, expenses, and salaries. Your user account, workshop profile info, and settings will remain safe.
                        </div>
                    </div>

                    <form action="{{ route('system.clear_data') }}" method="POST" onsubmit="return confirm('CRITICAL WARNING: Are you absolutely sure you want to permanently clear all business data for this workshop? This will delete all customers, vehicles, invoices, work orders, and other records. This action CANNOT be undone!')">
                        @csrf
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary bg-rose-600 hover:bg-rose-700 border-rose-600 shadow-sm !py-2.5 !px-6 text-sm flex items-center justify-center gap-2">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Clear Workshop Demo Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Grid Column (1/3 width): System info metrics --}}
        <div class="space-y-8 lg:col-span-1">
            
            {{-- Technical Details Card --}}
            <div class="glass-card shadow-sm border border-slate-200/80">
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest font-outfit">System Specifications</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Underlying software & environment metrics</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Laravel Framework</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">v{{ $systemInfo['laravel_version'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">PHP Version</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">v{{ $systemInfo['php_version'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Server Operating System</span>
                        <span class="text-xs font-bold text-slate-900 font-mono capitalize">{{ $systemInfo['server_os'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Database Connection</span>
                        <span class="text-xs font-bold text-slate-900 font-mono capitalize">{{ $systemInfo['database_driver'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Database Schema Name</span>
                        <span class="text-xs font-bold text-slate-900 font-mono truncate max-w-[150px]" title="{{ $systemInfo['database_name'] }}">{{ $systemInfo['database_name'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Environment Mode</span>
                        <span class="badge {{ $systemInfo['app_env'] === 'production' ? 'badge-info' : 'badge-purple' }} uppercase text-[9px]">{{ $systemInfo['app_env'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Debug Mode Status</span>
                        <span class="text-xs font-bold {{ $systemInfo['app_debug'] === 'Enabled' ? 'text-rose-500' : 'text-slate-600' }}">{{ $systemInfo['app_debug'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">System Timezone</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">{{ $systemInfo['timezone'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100/50">
                        <span class="text-xs text-slate-500 font-medium">Database Backups Size</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">{{ $systemInfo['backup_size'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-xs text-slate-500 font-medium">Application Logs Size</span>
                        <span class="text-xs font-bold text-slate-900 font-mono">{{ $systemInfo['log_size'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Subscription / Trial Details Card --}}
            <div class="glass-card shadow-sm border border-slate-200/80 border-l-4 border-l-blue-600">
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest font-outfit">Subscription Status</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Workshop membership & licensing detail</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Membership Type</span>
                        <span class="badge badge-info uppercase mt-1 inline-block">{{ $workshop->subscription_status }}</span>
                    </div>
                    
                    @if($workshop->isTrial())
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Trial Period Time Remaining</span>
                            <span class="text-base font-bold text-slate-900 font-outfit mt-1 block">
                                {{ $workshop->getTrialDaysRemaining() }} Days Left
                            </span>
                            <span class="text-[10px] text-slate-400 mt-0.5 block">Trial ends on: {{ $workshop->trial_ends_at ? $workshop->trial_ends_at->format('d M Y') : 'N/A' }}</span>
                        </div>
                    @endif

                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Created / Registered Date</span>
                        <span class="text-xs font-bold text-slate-900 mt-1 block">{{ $workshop->created_at->format('d M Y h:i A') }}</span>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Operating for {{ $workshop->getSubscriptionDay() }} days</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('logo-preview-image');
                const placeholder = document.getElementById('logo-placeholder');
                
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
