<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }//end __construct()


    public function index()
    {
        $todos = $this->user->todos()->get(['id', 'title', 'body', 'completed', 'created_by']);
        return response()->json($todos->toArray());

    }//end index()


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'     => 'required|string',
                'body'      => 'required|string',
                'completed' => 'required|boolean',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $todo            = new Todo();
        $todo->title     = $request->title;
        $todo->body      = $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)) {
            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the todo could not be saved.',
                ]
            );
        }

    }//end store()


    public function show(Todo $todo)
    {
        return $todo;

    }//end show()


    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'     => 'required|string',
                'body'      => 'required|string',
                'completed' => 'required|boolean',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $todo->title     = $request->title;
        $todo->body      = $request->body;
        $todo->completed = $request->completed;

        if ($this->user->todos()->save($todo)) {
            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the todo could not be updated.',
                ]
            );
        }

    }//end update()


    public function destroy(Todo $todo)
    {
        if ($todo->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'todo'   => $todo,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the todo could not be deleted.',
                ]
            );
        }

    }//end destroy()


    protected function guard()
    {
        return Auth::guard();

    }//end guard()


}//end class
