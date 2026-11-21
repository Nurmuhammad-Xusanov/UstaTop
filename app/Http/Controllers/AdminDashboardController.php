<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProviderRequest;
use App\Models\ServiceRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /**
     * Admin Dashboard Overview
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_categories' => Category::count(),
            'total_requests' => ServiceRequests::count(),
            'pending_requests' => ServiceRequests::where('status', 'pending')->count(),
            'accepted_requests' => ServiceRequests::where('status', 'accepted')->count(),
            'completed_requests' => ServiceRequests::where('status', 'completed')->count(),
            'pending_provider_requests' => ProviderRequest::where('status', 'pending')->count(),
            'active_sessions' => DB::table('sessions')->count(),
        ];

        $recentRequests = ServiceRequests::with(['user', 'category', 'provider'])
            ->latest()
            ->take(6)
            ->get();

        $recentProviderRequests = ProviderRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(6)->get();

        return view('admin.dashboard.index', compact('stats', 'recentRequests', 'recentProviderRequests', 'recentUsers'));
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with('categories')->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Update User Role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:client,provider,admin',
        ]);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'O\'z rolingizni pasaytira olmaysiz.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Foydalanuvchi roli ({$user->name}) '{$request->role}' ga o'zgartirildi.");
    }

    /**
     * Toggle Email Verification
     */
    public function toggleVerifyUser(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = "Foydalanuvchi ({$user->name}) pochtasi tasdiqlanmagan holatga o'tkazildi.";
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = "Foydalanuvchi ({$user->name}) pochtasi muvaffaqiyatli tasdiqlandi.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete User
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'O\'zingizni o\'chira olmaysiz.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Foydalanuvchi ({$name}) o'chirildi.");
    }

    /**
     * Service Requests Management
     */
    public function serviceRequests(Request $request)
    {
        $query = ServiceRequests::with(['user', 'category', 'provider']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $requests = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.service_requests.index', compact('requests', 'categories'));
    }

    /**
     * Update Service Request Status
     */
    public function updateServiceRequestStatus(Request $request, ServiceRequests $serviceRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,provider_done,completed,cancelled',
        ]);

        $serviceRequest->update(['status' => $request->status]);

        // Send Telegram notification if client has telegram_id
        if ($serviceRequest->user && $serviceRequest->user->telegram_id) {
            $token = config('services.telegram.token');
            if ($token) {
                $statusLabels = [
                    'pending' => 'kutilmoqda',
                    'accepted' => 'qabul qilindi',
                    'provider_done' => 'usta tomonidan bajarildi',
                    'completed' => 'yakunlandi',
                    'cancelled' => 'bekor qilindi',
                ];
                $label = $statusLabels[$request->status] ?? $request->status;
                $msg = "ℹ️ Buyurtma #{$serviceRequest->id} holati admin tomonidan o'zgartirildi: {$label}.";
                try {
                    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                        'chat_id' => $serviceRequest->user->telegram_id,
                        'text' => $msg,
                    ]);
                } catch (\Exception $e) {
                    Log::error("Telegram notify error: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', "Buyurtma holati yangilandi.");
    }

    /**
     * Delete Service Request
     */
    public function destroyServiceRequest(ServiceRequests $serviceRequest)
    {
        $serviceRequest->delete();
        return back()->with('success', 'Buyurtma o\'chirildi.');
    }

    /**
     * Active Sessions & Security
     */
    public function sessions()
    {
        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('last_activity', 'desc')
            ->paginate(20);

        return view('admin.sessions.index', compact('sessions'));
    }
}
