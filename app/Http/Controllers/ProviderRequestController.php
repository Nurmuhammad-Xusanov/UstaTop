<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProviderRequest;
use Illuminate\Http\Request;

class ProviderRequestController extends Controller
{
    // ================= USER =================

    public function create()
    {
        $categories = Category::all();
        return view('provider.apply', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:13',
            'city' => 'required|string|max:100',
            'service_ids' => 'required|array',
        ]);

        ProviderRequest::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'city' => $request->city,
            'service_ids' => $request->service_ids,
            'status' => 'pending',
        ]);

        return back()->with('success', 'So‘rov yuborildi');
    }

    // ================= ADMIN =================

    public function index()
    {
        $this->adminOnly();

        $requests = ProviderRequest::latest()->get();
        
        return view('admin.provider_requests.index', compact('requests'));
    }

    public function show(ProviderRequest $providerRequest)
    {
        $this->adminOnly();

        return view('admin.provider_requests.show', compact('providerRequest'));
    }

    public function update(Request $request, ProviderRequest $providerRequest)
    {
        $this->adminOnly();

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $providerRequest->update([
            'status' => $request->status,
        ]);

        if ($request->status === 'approved') {
            $providerRequest->user->update([
                'role' => 'provider',
            ]);
        }

        return back()->with('success', 'Updated');
    }

    public function destroy(ProviderRequest $providerRequest)
    {
        $this->adminOnly();

        $providerRequest->delete();
        return back()->with('success', 'Deleted');
    }

    // ================= GUARD =================

    private function adminOnly()
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }
}
