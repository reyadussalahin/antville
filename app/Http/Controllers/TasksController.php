<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Task;


class TasksController extends Controller
{
    /**
     * Deafult pull limit for pull functionality
     *
     * @var integer
     */
    private $defaultPullLimit = 5;

    /**
     * Deafult pull type for pull functionality
     *
     * @var string
     */
    private $defaultPullType = "all";

    /**
     * Deafult pull id for pull functionality
     *
     * @var integer
     */
    private $defaultPullId = 0;

    /**
     * It helps to add milestone for which are created automatically for create, extend, edit, reopen etc.
     *
     * @param \Illuminate\Http\Request  $request,  int  $taskId,  string  $milestone
     *
     * @return boolean
     */

    private function addMilestone($request, $taskId, $milestone) {
        $task = $request->user()->tasks()->find($taskId);
        if($task === null) {
            return false;
        }
        $m = $task->milestones()->create([
            "description" => $milestone
        ]);
        $m->created_at = date("Y-m-d H:i:s", strtotime("+6 hours"));
        $m->save();
        return true;
    }

    /**
     * Create a new TasksController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("tasks.index");
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("tasks.create");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input("title");
        $description = $request->input("description");
        $expectedWithinTime = $request->input("expected-within-time");
        $expectedWithinDate = $request->input("expected-within-date");

        $title = trim($title);
        $description = trim($description);
        $expectedWithinTime = trim($expectedWithinTime);
        $expectedWithinDate = trim($expectedWithinDate);

        $expectedWithinDatetime = $expectedWithinDate . " " . $expectedWithinTime .":00";
        $now = date("Y-m-d H:i:s", strtotime("+6 hours"));

        $validator = Validator::make([
                "title" => $title,
                "description" => $description,
                // "expected-within-time" => $expectedWithinTime,
                "expected-within-date" => $expectedWithinDate,
                "expected-within-time" => $expectedWithinDatetime // note: we've assigned time to datetime
                // just to represent error nicely by taking advantage blade's templating
            ], [
                "title" => "required",
                "description" => "required",
                // "expected-within-time" => "required",
                "expected-within-date" => "required",
                "expected-within-time" => "required|after:" . $now
            ]
        );

        if($validator->fails()) {
            return redirect("/tasks/create")
                ->withErrors($validator)
                ->withInput();
        }

        $task = $request->user()->tasks()->create([
            "title" => $title,
            "description" => $description,
            "expected_within" => $expectedWithinDatetime,
        ]);

        $task->created_at = date("Y-m-d H:i:s", strtotime("+6 hours"));
        $task->save();

        $createMilestoneMsg = "Great, task has been created!";
        $this->addMilestone($request, $task->id, $createMilestoneMsg);

        return redirect("/tasks/" . $task->id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $task = $request->user()->tasks()->find($id);

        if($task === null) {
            return redirect("/");
        }

        if($task->status !== "finished") {
            $now = strtotime("+6 hours");
            $expected_within = strtotime($task->expected_within);
            if($expected_within <= $now) {
                $task->status = "unfinished";
            }
        }

        return view("tasks.show")
            ->with("task", $task);
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
     * Pulls tasks from tasks table based on type and id with a given limit
     *
     * @return array
     */

    private function pullHelper($request, $type, $id, $limit)
    {
        $builder = $request->user()
            ->tasks();

        if($type === "todo") {
            $now = date("Y-m-d H:i:s", strtotime("+6 hours"));
            $builder = $builder->where("status", "todo")
                ->where("expected_within", ">", $now);
        } else if($type === "unfinished") {
            $now = date("Y-m-d H:i:s", strtotime("+6 hours"));
            // note: we've used "todo" as "status" for
            // unfinished tasks
            // cause, unfinished tasks are also todo, they're just not finished within
            //        expected time. So, note, it well.
            $builder = $builder->where("status", "todo")
                ->where("expected_within", "<", $now);
        } else {
            $builder = $builder->where("status", "finished");
        }
        return $builder->where("id", ">", $id)
            ->take($limit)
            ->get();
    }

    /**
     * Pulls todo, finished and unfinished or all of them items from storage. Expects type, id, limit as input with get request. If not provided, it assigns default p
     *
     * @return string(json encoded)
     */
    public function pull(Request $request)
    {
        header("Content-Type: application/json");

        // first declaring all default value
        $type = $this->defaultPullType;
        $id = $this->defaultPullId;
        $limit = $this->defaultPullLimit;

        // now, override them, if any input is passed through
        if($request->has("type")) {
            $type = $request->input("type");
        }
        if($request->has("id")) {
            $id = $request->input("id");
            $id = (int)$id;
        }
        if($request->has("limit")) {
            $limit = $request->input("limit");
            $limit = (int)$limit;
        }

        $types = [];
        if($type === "all") {
            $types = ["todo", "finished", "unfinished"];
        } else {
            $types = [$type];
        }
        $response = [];
        foreach ($types as $type) {
            $response[$type] = $this->pullHelper($request, $type, $id, $limit);
        }
        
        return json_encode($response);
    }


    /**
     * Mark a task as finished by changing "status" field and writing "finished_at"
     *
     * @return string(json encoded)
     */
    public function finished($id, Request $request)
    {
        $response = [];
        $task = $request->user()->tasks()->find($id);
        if($task !== null) {
            // updating task milestones
            $finishedMilestoneMsg = "Amazing! I should pat my back. I just finished the task!";
            $this->addMilestone($request, $task->id, $finishedMilestoneMsg);

            // now, take care of finish
            $task->finished_at = date("Y-m-d H:i:s", strtotime("+6 hours"));
            $task->status = "finished";
            $task->save();

            // no need
            // $response = [
            //     "status" => "success"
            // ];
        } else {
            // no need
            // $response = [
            //     "status" => "failed",
            //     "message" => "task not found"
            // ];


            // task not recognized
            // so, redirect to "/"
            return redirect("/");
        }

        return redirect(url()->previous());
    }


    /**
     * Mark a task as finished by changing "status" field and writing "finished_at"
     *
     * @return string(json encoded)
     */
    public function extend($id, Request $request)
    {
        $time = $request->input("expected-within-time");
        $date = $request->input("expected-within-date");

        $time = trim($time);
        $date = trim($date);

        $datetime = $date . " " . $time .":00";
        $now = date("Y-m-d H:i:s", strtotime("+6 hours"));

        $validator = Validator::make([
                // "expected-within-time" => $time,
                "expected-within-date" => $date,
                "expected-within-time" => $datetime // note: we've assigned time to datetime
                // just to represent error nicely by taking advantage blade's templating
            ], [
                // "expected-within-time" => "required",
                "expected-within-date" => "required",
                "expected-within-time" => "required|after:" . $now
            ]
        );

        if($validator->fails()) {
            $showExtendedUtility = "show";
            return redirect(url()->previous())
                ->withErrors($validator)
                ->withInput()
                ->with("showExtendUtility", $showExtendedUtility);
        }

        $task = $request->user()->tasks()->find($id);
        if($task !== null) {
            // take care of milestones
            $extendMsg = "I am updating the expected todo time.";
            // check if task is already finished, then
            // it would be reopened
            if($task->status === "finished") {
                $extendMsg = "I am reopening the task! Let's work hard and do something more!";
            }
            $this->addMilestone($request, $task->id, $extendMsg);

            // now, extend time
            $task->status = "todo";
            $task->expected_within = $datetime;
            $task->save();
        } else {
            // task not recognized
            return redirect("/");
        }

        return redirect(url()->previous());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $title = $request->input("title");
        $description = $request->input("description");

        $title = trim($title);
        $description = trim($description);

        $validator = Validator::make([
                "title" => $title,
                "description" => $description,
            ], [
                "title" => "required",
                "description" => "required",
            ]
        );

        if($validator->fails()) {
            $showEditUtility = true;
            return redirect(url()->previous())
                ->withErrors($validator)
                ->withInput()
                ->with("showEditUtility", $showEditUtility);
        }

        $task = $request->user()->tasks()->find($id);
        if($task !== null) {
            // let's take care of milestones
            $editMsg = "I have edited the task content.";
            $this->addMilestone($request, $task->id, $editMsg);

            $task->title = $title;
            $task->description = $description;
            $task->save();
        } else {
            // task not recognized
            return redirect("/");
        }

        return redirect(url()->previous());
    }
}
