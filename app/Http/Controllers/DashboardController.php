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

        // --- Pie Chart Data Preparation ---
        // Expenses by tag
        $expensesByTags = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->selectRaw('tag, SUM(amount) as total_amount, COUNT(*) as transaction_count')
            ->groupBy('tag')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Incomes by tag
        $incomesByTags = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->selectRaw('tag, SUM(amount) as total_amount, COUNT(*) as transaction_count')
            ->groupBy('tag')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Both (all transactions by tag)
        $bothByTags = Transaction::where('user_id', $user->id)
            ->selectRaw('tag, SUM(CASE WHEN type = "income" THEN amount ELSE -amount END) as total_amount, COUNT(*) as transaction_count')
            ->groupBy('tag')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Helper to prepare chart data
        $prepareChartData = function($tagData) use ($user) {
            $chartData = [
                'labels' => [],
                'data' => [],
                'colors' => [],
                'backgroundColors' => []
            ];
            foreach ($tagData as $item) {
                $chartData['labels'][] = $item->tag;
                $chartData['data'][] = round(abs($item->total_amount), 2); // always positive for chart
                $tagColor = Transaction::getTagColor($item->tag, $user->id);
                $chartData['colors'][] = $tagColor;
                $hexColor = $this->getBootstrapColorHex($tagColor);
                $chartData['backgroundColors'][] = $hexColor;
            }
            return $chartData;
        };

        $chartDataExpenses = $prepareChartData($expensesByTags);
        $chartDataIncomes = $prepareChartData($incomesByTags);
        $chartDataBoth = $prepareChartData($bothByTags);

        return view('dashboard', [
            'totalBalance' => $totalBalance,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'savingsGoals' => 0.00, // Placeholder
            'recentTransactions' => $recentTransactions,
            'spendingByTags' => $expensesByTags,
            'incomeByTags' => $incomesByTags,
            'bothByTags' => $bothByTags,
            'chartDataExpenses' => $chartDataExpenses,
            'chartDataIncomes' => $chartDataIncomes,
            'chartDataBoth' => $chartDataBoth,
        ]);
    }

    /**
     * Convert Bootstrap color classes to hex colors
     */
    private function getBootstrapColorHex($bootstrapColor)
    {
        $colorMap = [
            'primary' => '#0d6efd',
            'secondary' => '#6c757d',
            'success' => '#198754',
            'danger' => '#dc3545',
            'warning' => '#ffc107',
            'info' => '#0dcaf0',
            'light' => '#f8f9fa',
            'dark' => '#212529',
        ];

        return $colorMap[$bootstrapColor] ?? '#6c757d';
    }
}