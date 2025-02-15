


<header id="page-topbar" class="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/assets/images/mbf_logo.png" alt="" height="22" style="height: 20px">МЕДНАЯ <br>ОБОГАТИТЕЛЬНАЯ <br>ФАБРИКА
                    </span>
                    <span class="logo-lg">
                        <img src="/assets/images/mbf_logo.png" alt="" height="20" style="height: 20px">МЕДНАЯ <br>ОБОГАТИТЕЛЬНАЯ <br>ФАБРИКА
                    </span>
                </a>

                <a href="/" class="logo logo-light" style="display: flex; align-items: center; margin-top: 10px; text-decoration: none;">
                    <img src="/assets/images/mbf_logo.png" alt="Логотип" style="height: 70px; margin-right: 10px;">
                    <div style="line-height: 1.2; color: #fff; font-family: Arial, sans-serif;">
                        <span style="font-size: 18px; display: block;">МЕДНАЯ</span>
                        <span style="font-size: 16px; display: block;">ОБОГАТИТЕЛЬНАЯ</span>
                        <span style="font-size: 16px; display: block;">ФАБРИКА</span>
                    </div>
                </a>
            </div>
        </div>

        <h5 class="text-light footer-title pt-4">@yield('header_title', '1 - ПОҒОНА ИШЛАБ ЧИҚАРИШ НАЗОРАТИ ЖУРНАЛИ')</h5>


        <div class="d-flex">
            <div class="dropdown d-inline-block">
                @auth
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span
                        class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">{{ \Illuminate\Support\Facades\Auth::user()->first_name . ' ' . \Illuminate\Support\Facades\Auth::user()->last_name }}</span>
                    <i class="fa  fa-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">



                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt font-size-18 align-middle me-1 text-muted"></i>
                        <span class="align-middle">Выход</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            @endauth


            <div class="dropdown d-inline-block">
                <button class="btn header-item waves-effect sun p-0">
                    <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked
                           style="display: none"/>
                    <label class="form-check-label btn noti-icon waves-effect mt-0" for="light-mode-switch"><i class="fa fa-sun"></i>
                    </label>
                </button>
                <button class="btn header-item waves-effect moon p-0" style="display: none">
                    <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch"
                           style="display: none"/>
                    <label class="form-check-label btn noti-icon waves-effect mt-0" for="dark-mode-switch"><i class="fa fa-moon"></i></label>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="topnav">
            @include('core.components.menu')
        </div>
    </div>
</header>
