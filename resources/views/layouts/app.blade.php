<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
{{--    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">--}}

{{--    @vite(['resources/css/app.css', 'resources/js/app.js'])--}}

    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        .dropdown-menu i{
            width: 1.5rem;
            text-align: center;
        }
        #page-topbar {
            background: #2F4050 !important;
        }
        body {
            background-image: url('/assets/images/mbf.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }
    </style>

    @yield('style')


    <!-- Scripts -->
</head>
{{--preloader--}}

{{--<body class="font-sans antialiased">--}}
<body data-layout="horizontal" data-topbar="colored">
<div id="layout-wrapper">


        @include('core.components.header')

{{--    @include('core.components.sidebar')--}}

    <div class="main-content">
        <div class="page-content">
            @yield('content')
        </div>
        @include('core.components.footer')
    </div>


</div>


@include('core.components.scripts')

<script>
    const month = new Date().getMonth();
    const isWinter = [11, 0, 1].includes(month); // Зимние месяцы

    if (isWinter) {
        const canvas = document.createElement('canvas');
        document.body.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        let width = window.innerWidth;
        let height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;

        const snowflakes = [];
        const snowflakeImage = new Image();
        snowflakeImage.src = '/assets/images/snowflake.png';

        function createSnowflakes() {
            for (let i = 0; i < 50; i++) {
                snowflakes.push({
                    x: Math.random() * width,         // Позиция по X
                    y: Math.random() * height,        // Позиция по Y
                    speed: Math.random() * 2 + 1,     // Скорость падения
                    size: Math.random() * 10 + 10,    // Размер снежинки
                    drift: Math.random() * 1 - 0.5    // Горизонтальное движение
                });
            }
        }

        // Функция для рисования снежинок
        function drawSnowflakes() {
            ctx.clearRect(0, 0, width, height);
            snowflakes.forEach(snowflake => {
                ctx.drawImage(
                    snowflakeImage,
                    snowflake.x,
                    snowflake.y,
                    snowflake.size,
                    snowflake.size
                );
            });
        }

        // Функция для обновления положения снежинок
        function updateSnowflakes() {
            snowflakes.forEach(snowflake => {
                snowflake.y += snowflake.speed;   // Падение
                snowflake.x += snowflake.drift;  // Горизонтальное смещение

                if (snowflake.y > height) snowflake.y = -snowflake.size; // Возвращение сверху
                if (snowflake.x > width) snowflake.x = 0;                // Перемещение слева
                if (snowflake.x < 0) snowflake.x = width;               // Перемещение справа
            });
        }

        // Анимация
        function animate() {
            drawSnowflakes();
            updateSnowflakes();
            requestAnimationFrame(animate);
        }

        // Обновление размеров Canvas при изменении окна
        window.addEventListener('resize', () => {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
        });

        snowflakeImage.onload = () => {
            createSnowflakes();
            animate();
        };
    }
</script>
</body>
</html>
