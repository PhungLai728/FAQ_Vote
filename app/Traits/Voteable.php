<?php

namespace App\Traits;

use App\Answer;
use App\User;
use Auth;

trait Voteable {

    public function votes()
    {
        return $this->morphMany('App\User', 'votable');
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

    public function vote($status)
    {
        if(Auth::user() != null){
            $user = Auth::user();
            if(! $this->checkIfVoted($user)){
                $vote = new Vote();
                $vote->user_id = Auth::user()->id;
                $vote->status = $status;
                $this->votes()->save($vote);
                return true;
            } else {
                $vote = $this->getVote($user);
                $vote->status = $status;
                $vote->save();
                return true;
            }
        }
        return false;
    }

}