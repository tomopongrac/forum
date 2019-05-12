@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-4 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>
        @foreach($activities as $date => $dateActivities)
            <h2>{{ $date }}</h2>
            @foreach($dateActivities as $activity)
                @includeIf("profiles.activities.{$activity->type}")
            @endforeach
        @endforeach
    </div>
@endsection
