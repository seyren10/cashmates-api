<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Group;
use App\Models\SavingsGoal;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
        ]);
        // $users =  User::factory(10)
        //     ->has(
        //         Group::factory(3)
        //             ->has(
        //                 SavingsGoal::factory(rand(2, 3))
        //                     ->has(Expense::factory(rand(2, 3))
        //                         ->state(function () {
        //                             return [
        //                                 'user_id' => User::inRandomOrder()->first()->id
        //                             ];
        //                         })
        //                         ->has(Comment::factory(rand(2, 3))->state(function () {
        //                             return [
        //                                 'user_id' => User::inRandomOrder()->first()->id
        //                             ];
        //                         })))
        //                     ->has(Contribution::factory(rand(2, 3))
        //                         ->state(function () {
        //                             return [
        //                                 'user_id' => User::inRandomOrder()->first()->id
        //                             ];
        //                         })
        //                         ->has(Comment::factory(rand(2, 3))->state(function () {
        //                             return [
        //                                 'user_id' => User::inRandomOrder()->first()->id
        //                             ];
        //                         })))
        //             )
        //     )->create();
            
        // Group::factory(3)->create()->each(function ($group) use ($users) {
        //     $owner = $users->random();
        //     $group->users()->attach($owner->id, ['role' => 'owner']);

        //     $members = $users->random(rand(2, 3));
        //     foreach ($members as $member) {
        //         $group->users()->attach($member->id, ['role' => 'member']);
        //     }

        //     SavingsGoal::factory(rand(2, 3))->create(['group_id' => $group->id]);


        // });
    }
}
