<?php

namespace Lara\Comment;

trait Commentable
{
    /**
     * Get all of the model's comments.
     */
    public function comments()
    {
        return $this->morphMany(config('comment.comment'), 'commentable');
    }
}
