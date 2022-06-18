<?php

namespace Lara\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Lara\Comment\Contracts\IsCommentable;

class Comment extends Model implements IsCommentable
{
    use HasFactory, Commentable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment',
        'user_comment_id',
    ];

    /**
     * Get the parent commentable model.
     * 
     * @return mixed
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function userComment()
    {
        return $this->belongsTo(UserComment::class);
    }

    /**
     * Get owner of a comment.
     * 
     * @return mixed
     */
    public function owner()
    {
        return $this->belongsTo(UserComment::class, 'user_comment_id')
                    ->getResults()
                    ->commentable;
    }
}
