<div class="card mb-3">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <a href="{{ route('profiles.show', $activity->subject->creator) }}">{{ $activity->subject->creator->name }}</a> published a thread:
                <a href="{{ route('threads.show', ['channel' => $activity->subject->channel->slug, 'thread' => $activity->subject]) }}">
                    {{ $activity->subject->title }}
                </a>
            </span>
            <span>{{ $activity->subject->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>