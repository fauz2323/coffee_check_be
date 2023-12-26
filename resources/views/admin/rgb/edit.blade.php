@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">RGB Detail</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hexcolor.edit', Crypt::encrypt($data->id)) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Type</label>
                                <input type="text" class="form-control" name="type" value="{{ $data->type }}">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Red</label>
                                <input type="text" class="form-control" name="red" value="{{ $data->red }}">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Green</label>
                                <input type="text" class="form-control" name="green" value="{{ $data->green }}">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Blue</label>
                                <input type="text" class="form-control" name="blue" value="{{ $data->blue }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
