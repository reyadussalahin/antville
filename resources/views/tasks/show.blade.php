@extends("layouts.app")

@section("content")

<div class="container">
    <div class="row">
        <div class="col-md-7">
            <div class="task-utility-wrapper px-md-2">
                <div class="task-content-utility border border-grey rounded p-2">
                    <div class="task-content border-bottom border-grey mb-2">
                        <div class="task-top py-2 d-flex align-items-center" style="font-size: 13px;">
                            <div class="task-created-at-prefix pr-1">
                                Expected: 
                            </div>
                            
                            <div class="task-created-at" style="font-size: 13px;">
                                <!-- 12:30pm, 17th August, 2020 -->
                                {{ $task->expected_within }}
                            </div>

                            <div class="task-status-badge ml-auto">
                                @if($task->status === "todo")
                                    <div class="task-status-badge-finished border border-warning btn-sm btn-warning text-dark font-weight-bold">
                                        Todo
                                    </div>
                                @elseif($task->status === "finished")
                                    <div class="task-status-badge-finished border border-success btn-sm btn-success text-white font-weight-bold">
                                        Finished
                                    </div>
                                @else
                                    <div class="task-status-badge-unfinished border border-danger btn-sm btn-danger text-white font-weight-bold">
                                        Unfinished
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="task-body pt-2 pb-3">
                            <div class="task-title font-weight-bold">
                                <!-- This is the title -->
                                {{ $task->title }}
                            </div>
                            <div class="task-description mt-2 pb-1-3" style="white-space: pre-wrap;">{{ $task->description }}</div>
                        </div>
                    </div>
                    <div class="task-action d-flex">
                        <div class="task-delete btn btn-sm border border-info text-primary mr-2">
                            Delete
                        </div>
                        <div class="task-edit btn btn-sm border border-info text-primary mr-2">
                            Edit
                        </div>

                        @if($task->status === "finished")
                            <div class="task-reopen btn btn-sm border border-info text-primary ml-auto">
                                Reopen
                            </div>
                        @else
                            <div class="task-extend btn btn-sm border border-info text-primary mr-2 ml-auto">
                                Extend
                            </div>

                            <a href="{{ url()->current() . '/finished' }}">
                                <div class="task-finished btn btn-sm border border-info text-primary">
                                    Finished
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="task-created-at-show-utility mt-1 d-flex text-secondary font-italic px-2" style="font-size: 12px;">
                    <div class="task-created-prefix pr-1">
                        Created: 
                    </div>
                    <div class="task-created-at">
                        {{ $task->created_at }}
                    </div>
                </div>

                <div class="task-edit-utility-wrapper">
                    @if(session('showEditUtility') !== null)
                        <div class="task-edit-utility px-2 mt-4 pt-3 border rounded" style="background-color: #e9ebee;">
                    @else
                        <div class="task-edit-utility px-2 mt-4 pt-3 border rounded" style="background-color: #e9ebee;" hidden>
                    @endif
                        <form class="task-edit-utility-form" action=" {{ url()->current() . '/edit'}} " method="POST">
                            @csrf
                            <div class="container form-group">
                                <div class="row">
                                    <div class="col">
                                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $task->title) }}" required autocomplete="title">

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="container form-group">
                                <div class="row">
                                    <div class="col">
                                        <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required autocomplete="description" style="height: 240px">{{ old('description', $task->description) }}</textarea>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="form-group row">
                                    <div class="ml-auto mr-3">
                                        <button id="submit" type="submit" class="btn btn-sm btn-primary-outline text-primary border border-primary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- the below solution is not good, i'll rewrite it later -->
                    @if(session('showExtendUtility') !== null)
                        <div class="task-extend-utility px-2 mt-4 pt-3 border rounded" style="background-color: #e9ebee;">
                    @else
                        <div class="task-extend-utility px-2 mt-4 pt-3 border rounded" style="background-color: #e9ebee;" hidden>
                    @endif
                    <!-- <div class="task-extend-utility px-2 mt-4 pt-3 border rounded" style="background-color: #e9ebee;" {{ (session('showExtendedUtility') !== null) ? '' : 'hidden' }}> -->
                        <form class="task-extend-utility-form" action="{{ url()->current() . '/extend' }}" method="POST">
                            @csrf
                            <div class="container form-group">
                                <div class="row">
                                    <label for="expected-within-time" class="col-lg-3 col-form-label font-weight-bold text-md-left">Expected within</label>

                                    <div class="col">
                                        <input id="expected-within-time" type="time" class="form-control @error('expected-within-time') is-invalid @enderror" name="expected-within-time" value="{{ old('expected-within-time', date('H:i', strtotime('+6 hours'))) }}" required autocomplete="expected-within-time" min="{{ date('H:i', strtotime('+6 hours')) }}">

                                        @error('expected-within-time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col">
                                        <input id="expected-within-date" type="date" class="form-control @error('expected-within-date') is-invalid @enderror" name="expected-within-date" value="{{ old('expected-within-date', date('Y-m-d', strtotime('+6 hours'))) }}" required autocomplete="expected-within-date" min="{{ date('Y-m-d', strtotime('+6 hours')) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="form-group row">
                                    <div class="ml-auto mr-3">
                                        <button id="submit" type="submit" class="btn btn-sm btn-primary-outline text-primary border border-primary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="task-add-milestone-form-utility p-2 my-4">
                    <div class="task-milestone-form-prefix my-2 border-bottom border-grey font-weight-bold ">
                        Add new milestone
                    </div>
                    <div class="task-add-milestone-form">
                        <form action="{{ url()->current() . '/milestones/store' }}" method="POST">
                            @csrf
                            <div class="task-milestone-form-item-container d-flex align-items-center">
                                <div class="task-milestone-input-box" style="width: 100%;">
                                    <textarea id="milestone" type="text" class="form-control @error('milestone') is-invalid @enderror px-3" name="milestone" required autocomplete="milestone" style="height: 38px; border-radius: 25px;">{{ old('milestone') }}</textarea>
                                    
                                    @error('milestone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="task-milestone-submit-box pl-2">
                                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 16px; width: 60px;">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="task-milestone-content-utility px-md-2">
                <div class="task-milestone-prefix text-primary font-weight-bold border-bottom border-grey mb-2">
                    Milestones
                </div>
                <div class="task-milestone-list">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section("hidden-component")

<div id="task-status-badge-finished-template" class="task-status-badge-finished border border-success btn-sm btn-success text-white font-weight-bold">
    Finished
</div>

<div id="task-status-badge-todo-template" class="task-status-badge-finished border border-warning btn-sm btn-warning text-dark font-weight-bold">
    Todo
</div>

<div id="task-status-badge-unfinished-template" class="task-status-badge-unfinished border border-danger btn-sm btn-danger text-white font-weight-bold">
    Unfinished
</div>



<div id="task-finished-template" class="task-finished btn btn-sm border border-info text-primary ml-auto">
    Mark as Finished
</div>
<div id="task-reopen-template" class="task-reopen btn btn-sm border border-info text-primary ml-auto">
    Reopen task
</div>



<div id="task-milestone-item-template" class="task-milestone-item border border-grey rounded-lg px-2 py-2 my-2">
    <div class="task-milestone-item-top pb-1 d-flex text-secondary font-italic" style="font-size: 12px;">
        <div class="task-milestone-item-prefix pr-1">
            added on: 
        </div>
        <div class="task-milestone-item-created">
            12:20pm, 27th August, 2020
        </div>
    </div>
    <div class="task-milestone-item-description" style="white-space: pre-wrap;"></div>
</div>


<div id="task-milestone-list-view-previous-template" class="task-milestone-list-view-previous text-right">
    <div class="task-milestone-list-view-previous-msg btn btn-sm text-primary" style="text-decoration: underline;">
        view previous...
    </div>
</div>

@endsection