@extends('layouts.app')


@section('style')
    <style>

        .udate-button{
            margin-bottom: 27px !important;
            margin-left: 1400px !important;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 20px;
        }


        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .button-save{
            margin-top: 40px;
            margin-right: 400px !important;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 19px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #f8ac59;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #f8ac59;;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(20px);
            -ms-transform: translateX(20px);
            transform: translateX(20px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 30px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .form-control {
            border: 1px solid #6689ff
        }
        .table {
            width: 50% !important;
            margin-left: 300px !important;
        }
        label .text-left {
            font-weight: 700;
            text-transform: uppercase
        }
        #formRooms{
            margin-top: -20px !important;
        }

    </style>
@endsection
@section('script')

    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script>
        function updateData() {
            const formData = {
                id: {{$row->id}},
                id_room_type: $('#id_room_type').val(),
                room_numb: $('#room_numb').val(),
                room_floor: $('#room_floor').val(),
                beds: $('#beds').val(),
                wifi: $('#wifi').prop('checked') ? '1' : '0',
                tvset: $('#tvset').prop('checked') ? '1' : '0',
                aircond: $('#aircond').prop('checked') ? '1' : '0',
                freezer: $('#freezer').prop('checked') ? '1' : '0',
                tag: $('#tag').val(),
                active: $('#active').prop('checked') ? '1' : '0',
                _token: '{{ csrf_token() }}'
            };

            console.log(formData);



            $.ajax({
                url: "/rooms/save",
                method: "POST",
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        window.location.href = '/rooms'
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

    <div class="sbox">
        <div class="sbox sbox-content">
            <div>
                <ul class="parsley-error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <form id="roomsFormAjax" method="post"
                  action="{{asset('rooms/save' . (isset($row) ? '/' . cryptId($row->id) : ''))}}"
                  class="form-horizontal" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="id" value="{{isset($row) ? $row->id : ''}}">
                <input type="hidden" name="hotel_id" id="hotel_id" required="true" value="{{$hotel->id}}">
                <div class="col-md-12" id="formRooms">
                    <table class="table table-bordered">
                        <tr>
                            <td><label for="ГОСТИНИЦА:" class=" control-label label-view">ГОСТИНИЦА: </label></td>
                            <td><span class="label label-primary font-bold text-uppercase"> {{$hotel->name}} </span></td>
                        </tr>
                        <tr>
                            <td><label for="ТИП КОМНАТЫ:" class="control-label label-view"> ТИП КОМНАТЫ: <span
                                        class="asterix"> * </span></label></td>
                            <td>
                                <select class="form-select select2" name="id_room_type" id="id_room_type" required>
                                    @foreach($types as $t)
                                        <option class="text-uppercase"
                                                value="{{$t->id}}" {{$t->id == (isset($row) ? $row->id_room_type : "") ? 'selected' : ''}}> {{$t->name}} </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="НОМЕР №: " class="control-label label-view"> НОМЕР №: </label></td>
                            <td>
                                <input name="room_numb" class="form-control text-center" type="text" id="room_numb"
                                       maxlength="10" required value="{{(isset($row) ? $row->room_numb : '')}}"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ЭТАЖ: " class="control-label label-view"> ЭТАЖ: </label></td>
                            <td>
                                <input name="room_floor" class="form-control text-center" type="number" id="room_floor"
                                       required
                                       min="1" max="999" value="{{(isset($row) ? $row->room_floor : 0)}}"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="КОЛ-ВО КОЕК: " class="control-label label-view"> КОЛ-ВО
                                    КОЕК: </label></td>
                            <td>
                                <input name="beds" class="form-control text-center" type="number" id="beds" min="1" required
                                       max="9999" value="{{(isset($row) ? $row->beds : 0)}}"/>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ИНТЕРНЕТ: " class="form-label"> ИНТЕРНЕТ: *</label></td>
                            <td>
                                <div class="square-switch">
                                    <input type="checkbox" name="wifi" id="wifi" switch="none" @checked((isset($row) && $row->wifi*1 == 1)) />
                                    <label for="wifi" data-on-label="Bor" data-off-label="Yo‘q"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="КАБ.ТЕЛЕВИДЕНИЕ: " class="form-label"> КАБ.ТЕЛЕВИДЕНИЕ: *</label></td>
                            <td>
                                <div class="square-switch">
                                    <input type="checkbox" name="tvset" id="tvset" switch="none" @checked((isset($row) && $row->tvset*1 == 1)) />
                                    <label for="tvset" data-on-label="Bor" data-off-label="Yo‘q"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="КОНДИЦИОНЕР: " class="form-label"> КОНДИЦИОНЕР: *</label></td>
                            <td>
                                <div class="square-switch">
                                    <input type="checkbox" name="aircond" id="aircond" switch="none" @checked((isset($row) && $row->aircond*1 == 1)) />
                                    <label for="aircond" data-on-label="Bor" data-off-label="Yo‘q"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ХОЛОДИЛЬНИК: " class="form-label"> ХОЛОДИЛЬНИК: *</label></td>
                            <td>
                                <div class="square-switch">
                                    <input type="checkbox" name="freezer" id="freezer" switch="none" @checked((isset($row) && $row->freezer*1 == 1)) />
                                    <label for="freezer" data-on-label="Bor" data-off-label="Yo‘q"></label>
                                </div>
                            </td>
                        </tr>
                        @if(\Auth::user()->hasRole(['super-admin','Сисадмины','Оператор KABUS','БУХГАЛТЕРЫ KABUS']))
                            <tr>
                                <td><label for="СТАТУС: " class="control-label text-bold text-red"> <strong>СТАТУС:
                                            <span class="asterix"> * </span></strong></label></td>
                                <td>
                                    <div class="square-switch">
                                        <input type="checkbox" name="active" id="active"
                                               switch="none" @checked((isset($row) && $row->active*1 == 1)) />
                                        <label for="active" data-on-label="Aktiv" data-off-label="Yo‘q"></label>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td><label for="ТЕГ" class="control-label label-view"> ТЕГ: </label></td>
                            <td>
                                <input name="tag" class="form-control text-left" type="text" id="tag"
                                       value="{{(isset($row) ? $row->tag : '')}}"/>
                            </td>
                        </tr>
                    </table>
                <div class="udate-button">
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="updateData()">Изменить</button>
                    <a class="btn btn-danger" href="rooms/">Отмена</a>
                </div>
                </div>
            </form>
        </div>
    </div>

@endsection

