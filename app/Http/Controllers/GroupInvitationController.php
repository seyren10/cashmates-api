<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinGroupInvitationRequest;
use App\Models\Group;

class GroupInvitationController extends Controller
{
    public function join(JoinGroupInvitationRequest $request)
    {
        $joinCode =  $request->validated('join_code');

        //find the group
        $group = Group::query()->where('join_code', $joinCode)->firstOrFail();

        //abort if the user is already in the group
        abort_if($group->users()->where('user_id', $request->user()->id)->exists(), 400, 'You are already a member of this group');

        //attach the user to the group as member
        $group->users()->attach($request->user()->id, ['role' => 'member']);

        return response()->json($group->load('users'));
    }
}
