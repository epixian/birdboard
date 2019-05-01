<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_list_or_view_or_edit_or_create_projects()
    {
        $project = ProjectFactory::create();

        // guests cannot list
        $this->get('/projects')->assertRedirect('login');

        // guests cannot view
        $this->get($project->path())->assertRedirect('login');

        // guests cannot edit
        $this->get($project->path() . '/edit')->assertRedirect('login');

        // guests cannot create
        $this->get('/projects/create')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = factory(Project::class)->raw(['owner_id' => auth()->id()]);

        $this->followingRedirects()
            ->post('/projects', $attributes)
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function tasks_can_be_added_at_project_create()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw();

        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body' => 'Task 2']
        ];

        $this->post('/projects', $attributes);

        $this->assertCount(2, Project::first()->tasks);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $user = $this->signIn();
 
        $project = ProjectFactory::create();

        $project->invite($user);

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('login');

        $user = $this->signIn();

        $this->delete($project->path())
            ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)
            ->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function a_user_can_update_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
                'title' => 'changed',
                'notes' => 'changed', 
                'description' => 'changed'
            ])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);

    }

    /** @test */
    public function a_user_can_update_their_projects_general_notes()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = [
                'notes' => 'changed'
            ]);

        $this->assertDatabaseHas('projects', $attributes);

    }

    /** @test */
    public function an_authenticated_user_cannot_view_or_update_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)
             ->assertSessionHasErrors('description');
    }

}
