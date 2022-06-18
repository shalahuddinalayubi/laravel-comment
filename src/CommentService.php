<?php

namespace Lara\Comment;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Lara\Comment\Contracts\IsCommentable;
use Lara\Comment\Contracts\IsCommentator;
use Lara\Comment\Exceptions\MustCommentableException;

class CommentService
{
    use AuthorizesRequests;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $commentable;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $commentator;

    /**
     * @var bool
     */
    protected $validateWithBag = false;

    public function __construct($commentable, $commentator, ?Request $request = null)
    {
        $this->setCommentable($commentable);
        $this->setCommentator($commentator);
        $this->request = $request ?? app(Request::class);
    }

    /**
     * Set commentable.
     * 
     * @param \Lara\Comment\Contracts\IsCommentable
     * @return this
     */
    public function setCommentable($commentable)
    {
        if (!$commentable instanceof IsCommentable) {
            throw new MustCommentableException();
        }

        $this->commentable = $commentable;

        return $this;
    }

    /**
     * Create new instace.
     * 
     * @param \Illuminate\Database\Eloquent\Model
     * @param \Illuminate\Database\Eloquent\Model
     * @param \Illuminate\Http\Request
     * @return this
     */
    public static function for($commentable, $commentator, ?Request $request = null)
    {
        return (new self($commentable, $commentator, $request));
    }

    /**
     * Set commentator.
     * 
     * @param \Illuminate\Database\Eloquent\Model
     * @return this
     */
    public function setCommentator(\Illuminate\Database\Eloquent\Model $commentator)
    {
        if (!$commentator instanceof IsCommentator) {
            throw new \Exception('The model should implements \Lara\Comment\Contracts\IsCommentator interface');
        }

        $this->commentator = $commentator;

        return $this;
    }

    /**
     * Validate the data.
     * 
     * @return array
     */
    public function validated()
    {
        $validator = config('comment.validator');

        $validator = new $validator($this->commentator, $this->request);

        $validator = $validator->getValidator();

        return $this->validateWithBag ? $validator->validateWithBag($this->errorBag()) : $validator->validated();
    }

    /**
     * Run validation with error bag.
     * 
     * @param bool $validateWithBag
     * @return this
     */
    public function validateWithBag($validateWithBag = true)
    {
        $this->validateWithBag = $validateWithBag;

        return $this;
    }

    /**
     * Make string error bag.
     * 
     * @return string
     */
    protected function errorBag()
    {
        return (string) $this->commentable->id . $this->request->method();
    }

    /**
     * Store the comment to the storage.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $data = $this->validated();
        
        $data['user_comment_id'] = $this->commentator->userComment()->firstOrCreate()->id;

        return $this->commentable->comments()->create($data);
    }

    /**
     * Update the exists comment.
     * 
     * @return bool
     */
    public function update()
    {
        $this->authorize('update', $this->commentable);

        return $this->commentable->update(['comment' => $this->validated()['comment']]);
    }

    /**
     * Remove the comment from the storage.
     * 
     * @return bool
     */
    public function destroy()
    {
        $this->authorize('delete', $this->commentable);

        return $this->commentable->delete();
    }
}
