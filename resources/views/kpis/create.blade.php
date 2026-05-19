@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            Create KPI
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('kpis.store') }}">
                @csrf

                <div class="mb-3">
                    <label>KPI Name</label>
                    <input type="text" name="kpi_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Category</label>
                    <input type="text" name="kpi_category" class="form-control">
                </div>

                <div class="mb-3">
                    <label>KPI Type</label>
                    <select name="kpi_type" class="form-control">
                        <option value="MTN">MTN</option>
                        <option value="COMPANY">Company</option>
                        <option value="STORE">Store</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Weighting</label>
                    <input type="number" step="0.01" name="weighting" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Target Value</label>
                    <input type="number" step="0.01" name="target_value" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Financial Period</label>
                    <input type="text" name="financial_period" class="form-control">
                </div>

                <button class="btn btn-primary">
                    Save KPI
                </button>

            </form>

        </div>
    </div>

</div>
@endsection