@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-4 border-bottom">
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
        </div>
        @forelse($activities as $date => $dateActivities)
            <h2>{{ $date }}</h2>
            @foreach($dateActivities as $activity)
                @includeIf("profiles.activities.{$activity->type}")
            @endforeach
        @empty
            <p>There is no activity for this user yet.</p>
        @endforelse
    </div>
@endsection
