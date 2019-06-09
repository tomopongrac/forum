@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <img src="{{ asset('storage/'.$thread->creator->avatar()) }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
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

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>

                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by <a
                                    href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a>,
                            and currently
                            has <span v-text="repliesCount"></span> {{ \Str::plural('comment', $thread->replies_count)}}.
                            </p>
                            <p>
                                <subscribe-button :active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
