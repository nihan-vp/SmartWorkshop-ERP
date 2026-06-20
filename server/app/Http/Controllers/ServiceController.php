<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
        }
        $services = $query->latest()->paginate((int) request('limit', 15));
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:100',
        ]);
        Service::create($validated);
        return redirect()->route('services.index')->with('success', 'Service added successfully!');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:100',
        ]);
        $service->update($validated);
        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully!');
    }
}
