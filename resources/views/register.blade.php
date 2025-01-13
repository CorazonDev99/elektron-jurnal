@extends('layouts.app')

@section('style')
    <style>
        table {
            border-collapse: collapse;
            /*width: 100%;*/
        }

        td {
            border: 1px solid black;
            padding: 8px;
            cursor: pointer;
        }

        td.selected {
            background-color: lightblue;
        }
        td.listok {
            background-color: lightcoral;
        }

        tr.swiping {
            user-select: none;
        }

        #swipeTable{
            border: 1px solid #000
        }
        tr.swipe th{
            border: 1px solid #000;
            background-color: #f0f0f0;
        }

        table#swipeTable td{
            width: 40px;
            height: 30px;
        }

    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

    <style>.daterangepicker {z-index: 9999;}</style>


    <script>
        function regListok() {
            var selected = $('.selected');
            // var date = selected.attr('data-day') + '.' + selected.attr('data-month') + '.' + selected.attr('data-year');
            // get min and max selected dates
            var dates = [];
            var room_name;
            var room;
            var type_name;
            selected.each(function() {
                dates.push($(this).attr('data-day') + '.' + $(this).attr('data-month') + '.' + $(this).attr('data-year'));
                room_name = $(this).attr('data-room_name');
                room = $(this).attr('data-room');
                type_name = $(this).attr('data-type_name');
            });
            var minDate = dates.reduce(function(a, b) {
                return a < b ? a : b;
            });
            var maxDate = dates.reduce(function(a, b) {
                return a > b ? a : b;
            });
            console.log(minDate, maxDate);

            $.confirm({
                title: 'Регистрация гостя в номер ' + room_name + ' (' + type_name + ') на период с ' + minDate + ' по ' + maxDate,
                // input fields for the form get url lisok/form
                content: 'url:lisok/form',
                type: 'blue',
                columnClass: 'col-lg-12',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Регистрация',
                        btnClass: 'btn-blue',
                        action: function () {
                            var form = this.$content.find('form');
                            var data = form.serialize();
                            data += '&_token=' + '{{ csrf_token() }}';
                            data += '&datevisiton=' + minDate;
                            data += '&datevisitoff=' + maxDate;
                            data += '&room=' + room;
                            $.ajax({
                                url: '{{ asset('lisok/save') }}',
                                method: 'POST',
                                data: data,
                                success: function (response) {
                                    if (response.status == 'success') {
                                        $.alert('Гость успешно зарегистрирован');
                                        selected.removeClass('selected');
                                        selected.addClass('lisok');
                                    } else {
                                        $.alert('Ошибка регистрации');
                                    }
                                }
                            });
                        }
                    },
                    close: {
                        text: 'Закрыть',
                        btnClass: 'btn-blue',
                        action: function () {
                        }
                    }
                }
            });


        }

        function cb(start, end) {
            $('#rgsrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $('#bookTable').load('{{ asset('lisok/book-table') }}?start=' + start.format('YYYY-MM-DD') + '&end=' + end.format('YYYY-MM-DD'));
        }
        $('#rgsrange').daterangepicker({
            showDropdowns: true, // Additional options you may want to include
            minDate: moment().subtract(1, 'day'), // Example: Minimum selectable date
            maxDate: moment().add(2, 'month'), // Example: Maximum selectable date
            range: {
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'За последнюю неделю': [moment().subtract(6, 'days'), moment()],
                'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                'Последние 45 дней': [moment().subtract(44, 'days'), moment()],
                'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Текущий год': [moment().startOf('year'), moment()],
                'Весь период': ['01/01/1970', moment()],
            }

        }, cb);
        cb(moment().subtract(1, 'day'), moment().add(1, 'month'));
    </script>

@endsection

@section('content')

    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Регистрация гостья</h4>
                    <div id="rgsrange" class="form-control"
                         style="background: #fff; cursor: pointer; border: 1px solid #55acee;display:inline!important; width: auto;">
                        <i class="fa fa-calendar"></i>&nbsp;<span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="bookTable" style="width: 100%; overflow-x: scroll">
                    </div>
                </div>
            </div>

        </div>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@endsection
