<?php

namespace Lara\Comment\Redirect;

use Illuminate\Support\Facades\URL;

class RedirectBack extends Redirect
{
    /**
     * Set the URL to redirect to on validation error.
     * 
     * @return string
     */
    public function redirectTo()
    {
        return URL::previous();
    }
}
