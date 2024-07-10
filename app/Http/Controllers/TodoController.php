<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoApp;
use Illuminate\Support\Facades\Config;

class TodoController extends Controller
{
    /***************************************************
        View Controller
    ***************************************************/
    public function index()
    {
        $fetchLimit = Config::get('todo.fetch_limit');
        $data = TodoApp::latest()->take($fetchLimit)->get();
        return view('index', ['data' => $data]);
    }

    /***************************************************
        Show All Data Controller
    ***************************************************/
    public function ShowAll()
    {
        $data = TodoApp::paginate(10);
        return view('list', ['data' => $data]);
    }

    /***************************************************
        Store Task Controller
    ***************************************************/
    public function store(Request $request)
    {
        // Validate request data ensure unique task
        $validatedData = $request->validate([
            'task' => 'required|string|unique:todo_apps,description',
        ]);

        $data = new TodoApp();
        $data->description = $request->task;
        $data->completed = 0;
        $data->save();

        return response()->json(['status' => true, 'data' => $data, 'success' => 'Task added successfully'], 200);
    }

    /***************************************************
        Delete Controller
    ***************************************************/
    public function destroy($id)
    {
        $task = TodoApp::findOrFail($id);
        $task->delete();

        return response()->json([
            'status' => true,
            'success' => 'Task deleted successfully.'
        ]);
    }

    /***************************************************
        Mark as Completed Controller
    ***************************************************/
    public function complete($taskId)
    {
        $task = TodoApp::find($taskId);

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        $task->completed = true;
        $task->save();

        return response()->json([
            'status' => true,
            'message' => 'Task marked as completed successfully.',
        ]);
    }
}
