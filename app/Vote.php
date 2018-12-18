<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    protected $fillable = [
        'type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function voteable(){
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where('type', 1);
    }

    public function votesAllowed(){
        return (bool) true;
    }

    public function upVotes(){
        return $this->votes->where('type', 'up');
    }

    public function downVotes(){
        return $this->votes->where('type', 'down');
    }

    public function getVote(User $user){
        return $this->votes()->where('user_id', $user->id);
    }

    public function getVotePoints()
    {
        $points = 0;
        foreach ($this->votes AS $vote) {
            if($vote->type == 'up')
                $points = $points + 1;
            elseif($vote->type == 'down')
                $points = $points - 1;
        }
        return $points;
    }

    public function checkVoted(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

}
