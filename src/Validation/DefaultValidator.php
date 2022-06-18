<?php

namespace Lara\Comment\Validation;

class DefaultValidator extends Validator
{
    /**
     * Data to store.
     * 
     * @return array
     */
    public function data()
    {
        return [
            'comment' => $this->request->get('comment'),
        ];
    }

    /**
     * Rules for validation.
     * 
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'required',
        ];
    }
}
