<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Task::class);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(array_keys(Task::STATUSES))],
            'priority' => ['nullable', Rule::in(array_keys(Task::PRIORITIES))],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'overdue' => ['nullable', Rule::in(['1'])],
        ]);

        $query = $request->user()->tasks()
            ->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where('title', 'like', '%'.$search.'%'))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['priority'] ?? null, fn (Builder $query, string $priority) => $query->where('priority', $priority))
            ->when($filters['due_date'] ?? null, fn (Builder $query, string $dueDate) => $query->whereDate('due_date', $dueDate))
            ->when(isset($filters['overdue']), fn (Builder $query) => $query
                ->where('status', '!=', 'completed')
                ->whereDate('due_date', '<', today()));

        return view('tasks.index', [
            'tasks' => $query->orderByRaw('due_date IS NULL')->orderBy('due_date')->latest()->paginate(10)->withQueryString(),
            'filters' => $filters,
            'filtersApplied' => collect($filters)->contains(fn ($value) => $value !== null && $value !== ''),
            'taskStatuses' => Task::STATUSES,
            'taskPriorities' => Task::PRIORITIES,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Task::class);

        return view('tasks.create', [
            'taskStatuses' => Task::STATUSES,
            'taskPriorities' => Task::PRIORITIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        $request->user()->tasks()->create($this->validatedTask($request));

        return redirect()->route('tasks.index')->with('success', 'La tâche a été créée.');
    }

    public function show(Task $task): View
    {
        Gate::authorize('view', $task);

        return view('tasks.show', [
            'task' => $task,
            'taskStatuses' => Task::STATUSES,
            'taskPriorities' => Task::PRIORITIES,
        ]);
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);

        return view('tasks.edit', [
            'task' => $task,
            'taskStatuses' => Task::STATUSES,
            'taskPriorities' => Task::PRIORITIES,
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);
        $task->update($this->validatedTask($request));

        return redirect()->route('tasks.show', $task)->with('success', 'La tâche a été mise à jour.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'La tâche a été supprimée.');
    }

    /**
     * @return array{title: string, description: ?string, priority: string, status: string, due_date: ?string}
     */
    private function validatedTask(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'priority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'status' => ['required', Rule::in(array_keys(Task::STATUSES))],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
        ]);
    }
}
