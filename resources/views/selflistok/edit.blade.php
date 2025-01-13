@extends('layouts.app')
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@section('script')

    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script defer>
        function UpdateSelectList(data, domid, selid, countryName) {
            $(domid).html('<option value="" selected> --- НЕ ВЫБРАНО ---</option>');
            let matchedId = null;
            $.each(data, function(key, value) {
                if (countryName && value[1] === countryName) {
                    matchedId = value[0];
                }
                $(domid).append('<option value="' + value[0] + '">' + value[1] + '</option>');
            });

            if (matchedId) {
                $(domid).val(matchedId);
            } else if (selid !== -1) {
                $(domid).val(selid);
            }

            $(domid).trigger('change');
        }

        $.getJSON("{!! url('lisok/combo-select?filter=tb_citizens:id:sp_name04') !!}", function(json){
            UpdateSelectList(json, '#id_country', {{ !empty($row["id_country"]) ? $row["id_country"] : -1}}, '{{$rowdata->sp_name04}}');
        });

        $.getJSON("{!! url('lisok/combo-select?filter=tb_citizens:id:sp_name04') !!}", function(json){
            UpdateSelectList(json, '#id_countryfrom', {{ !empty($row["id_countryFrom"]) ? $row["id_countryFrom"] : -1}}, '{{$rowdata->sp_name04}}');
        });

        $.getJSON("{!! url('lisok/combo-select?filter=tb_visittype:id:name_uz') !!}", function(json){
            UpdateSelectList(json, '#id_visitType', {{ !empty($row["id_visitType"]) ? $row["id_visitType"] : -1}}, '{{$rowdata->name_uz}}');
        });

        $.getJSON("{!! url('lisok/combo-select?filter=tb_visa:id:name') !!}", function(json){
            UpdateSelectList(json, '#id_visa', {{ !empty($row["id_visa"]) ? $row["id_visa"] : -1}}, '{{$rowdata->visa_name}}');

        });

        $.getJSON("{!! url('lisok/combo-select?filter=tb_guests:id:guesttype_uz') !!}", function(json){
            UpdateSelectList(json, '#id_guest', {{ !empty($row["id_guest"]) ? $row["id_guest"] : -1}}, '{{$rowdata->guesttype_name}}');

        });


        function tab02() {
            $('a[href="#messages1"]').tab('show');
        }
        function tab03() {
            $('a[href="#childdata"]').tab('show');
        }
        function tab04() {
            $('a[href="#settings1"]').tab('show');
        }


        function returnToTab01() {
            $('a[href="#home1"]').tab('show');
        }

        function returnToTab02() {
            $('a[href="#messages1"]').tab('show');
        }

        function returnToTab03() {
            $('a[href="#childdata"]').tab('show');
        }

        function updateData() {
            const pol = $("input[name='pol']:checked").val();
            const sex = pol == "5" ? 'M' : pol == "6" ? 'W' : '';

            const formData = {
                id:{{$rowdata->id}},
                passportissuedby: $('#passportissuedby').val(),
                surname: $('#surname').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                id_country: Number($('#id_country').val()),
                id_countryfrom: Number($('#id_countryfrom').val()),
                sex: sex,
                datevisiton: $('#datevisiton').val(),
                stay_days: Number($('#stay_days').val()),
                id_visitType: Number($('#id_visitType').val()),
                id_visa: Number($('#id_visa').val()),
                kpp_number: $('#kpp_number').val(),
                kpp_date: $('#kpp_date').val(),
                payment_amount: $('#payment_amount').val(),
                id_guest: Number($('#id_guest').val()),
                _token: '{{ csrf_token() }}'
            };


            $.ajax({
                url: "/selflistok/update",
                method: "POST",
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        window.location.href = '/selflistok'
                    } else {
                        Swal.fire('Ошибка', 'Произошла ошибка при обновлении данных', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Ошибка', 'Произошла ошибка при отправке данных', 'error');
                }
            });
        }
    </script>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/inputmask/inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alerts.init.js') }}"></script>

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
                                <a class="nav-link" data-bs-toggle="tab" href="#home1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Основная информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#messages1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Доп. информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#childdata" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о детях</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#settings1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о госте</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <section>
                                    <form>
                                        <div class="row col-lg-8">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="passportissuedby">ПАСПОРТ ВЫДАН:</label>
                                                    <input type="text" class="form-control" id="passportissuedby" name="passportissuedby" placeholder="" value="{{$rowdata->datePassport}}">
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                                                <div class="col-lg-3">
                                                    <label class="form-label" for="surname">ФАМИЛИЯ:</label>
                                                    <input type="text" id="surname" name="surname" class="form-control" placeholder="" value="{{$rowdata->surname}}"/>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label class="form-label" for="firstname">ИМЯ:</label>
                                                    <input type="email" id="firstname" name="firstname" class="form-control" placeholder="" value="{{$rowdata->firstname}}"/>
                                                </div>

                                                <div class="col-lg-3">
                                                    <label class="form-label" for="lastname">ОТЧЕСТВО:</label>
                                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="" value="{{$rowdata->lastname}}"/>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row col-lg-8">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="basicpill-companyuin-input">СТРАНА РОЖДЕНИЯ:</label>
                                                    <select name='id_country' id='id_country' class='select2 form-select' required></select>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="id_countryfrom">СТРАНА ОТКУДА ПРИБЫЛ:</label>
                                                    <select name='id_countryfrom' rows='5' id='id_countryfrom' class='select2 form-select' required></select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="basicpill-lastname-input">ПОЛ:</label><br>
                                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="pol" id="btnradio5" autocomplete="off" value="5" {{ $rowdata->sex == 'M' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-primary" for="btnradio5">Мужчина</label>
                                                        <input type="radio" class="btn-check" name="pol" id="btnradio6" autocomplete="off" value="6" {{ $rowdata->sex == 'W' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-primary" for="btnradio6">Женщина</label>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="datevisiton">ДАТА ПРИБЫТИЯ</label>
                                                    <input class="form-control inputmaskDate" data-date-format='dd-mm-yyyy' name="datevisiton" type="datetime-local" required id="datevisiton" value="{{$rowdata->dateVisitOn}}">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <div class="m-4">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab02()">Далее</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </section>
                            </div>
                            <div class="tab-pane" id="messages1" role="tabpanel">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="stay_days">На сколько дней прибыл:</label>
                                        <input type="number" id="stay_days" name="stay_days" class="form-control" value="{{$rowdata->wdays}}">
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="id_visitType">Тип визита:</label>
                                        <select id="id_visitType" name="id_visitType" class="select2 form-select"></select>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="id_visa">Тип визы:</label>
                                        <select id="id_visa" name="id_visa" class="select2 form-select"></select>
                                    </div>
                                </div>


                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="kpp_number">КПП №:</label>
                                        <input type="text" id="kpp_number" name="kpp_number" class="form-control" value="{{$rowdata->kppNumber}}">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="kpp_date">Дата заезда КПП:</label>
                                        <input type="date" id="kpp_date" name="kpp_date" data-date-format='dd-mm-yyyy' class="form-control" value="{{$rowdata->dateKPP}}">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="payment_amount">Сумма оплаты:</label>
                                        <input type="number" id="payment_amount" name="payment_amount" class="form-control" value="{{$rowdata->summa}}">
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="guest_type">Тип гостя:</label>
                                        <select id="id_guest" name="id_guest" class="select2 form-select"></select>
                                    </div>
                                </div>

                                <div class="col-8 d-flex justify-content-end">
                                    <div class="m-4">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab03()">Далее</button>
                                        <a class="btn btn-danger" onclick="returnToTab01()">Отмена</a>
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
                                                                <input type="text" id="name" name="untyped-input" class="form-control" placeholder="" value="{{$rowdata->children_name}}"/>
                                                            </div>

                                                            <div  class="mb-3 col-lg-4">
                                                                <label class="form-label" for="datebirth">ДАТА РОЖДЕНИЕ:</label>
                                                                <input class="form-control" data-date-format='dd-mm-yyyy' type="date" name="datebirth" value="{{$rowdata->children_dateBirth}}" id="datebirth">
                                                            </div>

                                                            <div class="mb-3 col-lg-3">
                                                                <label class="form-label" for="gender">ПОЛ:</label>
                                                                <select id="gender" class="form-control">
                                                                    <option value="" disabled selected>Выберите пол</option>
                                                                    <option value="Мальчик" {{ $rowdata->gender == 'M' ? 'selected' : '' }}>Мальчик</option>
                                                                    <option value="Девочка" {{ $rowdata->gender == 'W' ? 'selected' : '' }}>Девочка</option>
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
                                                            <a class="btn btn-danger" onclick="returnToTab02()">Отмена</a>
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
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="updateData()">Изменить</button>
                                <a class="btn btn-danger" onclick="returnToTab03()">Отмена</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

