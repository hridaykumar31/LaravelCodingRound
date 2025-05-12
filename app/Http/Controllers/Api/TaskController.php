<?php

namespace App\Http\Controllers\Api;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    //Store the task data into the database
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
        ]);
        $task = Task::create([
           'title' => $request->title,
           'is_completed' => false
        ]);
        return response()->json([
        'message' => 'Task Created Successfully',
        'task' => $task,
      ], 201);
    }

    //Update the task based on task id
    public function update(Request $request, $id) {

        //return response()->json([$request->all()], 200);
        //Fetching the task specific task based on given id or whichone we need toi be update
        $task = Task::findorFail($id);

        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        //After fetching the specific task update this task
        $task->update([
            'is_completed' => $request->is_completed,
        ]);
        return response()->json($task);
    }
    public function pending() {
        //return response()->json("okkk");

        //Fetching all the where is_completed is false
        $allPendingTask = Task::where('is_completed', false)->get();

        return response()->json([
            'message' => "All pending task",
            'task' => $allPendingTask,
        ]);
    }
}
