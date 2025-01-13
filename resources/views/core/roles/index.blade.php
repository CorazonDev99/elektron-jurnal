@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2>Role Management</h2>
                <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role </a>
            </div>
            <div class="card-body">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                                <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>

                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>


            </div>
        </div>
    </div>
@endsection
