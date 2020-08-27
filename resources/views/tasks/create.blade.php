@extends("layouts.app")

@section("content")

<div class="container">
    <div class="row">
        <div class="col">
            <div class="task-create-form-wrapper">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="container form-group">
                        <div class="row">
                            <label for="title" class="col-md-2 col-lg-3 col-form-label font-weight-bold text-md-right">Title</label>

                            <div class="col-md-8 col-lg-6 text-left">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>

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
                            <label for="description" class="col-md-2 col-lg-3 col-form-label font-weight-bold text-md-right">Description</label>

                            <div class="col-md-8 col-lg-6 text-left">
                                <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required autocomplete="description" style="height: 240px">{{ old('description') }}</textarea>
                                

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="container form-group">
                        <div class="row">
                            <label for="expected-within-time" class="col-md-2 col-lg-3 col-form-label font-weight-bold text-md-right">Expected within</label>

                            <div class="col-md-4 col-lg-3 text-left">
                                <input id="expected-within-time" type="time" class="form-control @error('expected-within-time') is-invalid @enderror" name="expected-within-time" value="{{ old('expected-within-time', date('H:i', strtotime('+6 hours'))) }}" required autocomplete="expected-within-time" min="{{ date('H:i', strtotime('+6 hours')) }}">

                                @error('expected-within-time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-lg-3 text-left">
                                <input id="expected-within-date" type="date" class="form-control @error('expected-within-date') is-invalid @enderror" name="expected-within-date" value="{{ old('expected-within-date', date('Y-m-d', strtotime('+6 hours'))) }}" required autocomplete="expected-within-date" min="{{ date('Y-m-d', strtotime('+6 hours')) }}">
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="form-group row mb-0">
                            <div class="col-md-8 col-lg-6 offset-md-2 offset-lg-3">
                                <button id="submit" type="submit" class="btn btn-primary">
                                    Add to Todo List
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection