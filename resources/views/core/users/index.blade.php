@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex align-items-end justify-content-between">
                <h2>Users Management</h2>
                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User </a>
            </div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('users.index') }}">
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ request()->username }}">
                        </div>
                        <div class="col">
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request()->email }}">
                        </div>
                        <div class="col">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ request()->first_name }}">
                        </div>
                        <div class="col">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ request()->last_name }}">
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                @php
                    $hasSearch = request()->has('username') || request()->has('email') || request()->has('first_name') || request()->has('last_name');
                @endphp

                @if($hasSearch && $data->count() > 0)
                    <table id="userTable" class="table table-sm table-hover table-bordered table-striped table-nowrap align-middle">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if(!empty($user->roles))
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUser{{$user->id}}">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="deleteUser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteUser{{$user->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUser{{$user->id}}">Are you sure you want to delete user: <br><b>{{ $user->first_name }}</b>?</h5>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        </tbody>
                    </table>

                @elseif($hasSearch)
                    <div class="alert alert-info">No results found.</div>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10
            });
        });
    </script>

@endsection
