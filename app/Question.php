<?php

namespace App;

use App\Traits\VoteTrait;

use App\Vote;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['body'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function votes(){
        return $this->morphMany('App\Vote', 'voteable');
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

    public function voteFromUser(User $user){
        return $this->votes()->where('user_id', $user->id);
    }
}
