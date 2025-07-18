<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class BudgetGoalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $budgets = Budget::where('user_id', $user->id)->get();
        $goals = Goal::where('user_id', $user->id)->get();

        // Calculate spent for each budget
        $budgets = $budgets->map(function($budget) use ($user) {
            $query = \App\Models\Transaction::where('user_id', $user->id)
                ->where('tag', $budget->category)
                ->where('type', 'expense');
            // Filter by period
            if ($budget->period === 'monthly') {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
            } else {
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
            }
            $query->whereBetween('created_at', [$start, $end]);
            // Filter by custom start/end date if set
            if ($budget->start_date) {
                $query->whereDate('created_at', '>=', $budget->start_date);
            }
            if ($budget->end_date) {
                $query->whereDate('created_at', '<=', $budget->end_date);
            }
            $budget->spent = $query->sum('amount');
            return $budget;
        });

        return view('budgets_goals.index', compact('budgets', 'goals'));
    }

    // Budget CRUD
    public function createBudget()
    {
        $user = Auth::user();
        // Get all tags (predefined + custom)
        $allTags = \App\Models\Transaction::getAllTags($user->id);
        $tags = array_merge($allTags['income'], $allTags['expense']);
        sort($tags);
        return view('budgets_goals.create_budget', compact('tags'));
    }

    public function storeBudget(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,weekly',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $user = Auth::user();
        Budget::create([
            'user_id' => $user->id,
            'category' => $request->category,
            'amount' => $request->amount,
            'period' => $request->period,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return redirect()->route('budgets_goals.index')->with('success', 'Budget created successfully!');
    }
    public function editBudget($id)
    {
        $user = Auth::user();
        $budget = Budget::where('user_id', $user->id)->findOrFail($id);
        // Get all tags (predefined + custom)
        $allTags = \App\Models\Transaction::getAllTags($user->id);
        $tags = array_merge($allTags['income'], $allTags['expense']);
        sort($tags);
        return view('budgets_goals.edit_budget', compact('budget', 'tags'));
    }

    public function updateBudget(Request $request, $id)
    {
        $user = Auth::user();
        $budget = Budget::where('user_id', $user->id)->findOrFail($id);
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,weekly',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $budget->update([
            'category' => $request->category,
            'amount' => $request->amount,
            'period' => $request->period,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return redirect()->route('budgets_goals.index')->with('success', 'Budget updated successfully!');
    }

    public function destroyBudget($id)
    {
        $user = Auth::user();
        $budget = Budget::where('user_id', $user->id)->findOrFail($id);
        $budget->delete();
        return redirect()->route('budgets_goals.index')->with('success', 'Budget deleted successfully!');
    }

    // Goal CRUD
    public function createGoal()
    {
        return view('budgets_goals.create_goal');
    }

    public function storeGoal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
        ]);
        $user = Auth::user();
        Goal::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'target_date' => $request->target_date,
        ]);
        return redirect()->route('budgets_goals.index')->with('success', 'Goal created successfully!');
    }
    public function editGoal($id)
    {
        $user = Auth::user();
        $goal = Goal::where('user_id', $user->id)->findOrFail($id);
        return view('budgets_goals.edit_goal', compact('goal'));
    }

    public function updateGoal(Request $request, $id)
    {
        $user = Auth::user();
        $goal = Goal::where('user_id', $user->id)->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
        ]);
        $goal->update([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'target_date' => $request->target_date,
        ]);
        return redirect()->route('budgets_goals.index')->with('success', 'Goal updated successfully!');
    }

    public function destroyGoal($id)
    {
        $user = Auth::user();
        $goal = Goal::where('user_id', $user->id)->findOrFail($id);
        $goal->delete();
        return redirect()->route('budgets_goals.index')->with('success', 'Goal deleted successfully!');
    }
} 