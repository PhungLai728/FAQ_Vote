<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Vote;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateVoteRequest;


class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateVoteRequest $request, Answer $answer){
        $this->authorize('vote', $answer);
        $answer->voteFromUser($request->user())->delete();
        $answer->votes()->create([
            'type' => $request->type,
            'user_id' => $request->user()->id
        ]);
        return response()->json(null, 200);
    }

    public function remove(Request $request, Answer $answer){
        $this->authorize('vote', $answer);
        $answer->voteFromUser($request->user())->delete();
        return response()->json(null, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function vote(Request $request)
    {
        $answer = Answer::where('id', $request->answer)->first();
        if ($answer) {
            if ($answer->vote($request->action)) {
                return response()->json([
                    'status' => 'success',
                    'points' => $answer->getPointsAttribute(),
                ], 201);
            } else {
                return response()->json([
                    'error' => 'User not logged in.'
                ], 401);
            }
        } else {
            return response()->json([
                'error' => 'Comment has been deleted.'
            ], 401);
        }


    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Answer $answer){
        $response = [
            'up' => null,
            'down' => null,
            'can_vote' => $answer->votesAllowed(),
            'user_vote' => null
        ];
        if($answer->votesAllowed()){
            $response['up'] = $answer->upVotes()->count();
            $response['down'] = $answer->downVotes()->count();
        }
        if($request->user()){
            $voteFromUser = $answer->voteFromUser($request->user())->first();
            $response['user_vote'] = $voteFromUser ? $voteFromUser->type : null;
        }

        return response()->json([
            'data' => $response
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function checkIfVoted(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function getVote(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->first();
    }

    public function getPointsAttribute()
    {
        $points = 0;
        foreach ($this->votes AS $vote) {
            if($vote->status == 'upvote')
                $points = $points + 1;
            elseif($vote->status == 'downvote')
                $points = $points - 1;
        }
        return $points;
    }

}
