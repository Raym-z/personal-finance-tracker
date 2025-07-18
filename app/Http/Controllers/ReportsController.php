<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filters
        $from = $request->input('from', Carbon::now()->startOfYear()->toDateString());
        $to = $request->input('to', Carbon::now()->endOfYear()->toDateString());
        $type = $request->input('type', 'both'); // income, expense, both
        $category = $request->input('category', null);

        // Base query
        $query = Transaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$from, $to]);
        if ($type !== 'both') {
            $query->where('type', $type);
        }
        if ($category) {
            $query->where('tag', $category);
        }
        $transactions = $query->get();

        // Summary calculations
        $totalIncomeQuery = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereBetween('created_at', [$from, $to]);
        $totalExpensesQuery = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('created_at', [$from, $to]);
        if ($category) {
            $totalIncomeQuery->where('tag', $category);
            $totalExpensesQuery->where('tag', $category);
        }
        $totalIncome = $totalIncomeQuery->sum('amount');
        $totalExpenses = $totalExpensesQuery->sum('amount');
        $netWorth = $totalIncome - $totalExpenses;
        $savingsRate = $totalIncome > 0 ? round((($totalIncome - $totalExpenses) / $totalIncome) * 100, 1) : 0;

        // Monthly trends (line chart)
        $months = [];
        $incomeTrend = [];
        $expenseTrend = [];
        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();
        $current = $start->copy();
        while ($current <= $end) {
            $monthLabel = $current->format('M Y');
            $months[] = $monthLabel;
            $incomeQuery = Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->whereBetween('created_at', [$current->copy()->startOfMonth(), $current->copy()->endOfMonth()]);
            $expenseQuery = Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereBetween('created_at', [$current->copy()->startOfMonth(), $current->copy()->endOfMonth()]);
            if ($category) {
                $incomeQuery->where('tag', $category);
                $expenseQuery->where('tag', $category);
            }
            $income = $incomeQuery->sum('amount');
            $expense = $expenseQuery->sum('amount');
            $incomeTrend[] = round($income, 2);
            $expenseTrend[] = round($expense, 2);
            $current->addMonth();
        }

        // Category breakdown (pie chart)
        $breakdown = Transaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$from, $to]);
        if ($type !== 'both') {
            $breakdown->where('type', $type);
        }
        if ($category) {
            $breakdown->where('tag', $category);
        }
        $breakdown = $breakdown->selectRaw('tag, SUM(amount) as total_amount')
            ->groupBy('tag')
            ->orderBy('total_amount', 'desc')
            ->get();
        $breakdownLabels = $breakdown->pluck('tag');
        $breakdownData = $breakdown->pluck('total_amount');

        return view('reports', [
            'summary' => [
                'netWorth' => $netWorth,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'savingsRate' => $savingsRate,
            ],
            'filters' => [
                'from' => $from,
                'to' => $to,
                'type' => $type,
                'category' => $category,
            ],
            'trend' => [
                'months' => $months,
                'income' => $incomeTrend,
                'expenses' => $expenseTrend,
            ],
            'breakdown' => [
                'labels' => $breakdownLabels,
                'data' => $breakdownData,
            ],
        ]);
    }
} 