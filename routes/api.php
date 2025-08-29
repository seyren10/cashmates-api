<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupInvitationController;
use App\Http\Controllers\SavingsGoalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('groups/join', [GroupInvitationController::class, 'join']);
    Route::apiResource('groups', GroupController::class);
    Route::apiResource('groups.savings-goals', SavingsGoalController::class)->shallow();
    // Route::apiResource('savings-goals', SavingsGoalController::class);

    Route::apiResource('savings-goals.contributions', ContributionController::class)->shallow();
    Route::apiResource('savings-goals.expenses', ExpenseController::class)->shallow();

    Route::apiResource('contributions.comments', CommentController::class)->shallow();
    Route::apiResource('expenses.comments', CommentController::class)->shallow();
});
