@extends('layouts.app')
@section('title', 'Edit Branch')
@section('page-title', 'Edit Branch')
@section('page-subtitle', $branch->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-6">
        <a href="{{ route('branches.index') }}" class="btn-secondary !py-2 !px-4 flex items-center gap-2 shadow-sm text-xs font-bold rounded-xl">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Branches
        </a>
    </div>

    <form action="{{ route('branches.update', $branch) }}" method="POST" class="glass-card">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="form-label">Branch Name *</label>
                <input type="text" name="name" value="{{ old("name", $branch->name) }}" placeholder="Enter name" class="form-input" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="form-label">Address</label>
                <textarea name="address" rows="3" class="form-input" placeholder="Enter address">{{ old('address', $branch->address) }}</textarea>
                @error('address') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old("phone", $branch->phone) }}" placeholder="Enter phone" class="form-input">
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old("email", $branch->email) }}" placeholder="Enter email" class="form-input">
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="btn-primary !px-8 !py-2.5">
                Update Branch
            </button>
        </div>
    </form>
</div>
@endsection
