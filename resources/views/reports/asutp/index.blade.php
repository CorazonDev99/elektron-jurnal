@extends('layouts.app')

@section('style')
    <link href="{{asset('assets/mof/jquery-confirm.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/mof/select.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/mof/flatpickr.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .input-date{
            display:inline-block;
            text-align:center;
            width: 15%;
        }
        #chart {
            width: 60%;
            height: 600px;
            margin: 50px;
        }
        .filter-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-buttons button {
            margin: 0 5px;
        }
        .role-info {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/mof/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/mof/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/mof/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/mof/flatpickr@4.6.9.js') }}"></script>


    <script>
        var months = @json($months);
        var completed = @json($completed);
        var inProgress = @json($inProgress);
        var notCompleted = @json($notCompleted);
        var total = @json($total);

        var options = {
            chart: {
                type: 'bar',
                stacked: false,
                width: '100%',
                height: 600,
                events: {
                    dataPointSelection: function (event, chartContext, { dataPointIndex, seriesIndex }) {
                        var selectedMonth = months[dataPointIndex];
                        var reportType = chart.w.globals.seriesNames[seriesIndex];
                        console.log(reportType)

                        $.ajax({
                            url: '/get-data-asutp',
                            type: "POST",
                            data: {
                                month: selectedMonth,
                                reportType: reportType,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                $.confirm({
                                    title: `Хисобот: ${selectedMonth} ойи учун <span style="background-color: cornflowerblue; padding: 2px 5px; border-radius: 3px;">${reportType}</span>`,
                                    boxWidth: '1600px',
                                    type: 'blue',
                                    useBootstrap: false,
                                    content: `
                                <table id="reportTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 120px;">Сана вақт</th>
                                            <th>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.</th>
                                            <th>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот</th>
                                            <th>Аниқланган камчиликлар</th>
                                            <th>Камчиликни бартараф этиш бўйича чора-тадбирлар</th>
                                            <th>Камчиликни бартараф этишга жавобгар</th>
                                            <th>Камчиликни бартараф этиш муддати</th>
                                            <th>Камчиликнинг бартараф этилганлиги бўйича қайд</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.data.map(row => `
                                            <tr>
                                                <td>${row.created_at  || ''}</td>
                                                <td>${row.employee_name  || ''}</td>
                                                <td>${row.equipment || ''}</td>
                                                <td>${row.problem  || ''}</td>
                                                <td>${row.solution  || ''}</td>
                                                <td>${row.responsible_person  || ''}</td>
                                                <td>${row.deadline  || ''}</td>
                                                <td>${row.resolved  || ''}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                                <button id="printBtn" class="btn btn-primary mt-2"><i class="fas fa-print"></i>  Чоп этиш</button>
                            `,
                                    onContentReady: function () {
                                        $('#reportTable').DataTable({
                                            "paging": false,
                                            "searching": false,
                                            "ordering": false,
                                            "info": false
                                        });

                                        $('#printBtn').on('click', function () {
                                            printTable();
                                        });
                                    },
                                    buttons: {
                                        close: {
                                            text: 'Йопиш',
                                            btnClass: 'btn-red'
                                        }
                                    }
                                });
                            },
                            error: function () {
                                $.alert('Произошла ошибка при загрузке данных.');
                            }
                        });
                    }
                }
            },
            colors: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
            series: [
                { name: 'Умумий', data: total },
                { name: 'Бажарилганлар', data: completed },
                { name: 'Жарайонда', data: inProgress },
                { name: 'Бажарилмаганлар (муддати отган)', data: notCompleted }
            ],
            xaxis: {
                categories: months
            },
            title: {
                text: 'АСУТП бойича аникланган камчиликлар сони',
                align: 'center'
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + ' камчиликлар';
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        function printTable() {
            var content = document.getElementById('reportTable').outerHTML;

            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';

            document.body.appendChild(iframe);

            var doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><head><h1>Хисоботни чоп этиш</h1><style>');
            doc.write('h1 { text-align: center; }');
            doc.write('body { font-family: Arial, sans-serif; }');
            doc.write('table { width: 100%; border-collapse: collapse; }');
            doc.write('table, th, td { border: 1px solid black; }');
            doc.write('th, td { padding: 8px; width:100%; text-align: left; }');
            doc.write('</style></head><body>');
            doc.write(content);
            doc.write('</body></html>');
            doc.close();

            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            document.body.removeChild(iframe);
        }

        var fp = flatpickr("#period-picker", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function (selectedDates) {
                if (selectedDates.length === 2) {
                    var startDate = formatDate(selectedDates[0]);
                    var endDate = formatDate(selectedDates[1]);

                    $.ajax({
                        url: '/get-report-by-period-asutp',
                        type: "POST",
                        data: {
                            start_date: selectedDates[0].toISOString().split('T')[0],
                            end_date: selectedDates[1].toISOString().split('T')[0],
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $.confirm({
                                title: ` ${startDate} || ${endDate} давр учун Хисоботлар`,
                                boxWidth: '1100px',
                                type: 'blue',
                                useBootstrap: false,
                                content: `
                            <div id="reportChart"></div>
                            <p>Умумий камчиликлар сони: ${response.total}</p>
                            <p>Бажарилган качиликлар: ${response.completed}</p>
                            <p>Жарайондаги камчиликлар: ${response.inProgress}</p>
                            <p>Бажарилмаган (муддати отган) камчиликлар: ${response.notCompleted}</p>
                        `,
                                onContentReady: function () {
                                    var reportChartOptions = {
                                        chart: {
                                            type: 'pie',
                                            height: 400,
                                            width: '100%',
                                        },
                                        series: [
                                            response.completed,
                                            response.inProgress,
                                            response.notCompleted
                                        ],
                                        labels: ['Бажарилганлар', 'Жарайонда', 'Бажарилмаганлар (муддати отган)'],
                                        colors: ['#28a745', '#ffc107', '#dc3545'],
                                        title: {
                                            text: `${startDate} || ${endDate} давр учун аникланган камчиликлар`,
                                            align: 'center',
                                            style: {
                                                marginTop: '70px',
                                                fontSize: '16px',
                                                fontWeight: 'bold',
                                                color: '#333'
                                            }
                                        }
                                    };

                                    var reportChart = new ApexCharts(document.querySelector("#reportChart"), reportChartOptions);
                                    reportChart.render();
                                },
                                buttons: {
                                    print: {
                                        text: 'Чоп этиш',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                            printReport(startDate, endDate, response);
                                            return false;
                                        }
                                    },
                                    close: {
                                        text: 'Йопиш',
                                        btnClass: 'btn-red',
                                        action: function () {
                                            fp.clear();
                                        }
                                    }
                                }
                            });
                        },
                        error: function () {
                            $.alert('Произошла ошибка при загрузке данных.');
                        }
                    });
                }
            }
        });

        function formatDate(date) {
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function printReport(startDate, endDate, response) {
            var printContent = `
        <h3>Отчёт за период с ${startDate} по ${endDate}</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th style="width: 120px;">Сана вақт</th>
                    <th>Камчиликни  аниқлаган мутахасиснинг Ф.И.Ш.</th>
                    <th>Камчиликлар аниқланган ускуна, агрегат, қурилма, бино ёки иншоот</th>
                    <th>Аниқланган камчиликлар</th>
                    <th>Камчиликни бартараф этиш бўйича чора-тадбирлар</th>
                    <th>Камчиликни бартараф этишга жавобгар</th>
                    <th>Камчиликни бартараф этиш муддати</th>
                    <th>Камчиликнинг бартараф этилганлиги бўйича қайд</th>
                </tr>
            </thead>
            <tbody>
                ${response.data.map(item => `
                    <tr>
                        <td>${formatDate(item.created_at) || ''}</td>
                        <td>${item.employee_name || ''}</td>
                        <td>${item.equipment || ''}</td>
                        <td>${item.problem || ''}</td>
                        <td>${item.solution || ''}</td>
                        <td>${item.responsible_person || ''}</td>
                        <td>${item.deadline || ''}</td>
                        <td>${item.resolved || ''}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;

            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';

            document.body.appendChild(iframe);

            var doc = iframe.contentWindow.document;
            doc.open();
            doc.write('<html><head><title>Хисоботни чоп этиш</title><style>');
            doc.write('h3 { text-align: center; }');
            doc.write('body { font-family: Arial, sans-serif; margin: 20px; }');
            doc.write('table { width: 100%; border-collapse: collapse; }');
            doc.write('table, th, td { border: 1px solid black; }');
            doc.write('th, td { padding: 8px; text-align: left; }');
            doc.write('</style></head><body>');
            doc.write(printContent);
            doc.write('</body></html>');
            doc.close();

            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            document.body.removeChild(iframe);
        }

    </script>

@endsection

@section('header_title', 'АСУТП бойича Хисоботлар')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 card-style">
                <div class="card" id="user">
                    <div class="filter-buttons mt-3">

                        <form style="display:inline;">
                            <input type="hidden" name="view" value="1month">
                            <button type="submit" class="btn btn-primary {{ $viewMode === '1month' ? 'active' : '' }}">
                                Жорий ой
                            </button>
                        </form>
                        <form style="display:inline;">
                            <input type="hidden" name="view" value="6months">
                            <button type="submit" class="btn btn-primary {{ $viewMode === '6months' ? 'active' : '' }}">
                                Охирги 6 ой
                            </button>
                        </form>
                        <form style="display:inline;">
                            <input type="hidden" name="view" value="year">
                            <button type="submit" class="btn btn-secondary {{ $viewMode === 'year' ? 'active' : '' }}">
                                Бутун йил давомида
                            </button>
                        </form>

                        <div class="input-date">
                            <button type="button" id="period-picker"  name="period" class="form-control btn-outline-info">
                                Даврни танлаш
                            </button>
                        </div>



                    </div>


                    <div id="chart"></div>


                </div>
            </div>
        </div>
    </div>
@endsection
