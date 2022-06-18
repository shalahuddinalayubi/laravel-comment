<?php

namespace Lara\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    use HasFactory;
    
    /**
     * Get the comment associated with the user comment.
     */
    public function comments()
    {
        return $this->hasMany(config('comment.comment'));
    }

    /**
     * Get the parent usercommentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
