@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">


    <style>
        #custom-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .loading-overlay{
            margin-left: 20px !important;
            margin-bottom: 20px !important;
        }
        .card {
            margin-bottom: 5px !important;
        }
        .line-confirm{
            background: #00a7d0;
        }
        .jconfirm .jconfirm-buttons{
            margin-right: 100px !important;
        }

        .jconfirm .jconfirm-title {
            margin-left: 20px;
            margin-top: 20px;
        }

        .confirm{
            margin-top: 20px !important;
            margin-left:60px!important;
            width: 90%;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .css-label {
            font-size: 16px;
            margin: 0;
        }

        .css-checkbox {
            margin: 0;
        }

        .no_reviews{
            margin-top: 300px;
        }
        #filter{
            margin-left: 8px !important;

        }
        #checkout {
            margin-left: 8px !important;
        }

        #details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
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

        .dataTables_scrollBody {
            overflow-y: auto;
            height: 600px;
        }


        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: inline-block;
            vertical-align: middle;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
        }

        .card-nav {
            margin: 20px !important;

        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: inline-block;
            margin-right: 15px;
            vertical-align: middle;
        }

        .dataTables_paginate {
            white-space: nowrap;
        }

        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate {
            width: auto;
        }

        #listok-table{
            cursor: pointer;
        }
        .data-tr{
            text-align: left;
        }

        .modal {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: block;
            opacity: 1;
        }
    </style>
    <style>
        .btn-color{
            color: #000000 !important;
        }
        .popup__add {
            display: block;
            position: fixed;
            right: 0;
            top: 0;
            width: 40%;
            height: 100vh;
            z-index: 9999;
            background: linear-gradient(to right, rgba(0, 167, 208, 0.05), rgba(107, 233, 255, 0.05));
            box-shadow: -4px 0 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            transform: translateX(100%);
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
            color: #000000;
            border-left: 6px solid rgba(0, 95, 115, 0.3);
            border-radius: 10px 0 0 10px;
            opacity: 0.9;
        }

        .popup__add.true {
            transform: translateX(0);
        }

        .popup__add .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
            height: 90%;
            margin: 5%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .popup__add .card-header {
            background: linear-gradient(45deg, rgba(0, 95, 115, 0.7), rgba(10, 147, 150, 0.7));
            color: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            font-size: 1.6rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px 15px 0 0;
        }

        .popup__add .card-header button {
            background: rgba(255, 255, 255, 0.6);
            border: none;
            color: rgba(0, 95, 115, 0.9);
            font-size: 2rem;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 0.3s ease, transform 0.2s;
        }

        .popup__add .card-header button i {
            color: rgba(0, 95, 115, 0.9); /* Цвет иконки */
        }

        .popup__add .card-header button:hover {
            background: rgba(255, 0, 11, 0.8);
            color: #fff;
            transform: scale(1.1);
        }

        /* Для использования Font Awesome */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');


        .popup__add .card-body {
            padding: 2rem;
            overflow-y: auto;
            color: rgb(0, 0, 0);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .popup__add .card-body p {
            padding-left: 3rem;
            margin: 0.8rem 4px;
            line-height: 1.7;
            font-size: 1.1rem;
            flex: 1 1 48%;
        }

        .popup__add .card-body p strong {
            font-weight: bold;
            color: rgba(0, 95, 115, 0.8);
        }

        .popup__add .card-body img {
            margin-right: 10px;
            vertical-align: middle;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgb(0, 0, 0);
        }

        .popup__add .card-footer {
            background: rgba(0, 95, 115, 0.6); /* Прозрачный футер */
            padding: 1.2rem;
            border-radius: 0 0 15px 15px;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            font-size: 1rem;
        }

        .width__100 {
            width: 100vw;
        }
        .width__25 {
            width: 25vw;
        }
        .width__50 {
            width: 50vw;
        }
        .width__75 {
            width: 75vw;
        }
        .hot_key{
            border: 1px solid #ccc;
            border-radius: .3rem;
            padding: 0 .2rem;
            margin-left: .2rem;
        }

        @media  (max-width: 700px) {
            .width__100 {
                width: 100vw;
            }
            .width__25 {
                width: 100vw;
            }
            .width__50 {
                width: 100vw;
            }
            .width__75 {
                width: 100vw;
            }
        }
    </style>
    <style>
        .rooms{
            margin-left: 20px !important;
        }
        .jconfirm-box{
            width: 600px ;
            padding-bottom: 20px ;
        }


        #icon-cont{
            margin-right: 10px;
        }

        #context-menu {
            position: absolute;
            display: none;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 4px;
        }


        #context-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #context-menu ul li {
            padding: 10px;
            cursor: pointer;
        }

        #context-menu ul li:hover {
            background-color: #f0f0f0;
        }

    </style>
@endsection

@section('header_title', 'ДЦ-1')

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/js/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>




    <script>
        var userId = @json(auth()->id());
        var roleId = @json($roleId);
    </script>


    <script>
        $(document).ready(function () {

            var table = $('#lisok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/ec/data',
                    data: function(d) {
                        let globalFilters = $('#global-search-form').serializeArray();
                        globalFilters.forEach(function(filter) {
                            d[filter.name] = filter.value;
                        });
                    }
                },
                columns: [
                    {
                        data: 'empty',
                        orderable: false,
                        searchable: false,
                        className: 'select-checkbox',
                        width: '10px',
                        defaultContent: ''
                    },
                    {data: 'id', name: 'ec.id', searchable: false, visible: false},
                    {data: 'created_at', name: 'created_at', sClass: 'dt-center', width: '70px'},
                    {data: 'equipment', name: 'ec.equipment', sClass: 'dt-center'},
                    {data: 'problem', name: 'ec.problem', searchable: false, sClass: 'dt-center'},
                    {data: 'solution', name: 'ec.solution', searchable: false, sClass: 'dt-center'},
                    {data: 'responsible_person', name: 'ec.responsible_person', sClass: 'dt-center'},
                    {data: 'deadline', name: 'deadline', width: '100px', searchable: false, sClass: 'dt-center'},
                    {
                        data: 'acknowledgment',
                        render: function (data, type, row) {
                            if (data === 1) {
                                return '<i class="fas fa-check-circle text-success"></i><br><small>' + row.updated_at + '</small>';
                            } else {
                                return '<i class="fas fa-clock text-warning"></i>';
                            }
                        },
                        sClass: 'dt-center'
                    },
                    {data: 'resolved', name: 'ec.resolved', sClass: 'dt-center'},
                    {data: 'employee_name', name: 'ec.employee_name', searchable: false, sClass: 'dt-center'},
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {

                            return `
                            <div class="d-flex">
                                ${userId === row.user_id && roleId['role_id'] !== 1 ?
                                `<button class="btn btn-success btn-sm edit-btn me-2" id="solved" data-id="${row.id}"><i class="fas fa-plus"></i></button>`
                                : ''}


                                ${roleId['role_id'] === 1 ?
                                `<button class="btn btn-success btn-sm edit-btn me-2" id="success" data-id="${row.id}"><i class="fas fa-clipboard-check"></i></button>`
                                : ''}

                                ${userId === row.user_id && row.acknowledgment === 0 && roleId['role_id'] !== 1 ?
                                `<button class="btn btn-success btn-sm edit-btn" id="edit" data-id="${row.id}"><i class="fas fa-edit"></i></button>`
                                : ''}
                                </div>
                        `;
                        },
                        sClass: 'dt-center'
                    }

                ],
                select: {
                    style: 'multi',
                    info: true
                },
                responsive: true,
                pageLength: 25,
                autoWidth: false,
                scrollX: true,
                order: [[2, 'asc']],
                dom: 'rt<"bottom"ilp><"clear">',
                "language": {
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Показано с _START_ по _END_ из _TOTAL_ записей",
                    "search": "Поиск:",
                    "paginate": {
                        "first": "Первый",
                        "last": "Последний",
                        "next": "Следующий",
                        "previous": "Предыдущий"
                    },
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoEmpty": "Нет доступных записей",
                    "zeroRecords": "Записи не найдены"
                },
                initComplete: function () {
                    $('.dataTables_length').appendTo('.dataTables_wrapper');
                    $('.dataTables_info').appendTo('.dataTables_wrapper');
                }
            });
            table.on('preXhr.dt', function() {
                $('#lisok-table-container').addClass('loading');
                $('#custom-loading').fadeIn();
            });

            table.on('xhr.dt', function() {
                $('#lisok-table-container').removeClass('loading');
                $('#custom-loading').fadeOut();
            });

            $(document).on('click', '#success', function () {
                var recordId = $(this).data('id');
                Swal.fire({
                    title: 'Вы уверены, что ознакомлени с выбранным элементам?',
                    text: "Элемент будут ознакомлен!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Потвердить',
                    cancelButtonText: 'Отмена',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/ec/success-record',
                            type: 'POST',
                            data: {
                                id: recordId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Данные подтверждено!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    table.ajax.reload();

                                } else {
                                    alert('Ошибка при обновлении!');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Ошибка сервера');
                            }
                        });
                    }

                });
            });

            $(document).on('click', '#edit', function () {
                var rowData = table.row($(this).closest('tr')).data();
                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }

                $.confirm({
                    title: 'Изменение запроса',
                    type: 'blue',
                    content: '' +
                        '<form id="editRequestForm">' +
                        `<input type="hidden" name="id" value="${rowData.id}">` +
                        '<div class="form-group">' +
                        '<label>ФИО сотрудника</label>' +
                        `<input type="text" name="fullname" class="form-control" value="${rowData.employee_name}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Оборудование</label>' +
                        `<input type="text" name="equipment" class="form-control" value="${rowData.equipment}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Проблема</label>' +
                        `<textarea name="problem" class="form-control" required>${rowData.problem}</textarea>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Решение</label>' +
                        `<textarea name="solution" class="form-control" required>${rowData.solution}</textarea>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Ответственный</label>' +
                        `<input type="text" name="responsible" class="form-control" value="${rowData.responsible_person}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Срок</label>' +
                        `<input type="date" name="deadline" class="form-control" value="${rowData.deadline}" required>` +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Изменить',
                            btnClass: 'btn-success',
                            action: function () {
                                let formData = $('#editRequestForm').serialize();
                                $.ajax({
                                    url: '/ec/edit-record',
                                    type: 'POST',
                                    data: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        if (response.status === true) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Данные успешно обновлены!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                            table.ajax.reload();
                                        } else {
                                            $.alert('Ошибка при обновлении данных!');
                                        }
                                    },
                                    error: function () {
                                        $.alert('Ошибка сервера!');
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Отмена',
                            btnClass: 'btn-danger'
                        }
                    }
                });
            });


            $(document).on('click', '#solved', function () {
                var rowData = table.row($(this).closest('tr')).data();
                console.log(rowData);
                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }

                $.confirm({
                    title: 'Добавить решение',
                    type: 'blue',
                    content: '' +
                        '<form id="soldevRequestForm">' +
                        `<input type="hidden" name="id" value="${rowData.id}">` +
                        '<label>Решение</label>' +
                        `<textarea name="solution" rows="7" class="form-control" required>${rowData.resolved}</textarea>` +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Добавить',
                            btnClass: 'btn-success',
                            action: function () {
                                let formData = $('#soldevRequestForm').serialize();
                                $.ajax({
                                    url: '/ec/solved-record',
                                    type: 'POST',
                                    data: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        if (response.status === true) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Данные успешно обновлены!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                            table.ajax.reload();
                                        } else {
                                            $.alert('Ошибка при обновлении данных!');
                                        }
                                    },
                                    error: function () {
                                        $.alert('Ошибка сервера!');
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Отмена',
                            btnClass: 'btn-danger'
                        }
                    }
                });
            });
        });



    </script>

    <script>
        $(document).ready(function() {
            if (localStorage.getItem('globalFilters')) {
                let filters = JSON.parse(localStorage.getItem('globalFilters'));
                for (let key in filters) {
                    $('[name="' + key + '"]').val(filters[key]);
                }
            }

            $('#filter').on('click', function() {
                let savedFilters = localStorage.getItem('globalFilters');
                let filterValues = savedFilters ? JSON.parse(savedFilters) : {};

                $.confirm({
                    title: 'Глобальный поиск',
                    boxWidth: '900px',
                    useBootstrap: false,
                    type: "blue",
                    content: "<div class='global-search'>" +
                        "<form id='global-search-form'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ФИО:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='fio' value='" + (filterValues.fio || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Дата создание:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='date' class='form-control' name='created_at' value='" + (filterValues.created_at || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Оборудование:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='equipment' value='" + (filterValues.equipment || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Проблема:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='problem' value='" + (filterValues.problem || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Решение проблемы :</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='solution' value='" + (filterValues.solution || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Ответсвенный:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='responsible_person' value='" + (filterValues.responsible_person || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Срок:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<div class='d-flex'>" +
                        "<input type='date' class='form-control me-2' name='deadline_from' value='" + (filterValues.deadline_from || '') + "'>" +
                        "<input type='date' class='form-control' name='deadline_until' value='" + (filterValues.deadline_until || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Решено: </label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='resolved' value='" + (filterValues.resolved || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</form>" +
                        "</div>",
                    buttons: {
                        ПОИСК: {
                            btnClass: 'btn-green',
                            action: function() {
                                var filters = $('#global-search-form').serializeArray();
                                var filterObject = {};
                                filters.forEach(function(field) {
                                    filterObject[field.name] = field.value;
                                });

                                localStorage.setItem('globalFilters', JSON.stringify(filterObject));
                                $('#lisok-table').DataTable().ajax.reload(null, false);
                            },
                        },
                        ОТМЕНА: {btnClass: 'btn-danger'},
                    },
                });
            });
            $('#clear-filter-btn').on('click', function () {
                $('#global-search-form input').val('');
                $('#global-search-form select').val('').trigger('change');

                var table = $('#lisok-table').DataTable();
                table.ajax.params = {};
                table.search('').columns().search('');
                table.ajax.reload();

                localStorage.removeItem('globalFilters');
            });
        });


    </script>
    <script>
        $('#familiarizationButton').on('click', function() {
            let table = $('#lisok-table').DataTable();
            let selectedRows = table.rows('.selected').data();
            if (selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Пожалуйста, выберите элемент для ознакомления.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Вы уверены, что ознакомлени с выбранными элементами?',
                text: "Эти элементы будут ознакомлени!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Потвердить',
                cancelButtonText: 'Отмена',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let ids = [];
                    selectedRows.each(function(row) {
                        ids.push(row.id);
                    });
                    $.ajax({
                        url: '/ec/success-records',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Элементы успешно ознакомлении.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Произошла ошибка при ознакомлении.',
                                text: error.responseText
                            });
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#createRequest').on('click', function () {
                let table = $('#lisok-table').DataTable();
                $.confirm({
                    title: 'Создание запроса',
                    type: 'blue',
                    content: '' +
                        '<form id="createRequestForm">' +
                        '<div class="form-group">' +
                        '<label>ФИО сотрудника</label>' +
                        '<input type="text" name="fullname" class="form-control" required>' +
                        '</div>' +
                        '<label>Оборудование</label>' +
                        '<input type="text" name="equipment" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Проблема</label>' +
                        '<textarea name="problem" class="form-control" required></textarea>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Решение</label>' +
                        '<textarea name="solution" class="form-control" required></textarea>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Ответственный</label>' +
                        '<input type="text" name="responsible" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Срок</label>' +
                        '<input type="date" name="deadline" class="form-control" required>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Создать',
                            btnClass: 'btn-success',
                            action: function () {
                                let formData = $('#createRequestForm').serialize();
                                $.ajax({
                                    url: '/ec/create-record',
                                    type: 'POST',
                                    data: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Элементы успешно добавлени!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        table.ajax.reload();                                    },
                                    error: function (xhr) {
                                        $.alert('Ошибка при создании запроса: ' + xhr.responseText);
                                    }
                                });
                            }

                        },
                        cancel: {
                            text: 'Отмена',
                            btnClass: 'btn-danger'
                        }
                    }
                });
            });
        });

    </script>

@endsection

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 card-style">
                    <div class="card" id="user">
                        <div class="card-nav d-flex justify-content-between align-items-center">
                            <div class="btn-group me-5" role="group" aria-label="Basic example">
                                <div class="btn-group me-3" role="group" aria-label="Basic example">
                                    @if($roleId->role_id != 1)
                                    <button id="createRequest" class="btn btn-outline-primary rounded me-2">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @endif

                                    @if($roleId->role_id == 1)
                                        <button id="familiarizationButton" type="button" class="btn btn-outline-success rounded">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif


                                    <button id="filter" type="button" class="btn btn-outline-info rounded me-2">
                                        <i class="fas fa-filter"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-info rounded me-2" id="clear-filter-btn">
                                        <i class="fa fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="listok-table-container" class="loading-overlay">
                            <div id="custom-loading">
                                <div class="spinner"></div>
                            </div>
                            <table class="table" id="listok-table" style="width: 100%">
                                <thead>
                                <tr class="data-tr">
                                    <th></th>
                                    <th class="non_searchable">ID</th>
                                    <th style="white-space: nowrap;">Дата</th>
                                    <th>Оборудование</th>
                                    <th class="non_searchable">Проблема</th>
                                    <th>Решение проблемы</th>
                                    <th>Ответсвенный</th>
                                    <th>Срок</th>
                                    <th>Ознакомление</th>
                                    <th>Решено</th>
                                    <th>ФИО сотрудника</th>
                                    <th>Action</th>

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
@endsection
