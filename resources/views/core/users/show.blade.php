@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="card shadow-sm border-light rounded">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0">Show User</h2>
                <a class="btn btn-light text-primary" href="{{ route('users.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="roles-tab" data-bs-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">
                            <i class="bi bi-shield"></i> Role
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="permissions-tab" data-bs-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">
                            <i class="bi bi-lock"></i> Permissions
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="userTabsContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Image" class="img-fluid border" style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/200" alt="Profile Image" class="img-fluid border" style="width: 200px; height: 200px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-2">
                                    <strong>First Name:</strong>
                                    <p class="mb-0">{{ $user->first_name }}</p>
                                </div>
                                <div class="form-group mb-2">
                                    <strong>Last Name:</strong>
                                    <p class="mb-0">{{ $user->last_name }}</p>
                                </div>
                                <div class="form-group mb-2">
                                    <strong>Username:</strong>
                                    <p class="mb-0">{{ $user->username }}</p>
                                </div>
                                <div class="form-group mb-2">
                                    <strong>Email:</strong>
                                    <p class="mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Permissions:</strong>
                                    <ul class="list-group mt-2">
                                        @forelse($user->roles as $role)
                                            <li class="list-group-item">{{ $role->name }}</li>
                                        @empty
                                            <li class="list-group-item">No rights assigned</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Permissions:</strong>
                                    <ul class="list-group mt-2">
                                        @forelse($user->permissions as $permission)
                                            <li class="list-group-item">{{ $permission->name }}</li>
                                        @empty
                                            <li class="list-group-item">No rights assigned</li>
                                        @endforelse
                                    </ul>
                                </div>

                                @if($isSuperUser)
                                    <form action="{{ route('permissions.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group mt-3">
                                            <label for="permissions">Select the rights that the user does not have</label>
                                            <div class="permissions-list">
                                                @foreach($permissions as $permission)
                                                    @if(!in_array($permission->id, $user->permissions->pluck('id')->toArray()))
                                                        <div class="form-check">
                                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                                   class="form-check-input" id="permission-{{ $permission->id }}">
                                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">
                                            <i class="bi bi-save"></i> Save changes
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
