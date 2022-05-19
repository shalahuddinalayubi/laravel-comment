<?php

namespace Lara\Comment\Validation;

use Illuminate\Support\Facades\Validator as LaravelValidator;

abstract class Validator
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $commentator;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @param \Illuminate\Database\Eloquent\Model
     * @param \Illuminate\Http\Request
     */
    public function __construct($commentator, $request)
    {
        $this->commentator = $commentator;
        $this->request = $request;
    }

    /**
     * Get Laravel validator.
     * 
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator()
    {
        $validator = LaravelValidator::make($this->data(), $this->rules());

        $validator->setException($this->getValidationException($validator));

        return $validator;
    }

    /**
     * Make redirector for error validation.
     * 
     * @return \Lara\Comment\Redirect\Redirect
     */
    protected function getValidationException($validator)
    {
        $redirector = config('comment.redirector');

        $redirector = new $redirector($validator);

        return $redirector->getValidationException();
    }

    /**
     * Data to store.
     * 
     * @return array
     */
    abstract public function data();

    /**
     * Rules for validation.
     * 
     * @return array
     */
    abstract public function rules();
}
