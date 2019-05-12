@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
        <a href="{{ route('profiles.show', $activity->subject->user) }}">{{ $activity->subject->user->name }}</a> favorited a
        <a href="{{ route('threads.show', ['channel' => $activity->subject->favorited->thread->channel->slug, 'thread' => $activity->subject->favorited->thread])."#reply-".$activity->subject->favorited->id }}">
            reply
        </a>
            </span>
        <span>{{ $activity->subject->created_at->diffForHumans() }}</span>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
