@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                            <a href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a>
                            posted:
                            {{ $thread->title }}
                            </span>
                            @can ('update', $thread)
                                <form method="POST" action="{{ route('threads.destroy', $thread) }}">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
                @foreach($replies as $reply)
                    <div id="reply-{{ $reply->id }}"class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <div class="flex">
                                    <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
                                    said {{ $reply->created_at->diffForHumans() }}
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('favorite.reply.store', $reply) }}">
                                        {{ csrf_field() }}
                                        <button type="submit"
                                                class="btn btn-outline-primary"{{ $reply->isFavorited() ? ' disabled': '' }}>
                                            {{ $reply->favorites_count }} {{ Str::plural('Favorite', $reply->favorites_count) }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $reply->body }}
                        </div>
                        @can('update', $reply)
                            <div class="card-footer text-muted">
                                <form method="POST" action="{{ route('reply.destroy', $reply) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                @endforeach
                {{ $replies->links() }}
                @if (auth()->check())
                    <form method="POST"
                          action="{{ route('reply.store', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" id="body" rows="3" name="body"
                                      placeholder="What's on your mind!"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to comment!</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }} by <a
                                href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a>,
                        and currently
                        has {{ $thread->replies_count }} {{ \Str::plural('comment', $thread->replies_count)}}.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
