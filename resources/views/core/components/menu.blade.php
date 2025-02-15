<nav class="navbar navbar-light navbar-expand-lg topnav-menu">

    <div class="collapse navbar-collapse justify-content-between" id="topnav-menu-content">
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="fa fa-home me-2"></i> Бош сахифа
                </a>

            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button">
                    <i class="fas fa-list-ul me-2"></i>МОФ Участкалари<div class="arrow-down"></div>
                </a>
                <div class="dropdown-menu mega-dropdown-menu px-2" aria-labelledby="topnav-uielement">
                    @if(in_array($roleId, [1, 2]))
                        <div>
                            <a href="/asutp" class="dropdown-item"><i class="fa fa-hotel"></i> АСУТП</a>
                            <a href="/dc1" class="dropdown-item"><i class="fa fa-hotel"></i> ДЦ-1</a>
                            <a href="/udip" class="dropdown-item"><i class="fa fa-hotel"></i> УДиП</a>
                            <a href="/uiif" class="dropdown-item"><i class="fa fa-hotel"></i> УиИФ</a>
                            <a href="/sector1" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №1</a>
                            <a href="/sector2" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №2</a>
                            <a href="/udii" class="dropdown-item"><i class="fa fa-hotel"></i> УДиИ</a>
                            <a href="/reagent" class="dropdown-item"><i class="fa fa-hotel"></i> Участок по приготовлению реагентов</a>
                            <a href="/fso" class="dropdown-item"><i class="fa fa-hotel"></i> ФСО</a>
                            <a href="/presfilter" class="dropdown-item"><i class="fa fa-hotel"></i> Участок пресс-фильтров</a>
                            <a href="/molibden" class="dropdown-item"><i class="fa fa-hotel"></i> Участок молибденовой селекции</a>
                            <a href="/pns1a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-1А</a>
                            <a href="/pns2a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-2А</a>
                            <a href="/rmu" class="dropdown-item"><i class="fa fa-hotel"></i> РМУ</a>
                            <a href="/ec" class="dropdown-item"><i class="fa fa-hotel"></i> ЭЦ</a>
                        </div>
                    @elseif($roleId == 3 || $roleId == 17)
                        <div>
                            <a href="/dc1" class="dropdown-item"><i class="fa fa-hotel"></i> ДЦ-1</a>
                        </div>
                    @elseif($roleId == 4 || $roleId == 18)
                        <div>
                            <a href="/udip" class="dropdown-item"><i class="fa fa-hotel"></i> УДиП</a>
                        </div>
                    @elseif($roleId == 5 || $roleId == 19)
                        <div>
                            <a href="/uiif" class="dropdown-item"><i class="fa fa-hotel"></i> УиИФ</a>
                        </div>
                    @elseif($roleId == 6 || $roleId == 20)
                        <div>
                            <a href="/sector1" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №1</a>
                        </div>
                    @elseif($roleId == 7 || $roleId == 21)
                        <div>
                            <a href="/sector2" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №2</a>
                        </div>
                    @elseif($roleId == 8 || $roleId == 22)
                        <div>
                            <a href="/udii" class="dropdown-item"><i class="fa fa-hotel"></i> УДиИ</a>
                        </div>
                    @elseif($roleId == 9 || $roleId == 23)
                        <div>
                            <a href="/reagent" class="dropdown-item"><i class="fa fa-hotel"></i> Участок по приготовлению реагентов</a>
                        </div>
                    @elseif($roleId == 10)
                        <div>
                            <a href="/fso" class="dropdown-item"><i class="fa fa-hotel"></i> ФСО</a>
                            <a href="/molibden" class="dropdown-item"><i class="fa fa-hotel"></i> Участок молибденовой селекции</a>

                        </div>
                    @elseif($roleId == 24)
                        <div>
                            <a href="/fso" class="dropdown-item"><i class="fa fa-hotel"></i> ФСО</a>
                        </div>
                    @elseif($roleId == 26)
                        <div>
                            <a href="/molibden" class="dropdown-item"><i class="fa fa-hotel"></i> Участок молибденовой селекции</a>

                        </div>
                    @elseif($roleId == 11 || $roleId == 25)
                        <div>
                            <a href="/presfilter" class="dropdown-item"><i class="fa fa-hotel"></i> Участок пресс-фильтров</a>
                        </div>
                    @elseif($roleId == 12)
                        <div>
                            <a href="/pns1a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-1А</a>
                            <a href="/pns2a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-2А</a>
                        </div>
                    @elseif($roleId == 27)
                        <div>
                            <a href="/pns1a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-1А</a>
                        </div>
                    @elseif($roleId == 28)
                        <div>
                            <a href="/pns2a" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-2А</a>
                        </div>
                    @elseif($roleId == 13 || $roleId == 29)
                        <div>
                            <a href="/rmu" class="dropdown-item"><i class="fa fa-hotel"></i> РМУ</a>
                        </div>
                    @elseif($roleId == 14 || $roleId == 30)
                        <div>
                            <a href="/ec" class="dropdown-item"><i class="fa fa-hotel"></i> ЭЦ</a>
                        </div>
                    @elseif($roleId == 15 || $roleId == 16)
                        <div>
                            <a href="/asutp" class="dropdown-item"><i class="fa fa-hotel"></i> АСУТП</a>
                        </div>
                    @endif
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button">
                    <i class="fas fa-chart-bar me-2"></i>Хисоботлар<div class="arrow-down"></div>
                </a>
                <div class="dropdown-menu mega-dropdown-menu px-2" aria-labelledby="topnav-uielement">
                    @if(in_array($roleId, [1, 2]))
                        <div>
                            <a href="/asutp-report" class="dropdown-item"><i class="fa fa-hotel"></i> АСУТП</a>
                            <a href="/dc1-report" class="dropdown-item"><i class="fa fa-hotel"></i> ДЦ-1</a>
                            <a href="/udip-report" class="dropdown-item"><i class="fa fa-hotel"></i> УДиП</a>
                            <a href="/uiif-report" class="dropdown-item"><i class="fa fa-hotel"></i> УиИФ</a>
                            <a href="/sector1-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №1</a>
                            <a href="/sector2-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №2</a>
                            <a href="/udii-report" class="dropdown-item"><i class="fa fa-hotel"></i> УДиИ</a>
                            <a href="/reagent-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок по приготовлению реагентов</a>
                            <a href="/fso-report" class="dropdown-item"><i class="fa fa-hotel"></i> ФСО</a>
                            <a href="/presfilter-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок пресс-фильтров</a>
                            <a href="/molibden-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок молибденовой селекции</a>
                            <a href="/pns1a-report" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-1А</a>
                            <a href="/pns2a-report" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-2А</a>
                            <a href="/rmu-report" class="dropdown-item"><i class="fa fa-hotel"></i> РМУ</a>
                            <a href="/ec-report" class="dropdown-item"><i class="fa fa-hotel"></i> ЭЦ</a>
                        </div>
                    @elseif($roleId == 3 || $roleId == 17)
                        <div>
                            <a href="/dc1-report" class="dropdown-item"><i class="fa fa-hotel"></i> ДЦ-1</a>
                        </div>
                    @elseif($roleId == 4 || $roleId == 18)
                        <div>
                            <a href="/udip-report" class="dropdown-item"><i class="fa fa-hotel"></i> УДиП</a>
                        </div>
                    @elseif($roleId == 6 || $roleId == 19)
                        <div>
                            <a href="/uiif-report" class="dropdown-item"><i class="fa fa-hotel"></i> УиИФ</a>
                        </div>
                    @elseif($roleId == 5 || $roleId == 20)
                        <div>
                            <a href="/sector1-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №1</a>
                        </div>
                    @elseif($roleId == 7 || $roleId == 21)
                        <div>
                            <a href="/sector2-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок №2</a>
                        </div>
                    @elseif($roleId == 8 || $roleId == 22)
                        <div>
                            <a href="/udii-report" class="dropdown-item"><i class="fa fa-hotel"></i> УДиИ</a>
                        </div>
                    @elseif($roleId == 9 || $roleId == 23)
                        <div>
                            <a href="/reagent-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок по приготовлению реагентов</a>
                        </div>
                    @elseif($roleId == 10 || $roleId == 24 || $roleId == 26)
                        <div>
                            <a href="/fso-report" class="dropdown-item"><i class="fa fa-hotel"></i> ФСО</a>
                            <a href="/molibden-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок молибденовой селекции</a>

                        </div>
                    @elseif($roleId == 11 || $roleId == 25)
                        <div>
                            <a href="/presfilter-report" class="dropdown-item"><i class="fa fa-hotel"></i> Участок пресс-фильтров</a>
                        </div>
                    @elseif($roleId == 12 || $roleId == 27 || $roleId == 28)
                        <div>
                            <a href="/pns1a-report" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-1А</a>
                            <a href="/pns2a-report" class="dropdown-item"><i class="fa fa-hotel"></i> ПНС-2А</a>
                        </div>
                    @elseif($roleId == 13 || $roleId == 29)
                        <div>
                            <a href="/rmu-report" class="dropdown-item"><i class="fa fa-hotel"></i> РМУ</a>
                        </div>
                    @elseif($roleId == 14 || $roleId == 30)
                        <div>
                            <a href="/ec-report" class="dropdown-item"><i class="fa fa-hotel"></i> ЭЦ</a>
                        </div>
                    @elseif($roleId == 15 || $roleId == 16)
                        <div>
                            <a href="/asutp-report" class="dropdown-item"><i class="fa fa-hotel"></i> АСУТП</a>
                        </div>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>
