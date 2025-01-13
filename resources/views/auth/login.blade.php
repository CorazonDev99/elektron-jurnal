@extends('layouts.guest')
@section('title', 'Kirish')
@section('styles')
    <link rel="stylesheet" href="/assets/face-detection/style/face-detection.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src='/assets/face-detection/js/face-api.min.js'></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <style>

        .card{
            width: 600px;
            height: 500px;

        }

        .card-f {
            width: 600px;
            height: 500px;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-8 col-lg-6 col-xl-5 card-f">
        <div class="card">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Xush kelibsiz!</h5>
                    <p class="text-muted">Hisobingizga kirish uchun email va parolingizni kiriting.</p>
                </div>
                <div class="p-2 mt-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div id="login">
                            <div class="mb-3">
                                <label class="form-label" for="email">{{__('Email')}}</label>
                                <input type="text" class="form-control" id="email" placeholder="Email kiriting" value="">
                            </div>

                            <div class="mb-3">
                                <div class="float-end">
                                    <a href="auth-recoverpw.html" class="text-muted">Parolni unutdingizmi?</a>
                                </div>
                                <label class="form-label" for="password">Parol</label>
                                <input type="password" class="form-control" id="password"
                                       placeholder="Parol kiriting">
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                <label class="form-check-label" for="auth-remember-check">Eslab qolish</label>
                            </div>

                            <div class="mt-3 text-end">
                                <button class="btn btn-primary w-sm waves-effect waves-light" type="button"
                                        onclick="getUser()">Kirish
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="/assets/facedetection/jquery.facedetection.js"></script>
    <script>
        function getUser() {
            let email = $('#email').val();
            let password = $('#password').val();

            $.ajax({
                url: '/api/user',
                type: 'POST',
                data: {
                    email: email,
                    password: password
                },
                success: function (response) {
                    if (response.status === 'success') {
                        var userId = response.user_id;
                        window.location.href = '/dashboard?user_id=' + userId;
                    } else {
                        alert('User not found');
                    }
                },
                error: function (jqXHR) {
                    alert('Error: ' + jqXHR.responseJSON.message);
                }
            });
        }



    </script>

    <script src="/assets/face-detection/js/face-detection.js"></script>
@endsection
