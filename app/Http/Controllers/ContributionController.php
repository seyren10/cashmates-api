<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContributionRequest;
use App\Http\Requests\UpdateContributionRequest;
use App\Models\Contribution;
use App\Models\SavingsGoal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SavingsGoal $savingsGoal)
    {
        $contributions = $savingsGoal->contributions()
            ->with(['user', 'media'])
            ->withCount('comments')
            ->latest()
            ->get();
        return response()->json($contributions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContributionRequest $request, SavingsGoal $savingsGoal)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $contribution = $savingsGoal->contributions()->create([...$validated, "user_id" => $request->user()->id]);

            if ($request->hasFile('receipt')) {
                $contribution->addMediaFromRequest('receipt')->toMediaCollection('receipts');
            }

            DB::commit();
            return response()->json($contribution->load('media'), 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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
    public function update(UpdateContributionRequest $request, Contribution $contribution)
    {
        try {

            Gate::authorize('update', $contribution);

            DB::beginTransaction();

            $validated = $request->safe()->except('receipt');
            $contribution->fill($validated);
            $contribution->save();

            if ($request->hasFile('receipt')) {
                $contribution->clearMediaCollection('receipts');
                $contribution->addMediaFromRequest('receipt')->toMediaCollection('receipts');
            }

            DB::commit();
            return response()->noContent();
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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
