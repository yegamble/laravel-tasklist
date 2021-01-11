<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'description' => 'required',
            ]);

            $task = new Task();
            $task->user_id = Auth::user()->id;
            $task->description = $request->description;
            $task->save();

            return response()->json(json_decode(json_encode($task)));

        } catch (Exception $e){
            $this->exceptions->report($e);
            return response()->json($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function update(Request $request)
    {

        try {

            $request->validate([
                'description' => 'required',
            ]);

            $task = Task::where('id',$request->id)->first();
            $task->description = $request->description;
            $task->save();

            return response()->json(json_decode(json_encode($task)));

        } catch (\Exception $e){
            $this->exceptions->report($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy( $id )
    {
        Task::destroy($id);

        return response()->json("deleted");
    }
}
