<?php

use App\Http\Controllers\Controller;
use App\Models\ProviderRequest;
use Illuminate\Http\Request;

class ProviderRequestController extends Controller
{
    // Existing methods...

    public function index()
    {
        $requests = ProviderRequest::all();
        return view('admin.provider_requests.index', compact('requests'));
    }

    public function show(ProviderRequest $providerRequest)
    {
        return view('admin.provider_requests.show', compact('providerRequest'));
    }

    public function update(Request $request, ProviderRequest $providerRequest)
    {
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
    }

    public function destroy($id)
    {
        $request = ProviderRequest::findOrFail($id);
        $request->delete();
        return redirect()->route('provider-requests.index')->with('success', 'Provider request deleted successfully.');
    }
}