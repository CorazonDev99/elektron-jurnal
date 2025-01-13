@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2>Create New User</h2>
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back </a>
            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong>Something went wrong.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <form id="create-user-form" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>First Name:</strong>
                            <input type="text" name="first_name" class="form-control" placeholder="first_name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Last Name:</strong>
                            <input type="text" name="last_name" class="form-control" placeholder="last_name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Username:</strong>
                            <input type="text" name="username" class="form-control" placeholder="username">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="text" name="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Profile Picture:</strong>
                            <input id="image" type="file" name="avatar" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password:</strong>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Confirm Password:</strong>
                            <input type="password" name="confirm-password" class="form-control" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong>Role:</strong>
                            <select name="roles[]" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong>Permissions:</strong>
                            <div>
                                @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}">
                                        <label class="form-check-label">
                                            {{ $permission }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
@endsection



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#create-user-form').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var userId = response.user_id;

                    if ($('#image')[0].files.length > 0) {
                        var imageData = new FormData();
                        imageData.append('user_id', userId);
                        imageData.append('image', $('#image')[0].files[0]);

                        $.ajax({
                            url: "http://127.0.0.1:8000/api/upload/",
                            type: 'POST',
                            data: imageData,
                            processData: false,
                            contentType: false,
                            success: function (imageResponse) {
                                window.location.href = "{{ route('users.index') }}";
                            },
                            error: function (xhr, status, error) {
                                console.error("Error details:", {
                                    status: status,
                                    error: error,
                                    responseText: xhr.responseText
                                });

                                var errorMessages = 'An error occurred while uploading the image. Please try again.';
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    var errors = xhr.responseJSON.errors;
                                    errorMessages = '';
                                    $.each(errors, function (key, value) {
                                        errorMessages += value[0] + '\n';
                                    });
                                }
                                alert('Error:\n' + errorMessages);
                            }
                        });

                    } else {
                        alert('User created successfully!');
                        window.location.href = "{{ route('users.index') }}";
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Status:", status);
                    console.log("Error:", error);
                    console.log("Response:", xhr.responseText);
                    var errorMessages = 'An error occurred while creating the user. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMessages = '';
                        $.each(errors, function (key, value) {
                            errorMessages += value[0] + '\n';
                        });
                    }
                    alert('Error:\n' + errorMessages);
                }

            });
        });
    });

</script>

