@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2>Edit User</h2>
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back </a>
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
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>First Name:</strong>
                                <input type="text" name="first_name" class="form-control" placeholder="First name" value="{{ $user->first_name }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Last Name:</strong>
                                <input type="text" name="last_name" class="form-control" placeholder="Last name" value="{{ $user->last_name }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Username:</strong>
                                <input type="text" name="username" class="form-control" placeholder="Username" value="{{ $user->username }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Profile Picture:</strong>
                                <input type="file" name="avatar" class="form-control">
                            </div>
                        </div>

                        @if ($user->avatar)
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Current Profile Picture:</strong>
                                    <br>
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Image" width="150">
                                </div>
                            </div>
                        @endif

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password:</strong>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                <input type="password" name="confirm-password" class="form-control" placeholder="Confirm Password">
                            </div>
                        </div>

                        @role('super-admin')
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Role:</strong>
                                <select name="roles[]" class="form-control">
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endrole

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Permissions:</strong>
                                <div>
                                    @foreach($allPermissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
