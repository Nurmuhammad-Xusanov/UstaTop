<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProviderRequest;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Provider;

class ProviderRequestController extends Controller
{

    public function create()
    {
        $categories = Category::all();
        return view('provider.apply', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:13|phone:Uzbekistan',
            'city' => 'required|string|max:100',
            'service_ids' => 'required|array',
        ]);

        ProviderRequest::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'city' => $request->city,
            'service_ids' => $request->service_ids,
        ]);

        return redirect()->back()->with('success', 'Sizning so\'rovingiz qabul qilindi. Tez orada siz bilan bog\'lanamiz.');
    }

}