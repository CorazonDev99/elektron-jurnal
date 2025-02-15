@extends('layouts.app')

@section('style')
    <link href="{{asset('assets/mof/dataTables.min.css')}}" rel="stylesheet" type="text/css"/>


    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/project.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/mof/select.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/mof/daterangepicker.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/mof/jquery-confirm.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .deadline-passed {
            background-color: #fd7b7b !important;
        }
    </style>
    <style>
        .jconfirm {
            z-index: 99999 !important;
        }

        .swal2-container {
            z-index: 150000 !important;
        }
        .daterangepicker {
            z-index: 99999 !important; /* Поверх confirm-окна */
            left: auto !important; /* Отключаем авто-расчет позиции */
            right: 470px !important; /* Смещаем календарь левее */
            top: 450px !important; /* Смещаем календарь левее */
        }
    </style>


@endsection


@section('script')

    <script src="{{ asset('assets/mof/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/mof/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="{{ asset('assets/mof/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/mof/jquery-confirm.min.js') }}"></script>



    <script>
        var userId = @json(auth()->id());
        var roleId = @json($roleId);
    </script>


    <script>
        $(document).ready(function () {

            var table = $('#listok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/fso/data',
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
                    {data: 'id', name: 'fso.id', searchable: false, visible: false},
                    {data: 'user_id', name: 'fso.user_id', searchable: false, visible: false},
                    {data: 'created_at', name: 'created_at', sClass: 'dt-center', width: '70px'},
                    {data: 'equipment', name: 'fso.equipment', sClass: 'dt-center'},
                    {data: 'problem', name: 'fso.problem', searchable: false, sClass: 'dt-center'},
                    {data: 'solution', name: 'fso.solution', searchable: false, sClass: 'dt-center'},
                    {data: 'responsible_person', name: 'fso.responsible_person', sClass: 'dt-center'},
                    {data: 'deadline', name: 'deadline', width: '100px', searchable: false, sClass: 'dt-center'},
                    {
                        data: 'acknowledgment',
                        render: function (data, type, row) {
                            if (data === 1) {
                                return `
                                    <i class="fas fa-check-circle text-success"></i><br>
                                    <small>${row.updated_at || ''}</small>`;
                                ;
                            } else {
                                return '<i class="fas fa-clock text-warning"></i>';
                            }
                        },
                        sClass: 'dt-center'
                    },
                    {data: 'resolved', name: 'fso.resolved', sClass: 'dt-center'},
                    {data: 'employee_name', name: 'fso.employee_name', searchable: false, sClass: 'dt-center'},
                    {data: 'reason', name: 'fso.reason', sClass: 'dt-center'},

                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let parts = data.deadline.split('-');
                            let deadlineDate = new Date(parts[2], parts[1] - 1, parts[0]);

                            let currentDate = new Date();
                            currentDate.setHours(0, 0, 0, 0);
                            deadlineDate.setHours(0, 0, 0, 0);

                            return `
                            <div class="d-flex">
                                ${userId === row.user_id && ![2,3,4,5,6,7,8,9,10,11,12,13,14,15].includes(roleId)  && !row.resolved && row.acknowledgment === 1?
                                `<button class="btn btn-success btn-sm edit-btn me-2" id="solved" data-id="${row.id}"><i class="fas fa-plus"></i></button>`
                                : ''}

                                ${userId === row.user_id && ![2,3,4,5,6,7,8,9,10,11,12,13,14,15].includes(roleId)  && !row.resolved && !row.reason && row.deadline &&  deadlineDate < currentDate?
                                `<button class="btn btn-info btn-sm edit-btn me-2" id="reason" data-id="${row.id}"><i class="fas fa-message"></i></button>`
                                : ''}

                                ${[1,3,4,5,6,7,8,9,10,11,12,13,14,15].includes(roleId) && row.acknowledgment === 0?
                                `<button class="btn btn-success btn-sm edit-btn me-2" id="success" data-id="${row.id}"><i class="fas fa-clipboard-check"></i></button>`
                                : ''}

                                ${userId === row.user_id && row.acknowledgment === 0 && ![2,3,4,5,6,7,8,9,10,11,12,13,14,15].includes(roleId) && deadlineDate >= currentDate?
                                `<button class="btn btn-success btn-sm edit-btn" id="edit" data-id="${row.id}"><i class="fas fa-edit"></i></button>`
                                : ''}
                                </div>
                        `;
                        },
                        sClass: 'dt-center'
                    }

                ],
                rowCallback: function(row, data) {
                    let parts = data.deadline.split('-');
                    let deadlineDate = new Date(parts[2], parts[1] - 1, parts[0]);

                    let currentDate = new Date();
                    currentDate.setHours(0, 0, 0, 0);
                    deadlineDate.setHours(0, 0, 0, 0);

                    if (deadlineDate < currentDate && !data.resolved) {
                        $(row).addClass('deadline-passed');
                    }
                },
                select: {
                    style: 'multi',
                    info: true
                },
                responsive: true,
                pageLength: 25,
                autoWidth: false,
                scrollX: true,
                order: [[1, 'desc']],
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
                $('#listok-table-container').addClass('loading');
                $('#custom-loading').fadeIn();
            });

            table.on('xhr.dt', function() {
                $('#listok-table-container').removeClass('loading');
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
                            url: '/fso/success-record',
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
                        '<label>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.</label>' +
                        `<input type="text" name="fullname" class="form-control" value="${rowData.employee_name || ''}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот</label>' +
                        `<input type="text" name="equipment" class="form-control" value="${rowData.equipment || ''}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Аниқланган камчиликлар</label>' +
                        `<textarea name="problem" class="form-control" required>${rowData.problem || ''}</textarea>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этиш бўйича чора-тадбирлар</label>' +
                        `<textarea name="solution" class="form-control" required>${rowData.solution || ''}</textarea>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этишга жавобгар</label>' +
                        `<input type="text" name="responsible" class="form-control" value="${rowData.responsible_person || ''}" required>` +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этиш муддати</label>' +
                        `<input type="text" name="deadline" class="form-control" value="${rowData.deadline ? rowData.deadline.split('-').join('-') : ''}" pattern="\d{2}-\d{2}-\d{4}" placeholder="DD-MM-YYYY" required>` +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Изменить',
                            btnClass: 'btn-success',
                            action: function () {
                                let form = $('#editRequestForm');
                                let formData = form.serializeArray();

                                let isValid = true;

                                form.find('input, textarea').each(function () {
                                    if (!$(this).val()) {
                                        isValid = false;
                                        $(this).addClass('is-invalid');
                                    } else {
                                        $(this).removeClass('is-invalid');
                                    }
                                });

                                if (!isValid) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Илтимос, барча мажбурий майдонларни тўлдиринг!',
                                    });
                                    return false;
                                }

                                let deadline = $('input[name="deadline"]').val();
                                let dateRegex = /^\d{2}-\d{2}-\d{4}$/;

                                if (!dateRegex.test(deadline)) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Камчиликни бартараф этиш муддати DD-MM-YYYY форматда болиши керак!',
                                    });
                                    return false;
                                }

                                let parts = deadline.split("-");
                                let deadlineDate = new Date(parts[2], parts[1] - 1, parts[0]);

                                let today = new Date();
                                today.setHours(0, 0, 0, 0);
                                deadlineDate.setHours(0, 0, 0, 0);

                                if (deadlineDate < today) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Камчиликни бартараф этиш муддати бугунги кунгача болиши мумкин емас!',
                                    });
                                    return false;
                                }


                                formData.forEach(field => {
                                    if (field.name === "deadline" && field.value) {
                                        let parts = field.value.split("-");
                                        if (parts.length === 3) {
                                            field.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                                        }
                                    }
                                });


                                $.ajax({
                                    url: '/fso/edit-record',
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
                    },
                    onContentReady: function () {
                        $('input[name="deadline"]').daterangepicker({
                            singleDatePicker: true,
                            autoUpdateInput: false,
                            locale: {
                                format: 'DD-MM-YYYY',
                                applyLabel: "Танлаш",
                                cancelLabel: "Бекор қилиш"
                            }
                        }).on('apply.daterangepicker', function (ev, picker) {
                            $(this).val(picker.startDate.format('DD-MM-YYYY'));
                        });
                    }
                });
            });

            $(document).on('click', '#reason', function () {
                var rowData = table.row($(this).closest('tr')).data();
                $.confirm({
                    title: 'Камчиликнинг бартараф этилмаганлиги сабаби:',
                    type: 'blue',
                    content: '' +
                        '<form id="reasonRequestForm">' +
                        `<input type="hidden" name="id" value="${rowData.id}">` +
                        '<label>Изохни киритинг:</label>' +
                        `<textarea name="reason" rows="8" class="form-control" required></textarea>` +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Добавить',
                            btnClass: 'btn-success',
                            action: function () {
                                let formData = $('#reasonRequestForm').serialize();
                                $.ajax({
                                    url: '{{route('fso.reasonRecord')}}',
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
                Swal.fire({
                    title: 'Камчилик бартараф этилдими?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Xa',
                    cancelButtonText: 'Йок'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.confirm({
                            title: '',
                            type: 'blue',
                            content: '' +
                                '<form id="solvedRequestForm">' +
                                `<input type="hidden" name="id" value="${rowData.id}">` +
                                '<label>Камчиликнинг бартараф этилганлиги бўйича қайд:</label>' +
                                `<textarea name="solution" rows="7" class="form-control" required>${rowData.resolved || ''}</textarea>` +
                                '</form>',
                            buttons: {
                                confirm: {
                                    text: 'Добавить',
                                    btnClass: 'btn-success',
                                    action: function () {
                                        let formData = $('#solvedRequestForm').serialize();
                                        $.ajax({
                                            url: '/fso/solved-record',
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
                    } else {
                        $.ajax({
                            url: '{{ route('fso.updateAcknowledgment') }}',
                            type: 'POST',
                            data: { id: rowData.id, acknowledgment: 0 },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Малумот янгиланди!',
                                        text: 'Камчилик бартараф этилмаганлиги хакида хабар юборилди!'
                                    });
                                    table.ajax.reload();
                                } else {
                                    $.alert('Ошибка при обновлении acknowledgment!');
                                }
                            },
                            error: function () {
                                $.alert('Ошибка сервера!');
                            }
                        });
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
                    title: 'Глобал кидирув',
                    boxWidth: '900px',
                    useBootstrap: false,
                    type: "blue",
                    content: "<div class='global-search mt-3'>" +
                        "<form id='global-search-form'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='fio' value='" + (filterValues.fio || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Сана вақт:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='date' class='form-control' name='created_at' value='" + (filterValues.created_at || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='equipment' value='" + (filterValues.equipment || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Аниқланган камчиликлар:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='problem' value='" + (filterValues.problem || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликни бартараф этиш бўйича чора-тадбирлар:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='solution' value='" + (filterValues.solution || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликни бартараф этишга жавобгар:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='responsible_person' value='" + (filterValues.responsible_person || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликни бартараф этиш муддати:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<div class='d-flex'>" +
                        "<input type='date' class='form-control me-2' name='deadline_from' value='" + (filterValues.deadline_from || '') + "'>" +
                        "<input type='date' class='form-control' name='deadline_until' value='" + (filterValues.deadline_until || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликнинг бартараф этилганлиги бўйича қайд: </label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' name='resolved' value='" + (filterValues.resolved || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Камчиликнинг кориб чикилганлиги: </label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='acknowledgment'>" +
                        "<option value=''>Хаммаси</option>" +
                        "<option value='1'" + (filterValues.acknowledgment === '1' ? ' selected' : '') + ">Кориб чикилган</option>" +
                        "<option value='0'" + (filterValues.acknowledgment === '0' ? ' selected' : '') + ">Кориб чикилмаган</option>" +
                        "</select>" +
                        "</div>" +
                        "</div>" +
                        "</form>" +
                        "</div>",
                    buttons: {
                        Қидирув: {
                            btnClass: 'btn-green',
                            action: function() {
                                var filters = $('#global-search-form').serializeArray();
                                var filterObject = {};
                                filters.forEach(function(field) {
                                    filterObject[field.name] = field.value;
                                });

                                localStorage.setItem('globalFilters', JSON.stringify(filterObject));
                                $('#listok-table').DataTable().ajax.reload(null, false);
                            },
                        },
                        Оркага: {btnClass: 'btn-danger'},
                    },
                });
            });

            $('#clear-filter-btn').on('click', function () {
                $('#global-search-form input').val('');
                $('#global-search-form select').val('').trigger('change');

                var table = $('#listok-table').DataTable();
                table.ajax.params = {};
                table.search('').columns().search('');
                table.ajax.reload();

                localStorage.removeItem('globalFilters');
            });
        });
    </script>

    <script>
        $('#familiarizationButton').on('click', function() {
            let table = $('#listok-table').DataTable();
            let selectedRows = table.rows('.selected').data();
            if (selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Илтимос, кориб чикиш учун елементни танланг!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Танланган элементлар билан таниш еканлигингизга ишончингиз комилми?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xa',
                cancelButtonText: 'Йок',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let ids = [];
                    selectedRows.each(function(row) {
                        ids.push(row.id);
                    });
                    $.ajax({
                        url: '/fso/success-records',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Танланган камчиликлар билан танишилди!',
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
                let table = $('#listok-table').DataTable();
                $.confirm({
                    title: 'Аникланган камчиликни бириктириш',
                    type: 'blue',
                    content: '' +
                        '<form id="createRequestForm">' +
                        '<div class="form-group">' +
                        "<label>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.:</label>" +
                        '<input type="text" name="fullname" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот:</label>' +
                        '<input type="text" name="equipment" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Аниқланган камчиликлар:</label>' +
                        '<textarea name="problem" class="form-control" required></textarea>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этиш бўйича чора-тадбирлар:</label>' +
                        '<textarea name="solution" class="form-control" required></textarea>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этишга жавобгар:</label>' +
                        '<input type="text" name="responsible" class="form-control" required>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '<label>Камчиликни бартараф этиш муддати:</label>' +
                        '<input type="text" name="deadline" class="form-control" required pattern="\d{2}-\d{2}-\d{4}" placeholder="DD-MM-YYYY" required>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Яратиш',
                            btnClass: 'btn-success',
                            action: function () {
                                let form = $('#createRequestForm');
                                let isValid = true;

                                form.find('input, textarea').each(function () {
                                    if (!$(this).val()) {
                                        isValid = false;
                                        $(this).addClass('is-invalid');
                                    } else {
                                        $(this).removeClass('is-invalid');
                                    }
                                });

                                if (!isValid) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Илтимос, барча мажбурий майдонларни тўлдиринг!',
                                    });
                                    return false;
                                }

                                let deadline = $('input[name="deadline"]').val();
                                let dateRegex = /^\d{2}-\d{2}-\d{4}$/;

                                if (!dateRegex.test(deadline)) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Камчиликни бартараф этиш муддати DD-MM-YYYY форматда болиши керак!',
                                    });
                                    return false;
                                }

                                let parts = deadline.split("-");
                                let deadlineDate = new Date(parts[2], parts[1] - 1, parts[0]);

                                let today = new Date();
                                today.setHours(0, 0, 0, 0);
                                deadlineDate.setHours(0, 0, 0, 0);

                                if (deadlineDate < today) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Хатолик',
                                        text: 'Камчиликни бартараф этиш муддати бугунги кунгача болиши мумкин емас!',
                                    });
                                    return false;
                                }


                                let formData = form.serializeArray();
                                formData.push({ name: 'roleId', value: roleId });
                                formData.forEach(field => {
                                    if (field.name === "deadline" && field.value) {
                                        let parts = field.value.split("-");
                                        if (parts.length === 3) {
                                            field.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                                        }
                                    }
                                });
                                $.ajax({
                                    url: '/fso/create-record',
                                    type: 'POST',
                                    data: $.param(formData),
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Элементы успешно добавлены!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        table.ajax.reload();
                                    },
                                    error: function (xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Ошибка',
                                            text: 'Ошибка при создании запроса: ' + xhr.responseText,
                                        });
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Оркага',
                            btnClass: 'btn-danger'
                        }
                    },
                    onContentReady: function () {
                        $('input[name="deadline"]').daterangepicker({
                            singleDatePicker: true,
                            autoUpdateInput: false,
                            locale: {
                                format: 'DD-MM-YYYY',
                                applyLabel: "Танлаш",
                                cancelLabel: "Бекор қилиш"
                            }
                        }).on('apply.daterangepicker', function (ev, picker) {
                            $(this).val(picker.startDate.format('DD-MM-YYYY'));
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $('#deleteButton').on('click', function() {
            let table = $('#listok-table').DataTable();
            let selectedRows = table.rows('.selected').data();
            if (selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Илтимос, Ўчириш учун елементни танланг',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Хакикатдан хам танлаган елементларни Ўчириб ташламокчимисз?',
                text: "Бу элементлар бутунлай очириб ташланади!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Удалить',
                cancelButtonText: 'Отмена',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let ids = [];
                    selectedRows.each(function(row) {
                        ids.push(row.id);
                    });
                    let bronNum = 0
                    $.ajax({
                        url: "{{ route('fso.deleteRecord') }}",
                        type: 'DELETE',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Элементы успешно удалены.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                            bronNum += 1
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Произошла ошибка при удалении.',
                                text: error.responseText
                            });
                        }
                    });
                }
            });
        });
    </script>

@endsection
@section('header_title', 'ФСО')

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 card-style">
                    <div class="card" id="user">
                        <div class="card-nav d-flex justify-content-between align-items-center">
                            <div class="btn-group me-5" role="group" aria-label="Basic example">
                                <div class="btn-group me-3" role="group" aria-label="Basic example">
                                    @if($roleId !== null && !in_array($roleId, [2,3,4,5,6,7,8,9,10,11,12,13,14,15]))
                                        <button id="createRequest" class="btn btn-outline-primary rounded me-2">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    @endif


                                    @if($roleId === 1)
                                        <button id="deleteButton" type="button" class="btn btn-outline-danger rounded">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <button id="familiarizationButton" type="button" class="btn btn-outline-success rounded me-2">
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
                            <table class="table bg-gradient-info table-hover dataTable row-border" id="listok-table" style="width: 100%">
                                <thead>
                                <tr class="data-tr" style="text-align: center; vertical-align: middle;">
                                    <th></th>
                                    <th></th>
                                    <th class="non_searchable">ID</th>
                                    <th style="white-space: nowrap;">Сана вақт</th>
                                    <th>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот</th>
                                    <th class="non_searchable">Аниқланган камчиликлар</th>
                                    <th>Камчиликни бартараф этиш бўйича чора-тадбирлар</th>
                                    <th>Камчиликни бартараф этишга жавобгар</th>
                                    <th>Камчиликни бартараф этиш муддати</th>
                                    <th>Участка бошлиғининг танишганлиги бўйича қайд</th>
                                    <th>Камчиликнинг бартараф этилганлиги бўйича қайд</th>
                                    <th>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.</th>
                                    <th>Камчилик бартараф этилмаганининг сабаби</th>
                                    @if($roleId !== 2)
                                        <th>Ходисалар</th>
                                    @endif
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
