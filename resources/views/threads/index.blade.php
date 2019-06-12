@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Forum Threads</h1>
                @include('threads._list')
                {{ $threads->links() }}
            </div>
            <div class="col-md-4">
                @if (count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            @foreach($trending as $thread)
                                <li>
                                    <a href="{{ $thread->path }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
