@extends ('layouts.app')

@section ('content')
    <header class="flex items-center mb-6">
        <div class="flex justify-between w-full items-end">
            <p class="text-default text-sm font-normal">
                <a href="/projects" class="text-default text-sm font-normal no-underline">My Projects</a> / {{ $project->title }}
            </p>

            <div class="flex items-center">
                <img 
                    src="{{ gravatar_url($project->owner->email) }}" 
                    alt="{{ $project->owner->name }}" 
                    class="rounded-full w-8 mr-2">
                @foreach ($project->members as $member)
                    <img 
                        src="{{ gravatar_url($member->email) }}" 
                        alt="{{ $member->name }}" 
                        class="rounded-full w-8 mr-2">
                @endforeach    
                <a href="{{ $project->path() . '/edit' }}" class="button ml-4">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">

            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-default font-normal mb-3">Tasks</h2>

                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form method="POST" action="{{ $task->path() }}">
                                @method('PATCH')
                                @csrf

                                <div class="flex items-center">
                                    <input name="body" value="{{ $task->body }}" class="w-full bg-card text-default {{ $task->completed ? 'text-default italic' : '' }}">
                                    <input type="checkbox" name="completed" onChange="this.form.submit();" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form method="POST" action="{{ $project->path() . '/tasks' }}">
                            @csrf

                            <input name="body" class="w-full bg-card text-default" placeholder="Add a new task..."> 
                        </form>
                        
                    </div>
                </div>
                
                <div>
                    <h2 class="text-lg text-default font-normal mb-3">General Notes</h2>

                    {{-- general notes --}}
                    <form method="POST" action="{{ $project->path() }}">
                        @method('PATCH')
                        @csrf

                        <textarea 
                            name="notes"
                            class="card w-full mb-4" 
                            style="min-height: 200px;"
                            placeholder="Anything you want to make a note of?"
                        >{{ $project->notes }}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>

                    @include ('errors')

                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include ('projects.card') 

                @include ('projects.activity.card')

                @can ('manage', $project)
                    @include ('projects.invite')
                @endcan
            </div>    

        </div>


    </main>

@endsection 