@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Forum Threads</h1>
                @include('threads._list')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection
