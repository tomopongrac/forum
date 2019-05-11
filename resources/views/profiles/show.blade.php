@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-4 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>
        @foreach($activities as $activity)
            @include("profiles.activities.{$activity->type}")
        @endforeach
    </div>
@endsection
