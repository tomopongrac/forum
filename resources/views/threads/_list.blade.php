@foreach($threads as $thread)
    <div class="card mb-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">
                            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>Posted By: <a href="{{ route('profiles.show', ['name' => $thread->creator->name]) }}">{{ $thread->creator->name }}</a> </h5>
                </div>
                <a href="{{ route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
            </div>
        </div>

        <div class="card-body">{{ $thread->body }}</div>
        <div class="card-footer">
            {{ $thread->visits() }} Visits
        </div>
    </div>
@endforeach