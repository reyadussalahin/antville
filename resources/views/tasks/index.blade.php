@extends("layouts.app")

@section("content")

<div class="container">
	<div class="row">

		<div class="col-md-6">
			<div class="todo-content mx-lg-1">
				<div class="todo-header pb-2 border-bottom border-gray d-flex" style="height: 38px;">
					<div class="todo-header-item mt-1 text-primary font-weight-bold">
						TODOs
					</div>
					<a href="{{ route('tasks.create') }}" class="ml-auto">
						<div class="todo-header-item btn btn-sm border border-info text-primary">
							Add New
						</div>
					</a>
				</div>
				<div class="todo-list">
                </div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="finished-content mx-lg-1">
				<div class="finished-header pb-2 border-bottom border-gray d-flex" style="height: 38px;">
					<div class="finished-header-item mt-1 text-success font-weight-bold">
						Finished
					</div>
				</div>
				<div class="finished-list">
                </div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="unfinished-content mx-lg-1">
				<div class="unfinished-header pb-2 border-bottom border-gray d-flex" style="height: 38px;">
					<div class="unfinished-header-item mt-1 text-danger font-weight-bold">
						Unfinished
					</div>
				</div>
				<div class="unfinished-list">
                </div>
			</div>
		</div>

	</div>
</div>

@endsection


@section("hidden-component")

<div id="todo-item-template" class="todo-item my-2 py-1 px-2 rounded-lg bg-warning">
	<div class="todo-item-top my-2 d-flex" style="font-size: 13px">
		<div class="todo-item-created-at-prefix pr-1">
			Created on: 
		</div>
		<div class="todo-item-created-at">
			12:30pm, 27th June, 2020
		</div>
	</div>
	<div class="todo-item-mid">
		<a class="todo-item-link" href="#" style="color: black;">
			<div class="todo-item-title font-weight-bold">
				This is the title. Lorem ispusm dolor set. Abol tablo bekar sob. Jaihok taihok sob i thk. Ekhon kokhon eshei gese.
			</div>
		</a>
	</div>
	<div class="todo-item-low my-2 d-flex" style="font-size: 13px">
		<div class="todo-item-expected-within-prefix pr-1">
			Expect within: 
		</div>
		<div class="todo-item-expected-within">
			16th July, 2020
		</div>
	</div>
</div>


<div id="finished-item-template" class="finished-item my-2 py-1 px-2 rounded-lg" style="background-color: lightgreen;">
	<div class="finished-item-mid my-2">
		<a class="finished-item-link" href="#" style="color: black;">
			<div class="finished-item-title font-weight-bold">
				The finished task's title. Lorem ipsum doloer set. Baki taki alo balo. Tumi kotha aso bepar nah.
			</div>
		</a>
	</div>
	<div class="finished-item-low my-2 d-flex" style="font-size: 13px">
		<div class="finished-item-finished-at-prefix pr-1">
			Finished at: 
		</div>
		<div class="finished-item-finished-at">
			12th July, 2020
		</div>
	</div>
</div>

<div id="unfinished-item-template" class="unfinished-item my-2 py-1 px-2 rounded-lg" style="background-color: rgb(255, 87, 109);">
	<div class="unfinished-item-mid my-2">
		<a class="unfinished-item-link" href="#" style="color: black;">
			<div class="unfinished-item-title font-weight-bold">
				The unfinished task's title. Lorem ipsum doloer set. Baki taki alo balo. Tumi kotha aso bepar nah.
			</div>
		</a>
	</div>
	<div class="unfinished-item-low my-2 d-flex" style="font-size: 13px">
		<div class="unfinished-item-expected-within-prefix pr-1">
			Expected: 
		</div>
		<div class="unfinished-item-expected-within">
			12th July, 2020
		</div>
	</div>
</div>




<div id="todo-list-show-more-template" class="todo-list-show-more text-right">
    <div class="todo-list-show-more-msg btn btn-sm text-primary" style="text-decoration: underline;">
        show more...
    </div>
</div>

<div id="finished-list-show-more-template" class="finished-list-show-more text-right">
    <div class="finished-list-show-more-msg btn btn-sm text-primary" style="text-decoration: underline;">
        show more...
    </div>
</div>

<div id="unfinished-list-show-more-template" class="unfinished-list-show-more text-right">
    <div class="unfinished-list-show-more-msg btn btn-sm text-primary" style="text-decoration: underline;">
        show more...
    </div>
</div>



<div id="todo-list-empty-msg-template" class="todo-list-empty-msg text-dark mt-3 mb-4 px-2 font-italic">
	<span class="font-weight-bold">No Todos !</span> Looks like, you have been enjoying free times...Have fun...
</div>

<div id="finished-list-empty-msg-template" class="finished-list-empty-msg text-dark mt-3 mb-4 px-2 font-italic">
	<span class="font-weight-bold">No tasks Finished yet!</span> Don't worry, you'll catch up soon. Stay with us and keep track of your activity...
</div>

<div id="unfinished-list-empty-msg-template" class="unfinished-list-empty-msg text-dark mt-3 mb-4 px-2 font-italic">
	<span class="font-weight-bold">Bravo!</span> No Unfnished tasks! You're a champ!
</div>

@endsection