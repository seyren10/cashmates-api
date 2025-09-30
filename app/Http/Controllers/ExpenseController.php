<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\SavingsGoal;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $expense = $savingsGoal->expenses()->create([...$validated, "user_id" => $request->user()->id]);

            if ($request->hasFile('receipt')) {
                $expense->addMediaFromRequest('receipt')->toMediaCollection('receipts');
            }

            DB::commit();
            return response()->json($expense->load('media'), 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {

        try {

            Gate::authorize('update', $expense);

            DB::beginTransaction();

            $validated = $request->safe()->except('receipt');
            $expense->fill($validated);
            $expense->save();

            if ($request->hasFile('receipt')) {
                $expense->clearMediaCollection('receipts');
                $expense->addMediaFromRequest('receipt')->toMediaCollection('receipts');
            }

            DB::commit();
            return response()->noContent();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

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
