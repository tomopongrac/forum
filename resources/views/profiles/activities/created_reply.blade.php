@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
                <a href="{{ route('profiles.show', $activity->subject->owner) }}">{{ $activity->subject->owner->name }}</a> reply to thread:
                <a href="{{ route('threads.show', ['channel' => $activity->subject->thread->channel->slug, 'thread' => $activity->subject->thread]) }}">
                    {{ $activity->subject->thread->title }}
                </a>
            </span>
        <span>{{ $activity->subject->thread->created_at->diffForHumans() }}</span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
