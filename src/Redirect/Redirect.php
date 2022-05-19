<?php

namespace Lara\Comment\Redirect;

use Illuminate\Validation\ValidationException;

abstract class Redirect
{
    /**
     * @var \Illuminate\Validation\ValidationException
     */
    protected $validationException;

    /**
     * Construct
     * 
     * @param \Illuminate\Validation\Validator $validator
     */
    public function __construct($validator)
    {
        $this->validationException = new ValidationException($validator);
        $this->validationException->redirectTo($this->redirectTo());
    }

    /**
     * Get validation exception.
     * 
     * @return \Illuminate\Validation\Validator
     */
    public function getValidationException()
    {
        return $this->validationException;
    }

    /**
     * Set the URL to rediret to on validation error.
     * 
     * @return string
     */
    abstract public function redirectTo();
}
