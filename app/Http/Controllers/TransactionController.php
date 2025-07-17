<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

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
        return view('transaction', [
            'transaction' => null,
            'mode' => 'create',
            'type' => $type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
        ]);

        $request->user()->transactions()->create($request->only('description', 'amount', 'type'));

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
        $transaction = $user->transactions()->findOrFail($id);
        return view('transaction', [
            'transaction' => $transaction,
            'mode' => 'edit',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
        ]);

        $user = Auth::user();
        $transaction = $user->transactions()->findOrFail($id);
        $transaction->update($request->only('description', 'amount', 'type'));

        return redirect()->route('dashboard')->with('success', 'Transaction updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $transaction = $user->transactions()->findOrFail($id);
        $transaction->delete();

        return redirect()->route('dashboard')->with('success', 'Transaction deleted!');
    }
}