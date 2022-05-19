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
        'user_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(config('comment.commentator'));
    }
}
