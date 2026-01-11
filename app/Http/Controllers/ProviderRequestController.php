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
        $user = auth()->user();

        if (! $user->telegram_id) {
            return back()->withErrors([
                'telegram' => 'Avval Telegramni ulang',
            ]);
        }

        if ($user->role === 'provider') {
            return back()->withErrors([
                'role' => 'Siz allaqachon provaydersiz',
            ]);
        }

        if (ProviderRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists()
        ) {

            return back()->withErrors([
                'request' => 'So‘rovingiz ko‘rib chiqilmoqda',
            ]);
        }

        $request->validate([
            'phone' => 'required|string|max:13',
            'city' => 'required|string|max:100',
            'service_ids' => 'required|array',
        ]);

        ProviderRequest::create([
            'user_id' => $user->id,
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

        $categories = Category::whereIn('id', $providerRequest->service_ids)->get();

        return view('admin.provider_requests.show', compact('providerRequest', 'categories'));
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
        return redirect()->route('provider-requests.index')->with('success', 'Deleted');
    }

    // ================= GUARD =================

    private function adminOnly()
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }
}
