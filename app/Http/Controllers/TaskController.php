<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogginRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return respuesta(Task::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    public function form(LogginRequest $request){
        try {
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
            return respuesta('Paso!');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class().'::'. __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }
}
