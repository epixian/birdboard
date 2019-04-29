<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects ()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects); 
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $john = $this->signIn();

        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->assignedProjects());

        $sally = factory(User::class)->create();
        $nick = factory(User::class)->create();

        $project = ProjectFactory::ownedBy($sally)->create();
        $project->invite($nick);

        $this->assertCount(1, $john->assignedProjects());

        $project->invite($john);

        $this->assertCount(2, $john->assignedProjects());
    }
}
