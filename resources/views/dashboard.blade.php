@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total Stores</h5>
                    <h2>{{ $totalStores }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total KPIs</h5>
                    <h2>{{ $totalKpis }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Employee KPIs</h5>
                    <h2>{{ $employeeKpis }}</h2>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection