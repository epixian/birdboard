<div class="card p-4 mt-3 text-xs list-reset">
    @foreach ($project->activity as $activity)
        <li class="{{ $loop->last ? '' : 'mb-1' }}">
            @include ("projects.activity.{$activity->description}")<br>
            <span class="text-grey">{{ $activity->created_at->diffForHumans() }}</span>
        </li>
    @endforeach
</div>