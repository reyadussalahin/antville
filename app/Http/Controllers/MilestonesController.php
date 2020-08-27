<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Milestone;

class MilestonesController extends Controller
{
    /**
     * Deafult pull limit for pull functionality
     *
     * @var integer
     */
    private $defaultPullLimit = 10;

    /**
     * Deafult pull id for pull functionality
     *
     * @var integer
     */
    private $defaultPullId = 0;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($taskId, Request $request)
    {
        $milestone = $request->input("milestone");
        $milestone = trim($milestone);

        $validator = Validator::make([
            "milestone" => $milestone
        ], [
            "milestone" => "required"
        ]);

        if($validator->fails()) {
            return redirect(url()->previous())
                ->withValidationErrors($validator)
                ->withInput();
        }

        $task = $request->user()->tasks()->find($taskId);
        if($task === null) {
            return redirect("/");
        }

        $m = $task->milestones()->create([
            "description" => $milestone,
        ]);
        $m->created_at = date("Y-m-d H:i:s", strtotime("+6 hours"));
        $m->save();

        return redirect(url()->previous());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Pulls milestones for task with provided taskId. Also, expects $id and $limit as input, if not given default is used
     *
     * @return string(json encoded)
     */
    public function pull($taskId, Request $request)
    {
        $limit = $this->defaultPullLimit;
        $id = $this->defaultPullId;

        // now, override them, if any input is passed through
        if($request->has("id")) {
            $id = $request->input("id");
            $id = (int)$id;
        }
        if($request->has("limit")) {
            $limit = $request->input("limit");
            $limit = (int)$limit;
        }

        $task = $request->user()->tasks()->find($taskId);
        
        $response = [];
        if($task === null) {
            $response = [
                "status" => "error",
                "message" => "provided task id not found"
            ];
        } else {
            $builder = $task->milestones();
            if($id !== 0) {
                $builder = $builder->where("id", "<", $id);
            }
            
            $milestones = $builder->orderBy("created_at", "desc")
                ->take($limit)
                ->get();

            $response = [
                "status" => "success",
                "milestones" => $milestones
            ];
        }
        return json_encode($response);
    }
}
