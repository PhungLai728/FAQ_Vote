<?php

use Illuminate\Database\Seeder;

class VotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();
        for ($i = 1; $i <= 1; $i++) {
            $users->each(function ($user) {
                $vote = factory(\App\Vote::class)->make();
                $vote->user()->associate($user);
                $vote->save();

            });
        }
    }
}
