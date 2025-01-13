<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Face Detection</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/assets/face-detection/style/face-detection.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src='/assets/face-detection/js/face-api.min.js'></script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>--}}
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
</head>
<body>
<main>
    <div class="container mt-1">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 align-top">
                <div class="row mb-3">
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch">
                            <input type="checkbox" id="webcam-switch">
                            <i></i> Webcam </label>
                        <button id="cameraFlip" class="btn d-none"></button>
                    </div>
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch disabled">
                            <input type="checkbox" disabled id="detection-switch">
                            <i></i> Detect Face </label>
                    </div>
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch disabled">
                            <input type="checkbox" disabled id="box-switch">
                            <i></i> Bounding Box </label>
                    </div>
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch disabled">
                            <input type="checkbox" disabled id="landmarks-switch">
                            <i></i> Landmarks </label>
                    </div>
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch disabled">
                            <input type="checkbox" disabled id="expression-switch">
                            <i></i> Expression </label>
                    </div>
                    <div class="col-md-10 col-6 form-control">
                        <label class="form-switch disabled">
                            <input type="checkbox" disabled id="age-gender-switch">
                            <i></i> Age & Gender </label>
                    </div>
                </div>
            </div>


            <div class="col-12 col-md-8 col-xl-9 align-top" id="webcam-container">
                <div id="message" style="display:none" >Kuling</div>
                <div class="loading d-none">
                    Loading Model
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>

                <div id="video-container">
                    <video id="webcam" autoplay muted playsinline></video>
                </div>

                <div id="snap_neutral"></div>
                <div id="snap_happy"></div>
                <div id="snapshots"></div>
                <input type="hidden" name="image" class="image-tag">

                <button onclick="takePic()" class="btn btn-primary">take picture</button>

                <div id="errorMsg" class="col-12 alert-danger d-none">
                    Fail to start camera <br>
                    1. Please allow permission to access camera. <br>
                    2. If you are browsing through social media built in browsers, look for the ... or browser icon on the right top/bottom corner, and open the page in Sarafi (iPhone)/ Chrome (Android)
                </div>
            </div>
        </div>
    </div>
</main>
<div id="results"></div>

<script src="/assets/face-detection/js/face-detection.js"></script>

<script>

    const video = document.getElementById('webcam');
    // take snapshot from video stream
    function takePic(attr) {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
        const data = canvas.toDataURL('image/png');
        console.log(data);
        document.querySelector('.image-tag').value = data;
        const img = document.createElement('img');
        img.src = data;
        document.getElementById('snap_'+attr).appendChild(img);
    }

</script>
</body>
</html>
