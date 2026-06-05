@extends('layouts.app')

@section('title', 'Add Supplier')
@section('header', 'Add Supplier')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('suppliers.index') }}" class="text-slate-500 hover:text-primary-500 flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Suppliers
        </a>
    </div>

    <div class="card">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2 md:col-span-1">
                    <label class="form-label">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Enter name">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-input" placeholder="Enter contact person">
                    @error('contact_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="Enter phone">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter email">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3" class="form-input" placeholder="Enter address">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('suppliers.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Save Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
