<div class="card flex flex-col" style="height: 200px;">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-light pl-4 mb-2">
        <a href="{{ $project->path() }}" class="text-default no-underline">{{ $project->title }}</a>
    </h3>

    <div class="text-default mb-4 flex-1">{{ str_limit($project->description, 90) }}</div>

    @can ('manage', $project)
        <footer>
            <form action="{{ $project->path() }}" method="POST" class="text-right">
                @csrf
                @method('DELETE')
                <button class="text-xs" type="submit">Delete</button>
            </form>
        </footer>
    @endcan
</div>