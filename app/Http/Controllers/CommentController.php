<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Contribution;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $id)
    {
        $model = $this->getModel($request, $id);

        return response()->json($model->comments()->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, int $id)
    {
        $model = $this->getModel($request, $id);

        $validated = $request->validated();


        $comment = $model->comments()->create([...$validated, 'user_id' => $request->user()->id]);

        return response()->json($comment->load('user'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json($comment->load('user', 'commentable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }

    private function getModel(Request $request, int $id)
    {
        if ($request->routeIs('contributions.*'))
            return Contribution::findOrFail($id);
        else if ($request->routeIs('expenses.*'))
            return Expense::findOrFail($id);
        else
            abort(404, 'Invalid comment type');
    }
}
