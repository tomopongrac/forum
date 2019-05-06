@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Forum Threads</h1>


                @foreach($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>
                                <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
                            </div>
                        </div>

                        <div class="card-body">{{ $thread->body }}</div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
