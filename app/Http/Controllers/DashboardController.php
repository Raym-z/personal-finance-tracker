<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::where('user_id', $user->id);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $sortOrder = $request->input('sort', 'desc'); // Default to 'desc' (Most Recent)
        $recentTransactions = $query->orderBy('created_at', $sortOrder)->get();

        // Calculate totals for the logged-in user
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->sum('amount');

        $totalBalance = $totalIncome - $totalExpenses;

        return view('dashboard', [
            'totalBalance' => $totalBalance,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'savingsGoals' => 0.00, // Placeholder
            'recentTransactions' => $recentTransactions,
        ]);
    }
}