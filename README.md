## Instalation
```bash
composer require alayubi/laravel-comment
```
you must publish the migration with:
```bash
php artisan vendor:publish --tag=lara-comment-migrations
```
and rerun the migration.

## Config
You can publish the config file with:
```bash
php artisan vendor:publish --tag=lara-comment-config
```
## Usage

### Commentable
If you want a model can be commented you can implements `\Lara\Comment\Contracts\IsCommentable` interface and add `\Lara\Comment\Commentable` trait for the implementation.
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lara\Comment\Commentable;
use Lara\Comment\Contracts\IsCommentable;

class Post extends Model implements IsCommentable
{
    use Commentable;
}
```
### Commentator
Commentator is a model that comment on a model.
Implements `\Lara\Comment\Contracts\IsCommentator\` interface on a model if you want to your model to be a commentator and add `\Lara\Comment\Commentator` trait for the implementation.
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lara\Comment\Commentator;
use Lara\Comment\Contracts\IsCommentator;

class User extends Model implements IsCommentator
{
    use Commentator;
}
```

### Creating Comments
To create a comment to a model you should first create the view with form.
```html
<form action="/posts/comments/store" methdo="POST">
    <textarea name="comment"></textarea>
    <button type="submit">Submit</button>
</form>
```
at least you provide the textarea tag HTML with `comment` name.
Then you may create a route to handle the request.
In your controller you can use `\Lara\Comment\CommentService` class and `store` method  to create a comment.
```php
$post = Post::find(1);

$user = Auth::user();

$comment = CommentService::for($post, $user)
            ->store();
```

### Updating comment
```php
$commentToUpdate = Comment::find(1);

$user = Auth::user();

$comment = CommentService::for($commentToUpdate, $user)
            ->update();
```

### Destory comment
```php
$post = Post::find(1);

$user = Auth::user();

$comment = CommentService::for($post, $user)
            ->destroy();
```

## Frontend
Before you start with the default frontend you must integrate your app with:
1. vue
2. tailwindcss

The command below will publish the nested comment frontend.
With nested comment you can reply a comment.
You can publish the frotend with:
```bash
php artisan vendor:publish --tag=lara-comment-vue
```
it will create views in resources/views/vendor/comment and vue component in resources/js/components/comment directory.
Don't forget to copy this code below to your app.js.
```javascript
Vue.component('edit-comment', require('./components/comment/EditComment.vue').default);
Vue.component('reply-comment', require('./components/comment/ReplyComment.vue').default);
```
After published you are be able to customise the css to fit with your view.
To use the frontend nested comment you may include it in your view and pass the commentable model to it.
```php
@include('vendor.comment.comment-list', ['commentable' => $post])
```
The code will render the comments with nested indentation belongs to `$post`.
Change the `indentation` in config file so that you can reply a comment in more deeper indentation.
You can imagine the indentation like:
```
- 0
    - 1
        - 2
```

## Routes

By default there are three routes for common task.

1. create
visit `route('comments.comments.store')` or `/comments/{comment}/comments` with POST method to create a comment on the comment.
2. update
visit `route('comments.update')` or `/comments/{comment}` with PUT method to update the comment.
3. destroy
visit `route('comments.destroy')` or `/comments/{comment}` with DELETE method to remove the comment from storage.

If you don't want to use the default route put `false` value in `route` inside setting file comment.php.

```php
'route' => false
```

## Validation Rule and Requested Data
`\Lara\Comment\Validation\DefaultValidator` is the default validator.
Validator is a class that responsible to get and validate the data from user.

If you want to chnage default behavior of validation or what kind of data you will store to storage you can extends `\Lara\Comment\Validation\Validator` abstract class then you must implement public function data() and public function rules().
From the class you can access commentator model and request object.

### data()
The data function is resposible to return what kind of data to store.
```php
public function data()
{
    return [
        'user_id' => $this->commentator->id,
        'comment' => $this->request->get('comment'),
    ];
}
```

### rules()
The rules function is responsible to define what kind of validation rules to run.
```php
public function rules()
{
    return [
        'user_id' => 'required',
        'comment' => 'required',
    ];
}
```

Don't forget to change the config validator value to your implementation.
```php
return [
    'validator' => \Lara\Comment\Validation\DefaultValidator::class,
]
```

### validateWithBag() method
If you have multiple form for comment then you want to display error message
You can use `validateWithBag()` method to validate with bag.
```php
$commentToUpdate = Comment::find(1);

$user = Auth::user();

$comment = CommentService::for($commentToUpdate, $user)
            ->validateWithBag()
            ->update();
```
so whene validation error occur you may the access the error bag
```php
{{ $errors->{$comment->id . 'PUT'}->first('comment') }}
```
you can access the name error bag with combination of the `commentable` id and the method `PUT` and `POST`

## Redirector
Redirector will redirect to the URL if validation fails.
The default redirector is \Lara\Comment\Redirect\RedirectBack.
This will redirect back with URL fragment #validation-comment-error.
If you wish to change this default behavior you could create your own redirect by extends \Lara\Comment\Redirect\Redirect abstract class and change the redirector value on configuration comment file to your own implementation.
```php
return [
    'redirector' => \Lara\Comment\Redirect\RedirectBack::class
];
```

## Policy
You can create your own policy to authorize the action.
To create policy class just run the laravel artisan command. For the complete guide see laravel documentation.
```bash
php artisan make:policy CommentPolicy
```
Don't forget to change `policy` class in config file.
```php
return [
    'policy' => \Lara\Comment\CommentPolicy::class
]
```