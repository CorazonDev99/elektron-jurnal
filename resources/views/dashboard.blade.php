@extends('layouts.app')

@section('script')

<script>

function currentMonth(){

    var currentMonth = $('#currentMonth').val();
    window.location.href = '/dashboard?currentMonth='+currentMonth;

}

</script>

@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Dashboard</h4>
                </div>
            </div>
        </div>

        <div class="row"></div>

    </div>
@endsection
