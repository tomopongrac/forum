<div class="card mb-3">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <a href="{{ route('profiles.show', $activity->subject->owner) }}">{{ $activity->subject->owner->name }}</a> reply to thread:
                <a href="{{ route('threads.show', ['channel' => $activity->subject->thread->channel->slug, 'thread' => $activity->subject->thread]) }}">
                    {{ $activity->subject->thread->title }}
                </a>
            </span>
            <span>{{ $activity->subject->thread->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>