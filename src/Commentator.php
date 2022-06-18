<?php

namespace Lara\Comment;

use Lara\Comment\UserComment;

trait Commentator
{
    /**
     * Get the userComment.
     */
    public function userComment()
    {
        return $this->morphOne(UserComment::class, 'commentable');
    }
}
