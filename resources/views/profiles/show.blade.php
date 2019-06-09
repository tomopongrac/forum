@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-4 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
            @can('update', $profileUser)
                <form method="POST" action="{{ route('user.profile.avatar', $profileUser) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="custom-file mb-2">
                        <input type="file" class="custom-file-input" id="customFile" name="avatar">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Avatar</button>
                </form>
                <img src="{{ asset('storage/'.$profileUser->avatar_path) }}" alt="" width="50" height="50">
            @endcan
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
