<?php

if (! function_exists('reply_able')) {
    /**
     * Determine if cureent indentation is reply able.
     * 
     * @param int $indentation
     * @return bool
     */
    function reply_able($indentation)
    {
        return $indentation < config('comment.indentation');
    }
}
