<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_register_login_and_logout(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Camille Martin',
            'email' => 'camille@example.com',
            'password' => 'a-secure-password',
            'password_confirmation' => 'a-secure-password',
        ]);

        $user = User::where('email', 'camille@example.com')->firstOrFail();

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Hash::check('a-secure-password', $user->password));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login.store'), [
            'email' => 'camille@example.com',
            'password' => 'a-secure-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_guests_cannot_access_the_task_area(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('tasks.index'))->assertRedirect(route('login'));
        $this->post(route('tasks.store'))->assertRedirect(route('login'));
    }

    public function test_users_can_create_read_update_and_delete_their_tasks(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('tasks.store'), [
            'title' => 'Préparer la présentation',
            'description' => 'Réunir les éléments clés.',
            'priority' => 'high',
            'status' => 'todo',
            'due_date' => '2026-11-01',
        ])->assertRedirect(route('tasks.index'));

        $task = Task::where('title', 'Préparer la présentation')->firstOrFail();
        $this->assertSame($user->id, $task->user_id);

        $this->get(route('tasks.create'))->assertOk();
        $this->get(route('tasks.edit', $task))->assertOk();

        $this->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSee('Réunir les éléments clés.');

        $this->put(route('tasks.update', $task), [
            'title' => 'Finaliser la présentation',
            'description' => null,
            'priority' => 'medium',
            'status' => 'in_progress',
            'due_date' => null,
        ])->assertRedirect(route('tasks.show', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Finaliser la présentation',
            'status' => 'in_progress',
            'due_date' => null,
        ]);

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_validation_errors_are_displayed_in_french(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('tasks.store'), [])
            ->assertSessionHasErrors([
                'title' => 'Le champ titre est obligatoire.',
                'priority' => 'Le champ priorité est obligatoire.',
                'status' => 'Le champ statut est obligatoire.',
            ]);
    }

    public function test_users_cannot_view_or_modify_another_users_task(): void
    {
        $owner = User::factory()->create();
        $task = $owner->tasks()->create([
            'title' => 'Tâche privée',
            'priority' => 'medium',
            'status' => 'todo',
        ]);

        $this->actingAs(User::factory()->create());

        $this->get(route('tasks.show', $task))->assertForbidden();
        $this->put(route('tasks.update', $task), [
            'title' => 'Accès non autorisé',
            'priority' => 'high',
            'status' => 'completed',
        ])->assertForbidden();
        $this->delete(route('tasks.destroy', $task))->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Tâche privée']);
    }

    public function test_task_lists_and_dashboard_only_show_the_authenticated_users_tasks(): void
    {
        $user = User::factory()->create();
        $user->tasks()->create([
            'title' => 'Ma tâche personnelle',
            'priority' => 'medium',
            'status' => 'todo',
        ]);

        User::factory()->create()->tasks()->create([
            'title' => 'Tâche privée d’un autre compte',
            'priority' => 'high',
            'status' => 'todo',
        ]);

        $this->actingAs($user);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee('Ma tâche personnelle')
            ->assertDontSee('Tâche privée d’un autre compte');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Ma tâche personnelle')
            ->assertDontSee('Tâche privée d’un autre compte');
    }

    public function test_dashboard_statistics_and_combined_task_filters_use_real_user_data(): void
    {
        $user = User::factory()->create();
        $user->tasks()->createMany([
            [
                'title' => 'Rapport urgent',
                'priority' => 'high',
                'status' => 'todo',
                'due_date' => today()->subDay()->toDateString(),
            ],
            [
                'title' => 'Réunion hebdomadaire',
                'priority' => 'high',
                'status' => 'in_progress',
                'due_date' => today()->toDateString(),
            ],
            [
                'title' => 'Plan annuel',
                'priority' => 'low',
                'status' => 'completed',
                'due_date' => today()->subDays(3)->toDateString(),
            ],
        ]);

        $this->actingAs($user);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('3', false)
            ->assertSee('33%');

        $this->get(route('tasks.index', [
            'search' => 'rapport',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => today()->subDay()->toDateString(),
        ]))
            ->assertOk()
            ->assertSee('Rapport urgent')
            ->assertDontSee('Réunion hebdomadaire')
            ->assertDontSee('Plan annuel');

        $this->get(route('tasks.index', ['overdue' => 1]))
            ->assertOk()
            ->assertSee('Rapport urgent')
            ->assertDontSee('Plan annuel');
    }
}
