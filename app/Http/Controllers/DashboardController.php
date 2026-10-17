<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $tasks = $request->user()->tasks();

        $total = (clone $tasks)->count();
        $completed = (clone $tasks)->where('status', 'completed')->count();

        return view('dashboard', [
            'total' => $total,
            'todo' => (clone $tasks)->where('status', 'todo')->count(),
            'inProgress' => (clone $tasks)->where('status', 'in_progress')->count(),
            'completed' => $completed,
            'overdue' => (clone $tasks)
                ->where('status', '!=', 'completed')
                ->whereDate('due_date', '<', today())
                ->count(),
            'completionRate' => $total === 0 ? 0 : (int) round(($completed / $total) * 100),
            'recentTasks' => (clone $tasks)
                ->latest()
                ->limit(6)
                ->get(),
            'taskStatuses' => Task::STATUSES,
            'taskPriorities' => Task::PRIORITIES,
        ]);
    }
}
