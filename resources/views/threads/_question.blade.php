<div class="card mb-3" v-if="!editing">
    <div class="card-header">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-2">
            <span class="flex">
                            <a href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a>
                            posted:
                            <span v-text="form.title"></span>
                            </span>
        </div>
    </div>
    <div class="card-body" v-text="form.body"></div>
    <div class="card-footer" v-if="authorize('updateThread', thread)">
        <button class="btn btn-primary btn-sm" @click="editing = true">Edit</button>
    </div>
</div>

<div class="card mb-3" v-else>
    <div class="card-header">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary btn-sm" @click="update">Update</button>
        <button class="btn btn-link btn-sm" @click="cancel">Cancel</button>
        @can ('update', $thread)
            <form method="POST" action="{{ route('threads.destroy', $thread) }}" class="ml-auto">
                @csrf
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
        @endcan
    </div>
</div>