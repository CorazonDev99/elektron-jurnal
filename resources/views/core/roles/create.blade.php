@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2>Create New Role</h2>
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a>
            </div>
            <div class="card-body">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Something went wrong.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Permission:</strong>
                            <br/>
                            @foreach($permission as $value)
                                <label>
                                    <input type="checkbox" name="permission[]" class="name" value="{{ $value->id }}">
                                    {{ $value->name }}</label>
                                <br/>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </form>
            </div>

        </div>

    </div>

@endsection
