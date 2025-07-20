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

        // Filter by tags (support multiple tags)
        if ($request->filled('tags')) {
            $tags = is_array($request->tags) ? $request->tags : [$request->tags];
            $query->whereIn('tag', $tags);
        } elseif ($request->filled('tag')) {
            // Backward compatibility for single tag
            $query->where('tag', $request->tag);
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
            
            // Calculate total for percentage calculation
            $total = $tagData->sum('total_amount');
            
            foreach ($tagData as $item) {
                $amount = abs($item->total_amount);
                $percentage = ($amount / $total) * 100;
                
                // Include all segments, but ensure minimum display size for very small ones
                $chartData['labels'][] = $item->tag;
                $chartData['data'][] = round($amount, 2);
                $tagColor = Transaction::getTagColor($item->tag, $user->id);
                $chartData['colors'][] = $tagColor;
                $hexColor = Transaction::getTagHexColor($item->tag, $user->id);
                $chartData['backgroundColors'][] = $hexColor;
            }
            return $chartData;
        };

        $chartDataExpenses = $prepareChartData($expensesByTags);
        $chartDataIncomes = $prepareChartData($incomesByTags);
        $chartDataBoth = $prepareChartData($bothByTags);

        // Get all available tags for filtering (including custom tags)
        $allTags = Transaction::getAllTags($user->id);
        $availableTags = array_merge($allTags['income'], $allTags['expense']);
        sort($availableTags);
        
        // Get tags by type for dynamic filtering
        $incomeTags = $allTags['income'];
        $expenseTags = $allTags['expense'];
        sort($incomeTags);
        sort($expenseTags);

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
            'availableTags' => $availableTags,
            'incomeTags' => $incomeTags,
            'expenseTags' => $expenseTags,
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