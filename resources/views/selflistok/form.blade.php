@extends('layouts.app')
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@section('script')

    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/inputmask/inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alerts.init.js') }}"></script>,

    <script>


        function UpdateSelectList(data,domid,selid){
            $(domid).append('<option value="" ' + (selid == -1 ? ' selected': '') + '> --- НЕ ВЫБРАНО ---</option>');
            $.each(data,function(key, value){
                $(domid).append('<option value="'+value[0]+'" '+(selid==value[0]?' selected':'')+'>'+value[1]+'</option>');});
            $(domid).trigger('change');
        }

        $.getJSON("{!! url('lisok/combo-select?filter=tb_citizens:id:sp_name04') !!}",function(json){
            UpdateSelectList(json,'#id_country',{{ !empty($row["id_country"]) ? $row["id_country"] : -1}});
            UpdateSelectList(json,'#id_citizen',{{ !empty($row["id_citizen"]) ? $row["id_citizen"] : -1}});
            UpdateSelectList(json,'#id_countryfrom',{{ !empty($row["id_countryFrom"]) ? $row["id_countryFrom"] : -1}});
        });
        $.getJSON("{!! url('lisok/combo-select?filter=visittype:id:name') !!}",function(json){
            UpdateSelectList(json,'#id_visitType',{{ isset($row["id_visitType"]) ? $row["id_visitType"]:'' }});
        });
        $.getJSON("{!! url('lisok/combo-select?filter=visa:id:name') !!}",function(json){
            UpdateSelectList(json,'#id_visa',{{ isset($row["id_visa"]) ? $row["id_visa"]:'' }});
        });
        $.getJSON("{!! url('lisok/combo-select?filter=guests:id:guesttype') !!}",function(json){
            UpdateSelectList(json,'#id_guest',{{ isset($row["id_guest"]) ? $row["id_guest"]:'' }});
        });




        function tab01() {
            $.ajax({
                url: '/listok/check',
                type: 'POST',
                data: {
                    citezenID: $('#id_citizen').val(),
                    pspType: $('input[name=id_passporttype]:checked').val(),
                    dtb: $('#datebirth1').val(),
                    passportNumber: $('#passport').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data.person.birth_date);
                    if (data.status === 'success') {
                        const birthDate = new Date(data.person.birth_date);
                        const formattedDate = birthDate.toISOString().split('T')[0];
                        $('#datebirth2').val(formattedDate);
                        $('#lastname').val(data.person.lastname);
                        $('#firstname').val(data.person.firstname);
                        $('#surname').val(data.person.surname);
                        if (data.person.sex === 1) {
                            $('input[name="id_passporttype"][value="5"]').prop('checked', true);
                        } else if (data.person.sex === 2) {
                            $('input[name="id_passporttype"][value="6"]').prop('checked', true);
                        }
                        $('a[href="#profile1"]').tab('show');
                        $('a[href="#profile1"]').removeClass('disabled');
                        $('a[href="#messages1"]').addClass('disabled');
                        $('a[href="#childdata"]').addClass('disabled');
                        $('a[href="#settings1"]').addClass('disabled');


                    } else {
                        $('#checking').val('0');
                        $('#datebirth').prop('disabled', false);
                        $('#passport').prop('disabled', false);
                        $('#flag_ctz').hide();
                    }
                },
                error: function(xhr) {
                    console.error(xhr);

                }
            });
        }

        function tab02() {
            $('a[href="#messages1"]').removeClass('disabled');
            $('a[href="#messages1"]').tab('show');


        }

        function tab03() {
            $('a[href="#childdata"]').removeClass('disabled');
            $('a[href="#childdata"]').tab('show');

        }

        function tab04() {
            $('a[href="#settings1"]').removeClass('disabled');
            $('a[href="#settings1"]').tab('show');
        }



        function returnToHomeTab() {
            $('a[href="#home1"]').tab('show');
            $('a[href="#profile1"]').addClass('disabled');
            $('a[href="#messages1"]').addClass('disabled');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab02() {
            $('a[href="#profile1"]').tab('show');
            $('a[href="#messages1"]').addClass('disabled');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab03() {
            $('a[href="#messages1"]').tab('show');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab04() {
            $('a[href="#childdata"]').tab('show');
            $('a[href="#settings1"]').addClass('disabled');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dateVisitOn = document.getElementById('datevisiton');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const hours = String(today.getHours()).padStart(2, '0');
            const minutes = String(today.getMinutes()).padStart(2, '0');
            dateVisitOn.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        });


        function saveProfileData() {
            const data = {
                pinfl: $('#pinfl').val(),
                passportissuedby: $('#passportissuedby').val(),
                surname: $('#surname').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                id_country: $('#id_country').val(),
                id_countryfrom: $('#id_countryfrom').val(),
                id_passporttype: $('input[name="id_passporttype"]:checked').val(),
                datevisiton: $('#datevisiton').val(),
                _token: '{{ csrf_token() }}'
            };


            $.ajax({
                url: '/listok/savedata',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = '/selflistok'
                    } else {
                        Swal.fire('Ошибка', 'Произошла ошибка', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire('Ошибка', 'Произошла ошибка при сохранении данных', 'error');
                }
            });
        }


    </script>


@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="user">
                    <div class="card-header d-flex justify-content-between">
                        <h4><b>Листок прибытие</b></h4>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home1" onclick="returnToHomeTab()" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Поиск гостя</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#profile1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Основная информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#messages1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Доп. информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#childdata" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о детях</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#settings1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о госте</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">


                                <input name="id" type="hidden" value="">
                                <input type="hidden" name="person_id" id="person_id">
                                <input type="hidden" id="checking">

                                <div>
                                    <div class="row col-lg-6">
                                        <div class="col-lg-12">
                                            <div class="mt-1">
                                                <label for="basicpill-firstname-input">ГРАЖДАНСТВО:</label>
                                                <select id="id_citizen" name="id_citizen" class="form-control form-select" required></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 ">
                                            <div class="mb-3">
                                                <label for="basicpill-lastname-input">ТИП ДОКУМЕНТА:</label><br>
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio1" autocomplete="off" checked value="1">
                                                    <label class="btn btn-outline-primary" for="btnradio1">Паспорт</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio2" autocomplete="off"  value="2">
                                                    <label class="btn btn-outline-primary" for="btnradio2">Воен.удостоверение</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio3" autocomplete="off"  value="3">
                                                    <label class="btn btn-outline-primary" for="btnradio3">Другой документ</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio4" autocomplete="off"  value="4">
                                                    <label class="btn btn-outline-primary" for="btnradio4">ID карта</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="datebirth1">ДАТА РОЖДЕНИЕ:</label>
                                                <input class="form-control" data-date-format='dd-mm-yyyy' type="date" name="datebirth1" value="" id="datebirth1">
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="mb-3">
                                                <label for="passport">ПАСПОРТ СЕРИЯ|НОМЕР:</label>
                                                <input type="text" class="form-control" id="passport" name="passport" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab01()" id="sa-position">Проверить</button>
                                            </div>
                                        </div>


                                    </div>
                                </div>


                            </div>
                            <div class="tab-pane" id="profile1" role="tabpanel">
                                <section>
                                    <form>
                                        <div class="row col-lg-8">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="pinfl">ПИНФЛ:</label>
                                                    <input class="form-control" name="pinfl" type="text" maxlength="14" id="pinfl" value="">
                                                </div>
                                            </div><!-- end col-lg-6 -->

                                        </div><!-- end row -->
                                        <div class="row col-lg-8">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="passportissuedby">ПАСПОРТ ВЫДАН:</label>
                                                    <input type="text" class="form-control" id="passportissuedby" name="passportissuedby" placeholder="">
                                                </div>
                                            </div><!-- end col-lg-6 -->

                                            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                                                <div class="col-lg-3">
                                                    <label class="form-label" for="surname">ФАМИЛИЯ:</label>
                                                    <input type="text" id="surname" name="surname" class="form-control" placeholder="" />
                                                </div>

                                                <div class="col-lg-3">
                                                    <label class="form-label" for="firstname">ИМЯ:</label>
                                                    <input type="email" id="firstname" name="firstname" class="form-control" placeholder="" />
                                                </div>

                                                <div class="col-lg-3">
                                                    <label class="form-label" for="lastname">ОТЧЕСТВО:</label>
                                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="" />
                                                </div>

                                            </div><!-- end col-lg-6 -->
                                        </div><!-- end row -->
                                        <div class="row col-lg-8">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="basicpill-companyuin-input">СТРАНА РОЖДЕНИЯ:</label>
                                                    <select name='id_country' rows='5' id='id_country' class='select2 form-select' required></select>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="id_countryfrom">СТРАНА ОТКУДА ПРИБЫЛ:</label>
                                                    <select name='id_countryfrom' rows='5' id='id_countryfrom' class='select2 form-select' required></select>
                                                </div>
                                            </div><!-- end col-lg-6 -->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="basicpill-lastname-input">ПОЛ:</label><br>
                                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="id_passporttype" id="btnradio5" autocomplete="off" checked value="5">
                                                        <label class="btn btn-outline-primary" for="btnradio5">Мужчина</label>
                                                        <input type="radio" class="btn-check" name="id_passporttype" id="btnradio6" autocomplete="off"  value="6">
                                                        <label class="btn btn-outline-primary" for="btnradio6">Женщина</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="datevisiton">ДАТА ПРИБЫТИЯ</label>
                                                    <input class="form-control inputmaskDate" data-date-format='dd-mm-yyyy' name="datevisiton" type="datetime-local" required id="datevisiton">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <div class="m-4">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab02()">Далее</button>
                                                    <a class="btn btn-danger" onclick="returnToHomeTab()">Отмена</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </section>
                            </div>
                            <div class="tab-pane" id="messages1" role="tabpanel">

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="room_number">Номер / Комната:</label>
                                        <input type="text" id="room_number" name="room_number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="stay_days">На сколько дней прибыл:</label>
                                        <input type="number" id="stay_days" name="stay_days" class="form-control">
                                    </div>
                                </div>


                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="visit_type">Тип визита:</label>
                                        <select id="visit_type" name="visit_type" class="form-control">
                                            <option value="Образование">Образование</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="visa_type">Тип визы:</label>
                                        <select id="visa_type" name="visa_type" class="form-control">
                                            <option value="">-- НЕ ВЫБРАНО --</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="kpp_number">КПП №:</label>
                                        <input type="text" id="kpp_number" name="kpp_number" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="kpp_date">Дата заезда КПП:</label>
                                        <input type="date" id="kpp_date" name="kpp_date" data-date-format='dd-mm-yyyy' class="form-control" value="yyyy-mm-dd">
                                    </div>
                                </div>


                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="payment_status">Статус оплаты:</label>
                                        <select id="payment_status" name="payment_status" class="form-control">
                                            <option value="">-- НЕ ВЫБРАНО --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="payment_amount">Сумма оплаты:</label>
                                        <input type="number" id="payment_amount" name="payment_amount" class="form-control" value="0">
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="guest_type">Тип гостя:</label>
                                        <select id="guest_type" name="guest_type" class="form-control">
                                            <option value="Другое">Другое</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8 d-flex justify-content-end">
                                    <div class="m-4">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab03()">Далее</button>
                                        <a class="btn btn-danger" onclick="returnToTab02()">Отмена</a>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="childdata" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form class="repeater" enctype="multipart/form-data">
                                                    <div data-repeater-list="group-a">
                                                        <div data-repeater-item class="row">
                                                            <div  class="mb-3 col-lg-4">
                                                                <label class="form-label" for="name">ФИО РЕБЁНКА:</label>
                                                                <input type="text" id="name" name="untyped-input" class="form-control" placeholder="" />
                                                            </div>

                                                            <div  class="mb-3 col-lg-4">
                                                                <label class="form-label" for="datebirth">ДАТА РОЖДЕНИЕ:</label>
                                                                <input class="form-control" data-date-format='dd-mm-yyyy' type="date" name="datebirth" value="" id="datebirth">
                                                            </div>

                                                            <div  class="mb-3 col-lg-3">
                                                                <label class="form-label" for="gender">ПОЛ:</label>
                                                                <select id="gender" class="form-control">
                                                                    <option value="" disabled selected>Выберите пол</option>
                                                                    <option value="Мальчик">Мальчик</option>
                                                                    <option value="Девочка">Девочка</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-lg-1 mt-2 align-self-center">
                                                                <button data-repeater-delete type="button" class="btn btn-outline-danger rounded">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mt-lg-0" value="Добавить"/>

                                                    <div class="col-12 d-flex justify-content-end">
                                                        <div class="m-5">
                                                            <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab04()">Далее</button>
                                                            <a class="btn btn-danger" onclick="returnToTab03()">Отмена</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane" id="settings1" role="tabpanel">
                                <div class="form-group">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#info">Информация</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#reviews">Отзывы <span class="badge badge-success">0</span></a>
                                        </li>
                                    </ul>

                                    <div class="tab-content mt-3">
                                        <div id="info" class="tab-pane fade show active">
                                            <p>Информация о госте</p>
                                        </div>
                                        <div id="reviews" class="tab-pane fade">
                                            <p>Отзывы гостя</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="saveProfileData()">Сохранить</button>
                                <a class="btn btn-danger" onclick="returnToTab04()">Отмена</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection

