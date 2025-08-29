<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavingsGoalRequest;
use App\Http\Requests\UpdateSavingsGoalRequest;
use App\Models\Group;
use App\Models\SavingsGoal;
use Illuminate\Support\Facades\Gate;

class SavingsGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        Gate::authorize('view', $group);

        return response()->json($group->savingsGoals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSavingsGoalRequest $request, Group $group)
    {
        Gate::authorize('create', $group);

        $validated = $request->validated();

        $goal = $group->savingsGoals()->create($validated);

        return response()->json($goal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SavingsGoal $savingsGoal)
    {
        return response()->json($savingsGoal->load(['group', 'contributions', 'expenses']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSavingsGoalRequest $request, SavingsGoal $savingsGoal)
    {
        Gate::authorize('update', $savingsGoal->group);

        $validated = $request->validated();

        $savingsGoal->update($validated->all());

        return response()->json($savingsGoal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingsGoal $savingsGoal)
    {
        Gate::authorize('delete', $savingsGoal->group);

        $savingsGoal->delete();

        return response()->json(['message' => 'Savings goal deleted']);
    }
}
