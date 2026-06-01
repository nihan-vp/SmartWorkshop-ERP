@extends('layouts.app')
@section('title', 'Add Service')
@section('page-title', 'Add New Service')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('services.store') }}" method="POST" id="create-service-form">
            @csrf
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Service Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Price *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Save Service</button>
                <a href="{{ route('services.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
