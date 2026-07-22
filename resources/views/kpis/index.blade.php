@extends('layouts.app')
@section('content')
    <div style="max-width: 1400px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>KPIs</span>
                <a href="{{ route('kpis.create') }}" class="btn btn-primary">Create KPI</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Weighting</th>
                                <th>Target Value</th>
                                <th>Financial Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kpis as $kpi)
                                <tr>
                                    <td>{{ $kpi->kpi_name }}</td>
                                    <td>{{ $kpi->kpi_category }}</td>
                                    <td>{{ $kpi->kpi_type }}</td>
                                    <td>{{ $kpi->weighting }}</td>
                                    <td>{{ $kpi->target_value }}</td>
                                    <td>{{ $kpi->financial_period }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection