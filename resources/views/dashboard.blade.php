@extends('layouts.app')

@section('style')


    <style>

        /*---------------------------*/
        @media only screen and (max-width: 1249px) {
            div.main_wrapper{
                width: 100%;
                margin: 0 auto;
            }
            div.middleHeader{
                text-align: left;
                padding-left: 30px;
            }
            .middleHeader span{
                font-size: 40px;
            }
            .middleHeader h1{
                margin: 0px;
            }
            .middleheader_1, .middleheader_2, .middleheader_3::after {
                content: '';
                border-bottom: solid 6px rgb(218 111 0);
                margin-bottom: 10px;
                padding-bottom: 10px;
            }
        }

        #hideDiv{
            padding-left: 20px;
        }
        #slideset2 {
            height: 10em; position: relative;
            overflow: hidden;

        }
        #slideset2 > * {position: absolute; top: 100%; left: 0;
            animation: 12s autoplay2 infinite ease-in-out}
        @keyframes autoplay2 {
            0% {top: 100%;}
            4% {top: 0%;}
            33.33% {top: 0%;}
            37.33% {top: -100%; }
            100% {top: -100%;}
        }
        #slideset2 > *:nth-child(1) {animation-delay: 0s}
        #slideset2 > *:nth-child(2) {animation-delay: 4s}
        #slideset2 > *:nth-child(3) {animation-delay: 8s}

        .middleHeader{
            margin-top: 30vh;
            position: absolute;
        }

        .middleHeader span{
            font-family: Bender;
            color: white;
            font-weight: 600;
            font-size:70px;
            text-shadow:1px 3px 9px rgba(0,0,0,1.0)!important;

            transition: 0.25s ease-in-out!important;
            line-height: 1;
            text-transform: uppercase;
            letter-spacing: 1.32px;
            /*text-decoration: underline;*/
        }
        #hideDiv{
            padding-left: 20px;
        }
        .headWe{
            bottom: 215px;
            position: absolute;
            font-size: 50px;
            color: white;
            font-family: 'Bender';
        }

        .middleheader_1, .middleheader_2, .middleheader_3::after {
            content: '';

            border-bottom: solid 6px rgb(218 111 0);
            margin-bottom: -10px;
            padding-bottom: 20px;
            display: block;
            width: 50px;
        }
        .middleHeader span:hover{
            color: lightgray;
            cursor: pointer;
        }
        .middleheader_btn{
            width: 330px;
            font-family: Bender;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 3px;
            background-color: #da6f00;
            border: 2px solid#da6f00;
            padding:10px 40px;
            margin-top: 50px;
            color: white;
            text-align: left;
            border-radius: 18px;/**/
            /*box-shadow: 2px 3px 9px 0px rgba(0,0,0,0.9);*/
            transition:all 0.3s ease-in-out;
            overflow: hidden;
            z-index: 99;
        }
        .middleheader_btn:hover{
            /*background-color:  #1d1d1dcc;
            color: #ffffff;
            border: 1px solid #9a9a9a4f;*/
            background-color: rgba(97, 64, 2, 0);
            border: 2px solid #ffffff;
            /*box-shadow: 2px 3px 9px 0px rgba(0,0,0,0.9);*/
            cursor: pointer;
            /*box-shadow: 0px 15px 12px 0px rgba(0,0,0,0.9);*/
        }

        span.middleheader_card_span{
            font-size: 14px;
            margin:0;
            color:white;
            padding-left: 10px;
            transition:all 0.25s ease-in-out;
        }
        .middleheader_btn:hover span.middleheader_card_span{
            padding-left: 15px;
        }
        /*.middleheader_btn:active span.middleheader_card_span{
            font-size: 18px;
        }*/
        .middleheader_descrip{
            font-size: 20px!important;
        }
    </style>

@endsection
@section('script')
    <script>



        if (document.readyState == 'loading') {
            document.getElementById('frame').style.display = "none";
            console.log('frame none');
        }
        function myIframe_2Func() {
            document.getElementById('frame').style.display = "block";
            console.log('frame block');
        }

        /*-------------------------------------------------------------------- */
        AOS.init({  duration: 1000})
        /*-------------------------------------------------------------------- */

        jQuery(document).ready(function($) {
            $('.submenu > li').matchHeight();
        });
        /*-------------------------------------------------------------------- */
        var $leftMenu = $('#container');
        var leftVal = parseInt($leftMenu.css('left'));

        $('#toggleButton').click(function () {
            animateLeft = (parseInt($leftMenu.css('left')) == 0) ? leftVal : 0;
            $leftMenu.animate({
                left: animateLeft + 'px'
            });
        });

        /*-------------------------------------------------------------------- */
        var header = document.getElementById("hideDiv");
        var modal = document.getElementById("myModal");

        function myFunction() {
            document.getElementById('frame').style.display = "block";
            console.log('frame block');
            console.log("click");
            header.style.display = "none";
        }

        //var modal_content = document.getElementsByClassName("modal-content")[0];
        //var btn = document.getElementById("btn-click-me");
        var btn = document.getElementsByName("btn-click-me");
        var span = document.getElementsByClassName("close")[0];
        for(var i = 0; i < btn.length; i++){
            btn[i].onclick = function() {
                modal.style.display = "block";
                //header.style.display = "none";
            }
        }
        span.onclick = function() {
            modal.style.display = "none";
            localStorage.setItem('var1', 0);
            //header.style.display = "block";
            console.log("span clicked--");
            //modal_content.empty();
            // window.open("top_menu/includes/modal_window_month.php?ur=test_empty", "myiframe");
            window.open("bottom_menu/sortnost/empty.php", "myiframe");
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                localStorage.setItem('var1', 0);

                console.log("win clicked");
                //window.open("top_menu/includes/modal_window_month.php?ur=test_empty", "myiframe");
                window.open("bottom_menu/sortnost/empty.php", "myiframe");
            }
        }
        /*-------------------------------------------------------------------- */
        function MenuCheckFuntion() {
            var chbox, menuCont;
            chbox=document.getElementById('menuCheck');
            menuCont=document.getElementsByClassName('top-menu')[0];
            if (chbox.checked) {
                menuCont.classList.add("hambMenu");
                menuCont.style.transform = "translate(0, 0)";
            }
            else {
                if ($(window).width() < 615) {
                    menuCont.style.transform = "translate(-100vw, 0)";
                    console.log("less than 615");
                }
                else{
                    menuCont.style.transform = "translate(0, 0)";
                    console.log("more than 615");
                }

            }
        }
    </script>
@endsection
@section('content')
    <div class="container-fluid">
        <div id="hideDiv" data-aos="fade-up"  data-aos-anchor-placement="top" data-aos-once="true"data-aos-delay="1500">
            <div class="middleHeader"  id="slideset2"><!--id="middleHeader_"-->
                <div><h1><span class="middleheader_1">Производительность</span> </h1><span class="middleheader_descrip">Переработка более 39 млн тонн руды в год</span></div>
                <div><h1><span class="middleheader_2">Качество</span> </h1><span class="middleheader_descrip">Наша главная задача</span></div>
                <div><h1><span class="middleheader_3">Инновация</span> </h1><span class="middleheader_descrip">Внедрение новейших технологий</span></div>
            </div>
            <a href="http://mof.agmk.uz/v2/about/about.php" target="_blank"data-aos="fade-up"  data-aos-anchor-placement="top" data-aos-once="true"data-aos-delay="1800"><button   class="middleheader_btn">Подробнее о фабрике<span class="middleheader_card_span">&#10230;</span></button></a>
        </div>

    </div>
@endsection
