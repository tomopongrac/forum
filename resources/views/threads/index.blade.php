@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum Threads</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($threads as $thread)
                            <article>
                                <div class="level">
                                    <h4 class="flex">
                                        <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">
                                            {{ $thread->title }}
                                        </a>
                                    </h4>
                                    <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
                                </div>
                                <div class="body">{{ $thread->body }}</div>
                            </article>

                            <hr/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
