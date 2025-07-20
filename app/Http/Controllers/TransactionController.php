<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserSetting;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type');
        $user = Auth::user();
        
        // Get custom tags for this user
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Predefined tags for different transaction types
        $predefinedTags = [
            'income' => ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'],
            'expense' => ['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education', 'Other']
        ];
        
        // Add custom tags to the appropriate category
        foreach ($customTags as $tagName => $tagInfo) {
            if ($tagInfo['type'] === 'income') {
                $predefinedTags['income'][] = $tagName;
            } else {
                $predefinedTags['expense'][] = $tagName;
            }
        }
        
        // Color mapping for predefined tags
        $tagColors = [
            // Income tags
            'Salary' => '#198754',
            'Freelance' => '#0dcaf0',
            'Investment' => '#0d6efd',
            'Gift' => '#ffc107',
            'Bonus' => '#198754',
            'Other' => '#6c757d',
            
            // Expense tags
            'Food' => '#dc3545',
            'Transportation' => '#0d6efd',
            'Housing' => '#212529',
            'Utilities' => '#0dcaf0',
            'Entertainment' => '#ffc107',
            'Healthcare' => '#dc3545',
            'Shopping' => '#0d6efd',
            'Education' => '#0dcaf0',
        ];
        
        // Add custom tag colors
        foreach ($customTags as $tagName => $tagInfo) {
            $tagColors[$tagName] = $tagInfo['color'];
        }
        
        return view('transaction', [
            'transaction' => null,
            'mode' => 'create',
            'type' => $type,
            'predefinedTags' => $predefinedTags,
            'tagColors' => $tagColors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'tag' => 'required|string|max:100',
            'transaction_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $data = [
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'tag' => $request->input('tag'),
            'user_id' => $user->id,
        ];

        // Handle custom date if provided, otherwise use current time
        if ($request->filled('transaction_date')) {
            $data['created_at'] = $request->input('transaction_date');
            $data['updated_at'] = $request->input('transaction_date');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('transaction-images', 'public');
            $data['image_path'] = $imagePath;
        }

        Transaction::create($data);

        return redirect()->route('dashboard')->with('success', 'Transaction added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $transaction = Transaction::where('user_id', $user->id)->findOrFail($id);

        // Get custom tags for this user
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);

        // Predefined tags for different transaction types
        $predefinedTags = [
            'income' => ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'],
            'expense' => ['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education', 'Other']
        ];

        // Add custom tags to the appropriate category
        foreach ($customTags as $tagName => $tagInfo) {
            if ($tagInfo['type'] === 'income') {
                $predefinedTags['income'][] = $tagName;
            } else {
                $predefinedTags['expense'][] = $tagName;
            }
        }

        // Color mapping for predefined tags
        $tagColors = [
            // Income tags
            'Salary' => '#198754',
            'Freelance' => '#0dcaf0',
            'Investment' => '#0d6efd',
            'Gift' => '#ffc107',
            'Bonus' => '#198754',
            'Other' => '#6c757d',
            
            // Expense tags
            'Food' => '#dc3545',
            'Transportation' => '#0d6efd',
            'Housing' => '#212529',
            'Utilities' => '#0dcaf0',
            'Entertainment' => '#ffc107',
            'Healthcare' => '#dc3545',
            'Shopping' => '#0d6efd',
            'Education' => '#0dcaf0',
        ];

        // Add custom tag colors
        foreach ($customTags as $tagName => $tagInfo) {
            $tagColors[$tagName] = $tagInfo['color'];
        }

        return view('transaction', [
            'transaction' => $transaction,
            'mode' => 'edit',
            'predefinedTags' => $predefinedTags,
            'tagColors' => $tagColors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'tag' => 'required|string|max:100',
            'transaction_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $transaction = Transaction::where('user_id', $user->id)->findOrFail($id);
        
        $data = [
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'tag' => $request->input('tag'),
        ];

        // Handle custom date if provided, otherwise keep existing date
        if ($request->filled('transaction_date')) {
            $data['created_at'] = $request->input('transaction_date');
            $data['updated_at'] = now(); // Always update the updated_at timestamp
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($transaction->image_path) {
                Storage::disk('public')->delete($transaction->image_path);
            }
            
            $imagePath = $request->file('image')->store('transaction-images', 'public');
            $data['image_path'] = $imagePath;
        }

        $transaction->update($data);

        return redirect()->route('dashboard')->with('success', 'Transaction updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $transaction = Transaction::where('user_id', $user->id)->findOrFail($id);
        
        // Delete associated image if exists
        if ($transaction->image_path) {
            Storage::disk('public')->delete($transaction->image_path);
        }
        
        $transaction->delete();

        return redirect()->route('dashboard')->with('success', 'Transaction deleted!');
    }
}