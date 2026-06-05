@extends('layouts.app')
@section('title', 'Edit Service')
@section('page-title', 'Edit Service: ' . $service->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card">
        <form action="{{ route('services.update', $service) }}" method="POST" id="edit-service-form">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Service Name *</label>
                        <input type="text" name="name" value="{{ old("name", $service->name) }}" placeholder="Enter name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old("category", $service->category) }}" placeholder="Enter category" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Price *</label>
                        <input type="number" step="0.01" name="price" value="{{ old("price", $service->price) }}" placeholder="Enter price" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" value="{{ old("duration_minutes", $service->duration_minutes) }}" placeholder="Enter duration minutes" class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Enter description">{{ old('description', $service->description) }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-white/10">
                <button type="submit" class="btn-primary">Update Service</button>
                <a href="{{ route('services.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
