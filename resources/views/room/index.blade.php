@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.3/parsley.css">

    <style>
         .pencil {
            margin-left: 30px !important;
        }
        #room-index {
            z-index: 1;
        }

        #room-show {
            z-index: 2;
        }

        .viewed {
            background-color: #2f3e66 !important;
            color: #ffffff !important;
        }

    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>


    <script>
        $(document).ready(function () {
            $('#rooms-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/rooms/data',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'hotel', name: 'hotel'},
                    {data: 'room_floor', name: 'ru.room_floor'},
                    {data: 'room_numb', name: 'ru.room_numb'},
                    {data: 'tp', name: 'tp'},
                    {data: 'beds', name: 'beds'},
                    {data: 'tag', name: 'tag'},
                    {data: 'wifi', name: 'wifi'},
                    {data: 'tvset', name: 'tvset'},
                    {data: 'aircond', name: 'aircond'},
                    {data: 'freezer', name: 'freezer'},
                    {data: 'active', name: 'active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[0, 'asc']]
            });
        })

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
            Swal.fire('Сохранено!', '{{ session("success") }}', 'success');
            @endif
        });
    </script>

@endsection

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="user">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Xonalar</h4>
                        </div>

                        <div class="card-body">
                            <table class="table" id="rooms-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>НАЗВАНИЕ ГОСТИНИЦЫ</th>
                                    <th>ЭТАЖ</th>
                                    <th> №</th>
                                    <th> ТИП</th>
                                    <th><i class="fa fa-bed" title="Кол-во коек"></i></th>
                                    <th><i class="fa fa-tags" title="Тег"></i></th>
                                    <th><i class="fa fa-wifi" title="WiFi интернет"></i></th>
                                    <th><i class="fa fa-tv" title="Кабель.ТВ"></i></th>
                                    <th><i class="fa fa-fan" title="Кондиционер"></i></th>
                                    <th><i class="fa fa-door-closed" title="Холодильник"></i></th>
                                    <th>СТАТУС</th>
                                    <th><i class="fas fa-pencil-alt pencil"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="room-show"></div>
@endsection
