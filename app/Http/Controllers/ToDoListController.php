<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use Illuminate\Http\Request;

class ToDoListController extends Controller {

    public function index() {
        return view('todolist');
    }

    public function getTasks(Request $request) {
        $query = ToDoList::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('task', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->order ?? 'asc');
        }

        $tasks = $query->get();

        return response()->json($tasks);
    }

    public function store(Request $request) {

        $task = ToDoList::create($request->all());
        return response()->json($task);
    }

    public function update(Request $request, ToDoList $task) {
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(ToDoList $task) {
        $task->delete();
        return response()->json(['success' => true]);
    }
}
