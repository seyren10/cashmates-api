<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\SavingsGoal;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SavingsGoal $savingsGoal)
    {
        $expenses = $savingsGoal
            ->expenses()
            ->with(['user', 'media'])
            ->withCount('comments')
            ->latest()
            ->get();

        return response()->json($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request, SavingsGoal $savingsGoal)
    {
        $validated = $request->validated();
        $expense = $savingsGoal->expenses()->create([...$validated, 'user_id' => $request->user()->id]);

        if ($request->hasFile('receipt'))
            $expense->addMediaFromRequest('receipt')->toMediaCollection('receipts');


        return response()->json($expense->load('media'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return response()->json($expense->load('user', 'media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        Gate::authorize('delete', $expense);

        $expense->delete();
        return response()->json(['message' => 'Expense deleted']);
    }
}
