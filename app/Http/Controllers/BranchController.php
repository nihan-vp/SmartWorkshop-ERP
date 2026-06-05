<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::where('workshop_id', Auth::user()->workshop_id)->orderBy('name')->get();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['workshop_id'] = Auth::user()->workshop_id;

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        if ($branch->workshop_id !== Auth::user()->workshop_id) abort(403);
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if ($branch->workshop_id !== Auth::user()->workshop_id) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->workshop_id !== Auth::user()->workshop_id) abort(403);
        
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }

    public function switchBranch(Request $request)
    {
        $branchId = $request->input('branch_id');

        if ($branchId) {
            $branch = Branch::where('id', $branchId)->where('workshop_id', Auth::user()->workshop_id)->firstOrFail();
            session(['active_branch_id' => $branch->id]);
        } else {
            session()->forget('active_branch_id');
        }

        return back()->with('success', 'Branch switched successfully.');
    }
}
