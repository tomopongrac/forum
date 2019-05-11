@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
        <a href="{{ route('profiles.show', $activity->subject->creator) }}">{{ $activity->subject->creator->name }}</a> published a thread:
        <a href="{{ route('threads.show', ['channel' => $activity->subject->channel->slug, 'thread' => $activity->subject]) }}">
            {{ $activity->subject->title }}
        </a>
            </span>
        <span>{{ $activity->subject->created_at->diffForHumans() }}</span>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
