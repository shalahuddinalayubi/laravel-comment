<?php

return [
    /**
     * If you wish to extends \Lara\Comment\Redirect\Redirect
     * for your implementation redirect that you want.
     */
    'redirector' => \Lara\Comment\Redirect\RedirectBackWithFragment::class,

    /**
     * This option controll how to validate the comment data.
     */
    'validator' => \Lara\Comment\Validation\DefaultValidator::class,

    /**
     * This option determine route to comment on a comment.
     */
    'route' => true,

    /**
     * The indentation of a comment.
     * The value must be greater than or equal 0.
     */
    'indentation' => 1,

    /**
     * The class model responsible for the owner of a comment.
     */
    'commentator' => \App\Models\User::class,

    /**
     * The class model for comment.
     */
    'comment' => \Lara\Comment\Comment::class,

    /**
     * The policy class.
     */
    'policy' => \Lara\Comment\CommentPolicy::class,
];
