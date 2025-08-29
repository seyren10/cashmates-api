<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContributionRequest;
use App\Http\Requests\UpdateContributionRequest;
use App\Models\Contribution;
use App\Models\SavingsGoal;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SavingsGoal $savingsGoal)
    {
        return response()->json($savingsGoal->contributions()->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContributionRequest $request, SavingsGoal $savingsGoal)
    {
        $validated = $request->validated();
        Log::info($validated);
        $contribution = $savingsGoal->contributions()->create([...$validated, "user_id" => $request->user()->id]);

        if ($request->hasFile('receipt')) {
            $contribution->addMediaFromRequest('receipt')->toMediaCollection('receipts');
        }

        return response()->json($contribution->load('media'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contribution $contribution)
    {
        return response()->json($contribution->load('user', 'media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContributionRequest $request, Contribution $conribution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contribution $contribution)
    {
        Gate::authorize('delete', $contribution);

        $contribution->delete();

        return response()->json(['message' => 'Contribution deleted']);
    }
}
