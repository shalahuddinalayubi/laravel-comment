@foreach ($commentable->comments as $comment)
    <div class="flex items-start">
        <div class="flex items-center justify-center text-center w-10 h-10 rounded-full border-2 mr-3">
            {{ substr($comment->user->name, 0, 1) }}
        </div>

        <div class="flex flex-col w-full border-b-2 pb-2">
            <div class="text-xs">
                <span>{{ $comment->user->name }}</span>
                <span>{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
            </div>

            <span class="my-1">{{ $comment->comment }}</span>

            @can('update', $comment)    
                <edit-comment
                    comment="{{ $comment->comment }}"
                    action={{ route('comments.update', ['comment' => $comment]) }}
                    auth-user-name={{ Auth::user()->name }}
                    csrf={{ csrf_token() }}
                    error-message="{{ $errors->{$comment->id . 'PUT'}->first('comment') }}"
                >
                </edit-comment>
            @endcan

            @auth    
                <reply-comment
                    action={{ route('comments.comments.store', ['comment' => $comment]) }}
                    auth-user-name={{ Auth::user()->name }}
                    csrf={{ csrf_token() }}
                    error-message="{{ $errors->{$comment->id . 'POST'}->first('comment') }}"
                >
                </reply-comment>
            @endauth

            @can('delete', $comment)    
                <form action="{{ route('comments.destroy', ['comment' => $comment]) }}" method="POST" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs mb-1 hover:underline">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    @include('comment::comment-list-child', ['comments' => $comment->comments])
@endforeach
