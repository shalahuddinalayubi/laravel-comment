## Instalation

### Config
You can publish the config file with:
```bash
php artisan vendor:publish --tag=lara-comment-config
```

### Migration
You can publish the migration with:
```bash
php artisan vendor:publish --tag=lara-comment-migration
```

### Frontend
The command below will publish the nested comment frontend.
With nested comment you can reply a comment.
You can publish the frotend with:
```bash
php artisan vendor:publish --tag=lara-comment-vue
```
The default frontend is Vue with tailwindcss so you must integrate your Laravel app with Vue and tailwindcss.
After published you are be able to customise the css to fit with your view.
To use the frontend nested comment you may include it in your view and pass the commentable model to it.
```php
@include('comment-list', ['commentable' => $post])
```
The code will render the comments with nested indentation belongs to `$post`.
Change the `indentation` in config file so that you can reply a comment in more deeper indentation.
You can imagine the indentation like:
```
- 0
    - 1
        - 2
```

### Routes

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

## Usage

### Commentable
If you want a model can be commented you can add `\Lara\Comment\Commentable` trait and implements `\Lara\Comment\Contracts\IsCommentable` interface.
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
Add `\Lara\Comment\Commentator` trait and implements `\Lara\Comment\Contracts\IsCommentator\` interface to the model if you want to your model to be a commentator.
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
To create a comment to a model you should first create the view.
```html
<textarea name="comment"></textarea>
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
To update a comment, you can user `\Lara\Comment\CommentService` class
```php
$commentToUpdate = Comment::find(1);

$user = Auth::user();

$comment = CommentService::for($commentToUpdate, $user)
            ->update();
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


## Validation
If you want to chnage default behavior of validation you can extends \Lara\Comment\Validator abstract class then you must implement public function data() and public function rules(). From the class you can access commentator model and request object.
\Lara\Comment\Validation\DefaultValidator is the default validation.
```php
public function data()
{
    return [
        'user_id' => $this->commentator->id,
        'comment' => $this->request->get('comment'),
    ];
}

public function rules()
{
    return [
        'user_id' => 'required',
        'comment' => 'required',
    ];
}
```
Don't forget to change the config validator value.

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