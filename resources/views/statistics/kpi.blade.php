@extends('layouts.app')

@section('title', 'KPI Performance Dashboard')
@section('page-title', 'KPI Performance Dashboard')
@section('page-subtitle', 'Company performance, KPI achievement and store analytics')

@section('content')
<div class="kpi-dashboard" data-endpoint="{{ route('kpi.statistics.data') }}" data-export-endpoint="{{ route('kpi.statistics.export') }}" data-default-year="{{ $defaultYear }}">
    <section class="kpi-hero">
        <div>
            <span class="kpi-eyebrow"><i class="fas fa-chart-pie"></i> Management analytics</span>
            <h1>KPI Performance Dashboard</h1>
            <p>Company performance, KPI achievement and store analytics</p>
        </div>
        <button class="kpi-button kpi-button--light" type="button" data-action="refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
    </section>

    <form class="kpi-filters" data-filters>
        <div class="kpi-filter-group">
            <label for="kpi-year">Year</label>
            <select id="kpi-year" name="year">
                @if(!$options['years']->contains($defaultYear))
                    <option value="{{ $defaultYear }}" selected>{{ $defaultYear }}</option>
                @endif
                @foreach($options['years'] as $year)
                    <option value="{{ $year }}" @selected($year == $defaultYear)>{{ $year }}</option>
                @endforeach
                <option value="">All years</option>
            </select>
        </div>
        <div class="kpi-filter-group"><label for="kpi-quarter">Quarter</label><select id="kpi-quarter" name="quarter"><option value="">All quarters</option><option value="1">Q1</option><option value="2">Q2</option><option value="3">Q3</option><option value="4">Q4</option></select></div>
        <div class="kpi-filter-group"><label for="kpi-month">Month</label><select id="kpi-month" name="month"><option value="">All months</option>@foreach(range(1, 12) as $month)<option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-region">Region</label><select id="kpi-region" name="region"><option value="">All regions</option>@foreach($options['regions'] as $region)<option value="{{ $region }}">{{ $region }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-store">Store</label><select id="kpi-store" name="store_id"><option value="">All stores</option>@foreach($options['stores'] as $store)<option value="{{ $store->store_id }}">{{ $store->name ?: $store->store_code }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-role">User role</label><select id="kpi-role" name="role"><option value="">Salespeople and supervisors</option>@foreach($options['roles'] as $role)<option value="{{ $role }}">{{ $role }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-user">User</label><select id="kpi-user" name="user_id"><option value="">All users</option>@foreach($options['users'] as $user)<option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_name ?: 'User' }})</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-category">KPI category</label><select id="kpi-category" name="kpi"><option value="">All categories</option>@foreach($options['kpis'] as $kpi)<option value="{{ $kpi }}">{{ $kpi }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-level">KPI level</label><select id="kpi-level" name="level"><option value="">All levels</option>@foreach($options['levels'] as $level)<option value="{{ $level }}">{{ $level }}</option>@endforeach</select></div>
        <div class="kpi-filter-group"><label for="kpi-status">Status</label><select id="kpi-status" name="status"><option value="">All statuses</option><option value="achieved">Achieved</option><option value="on_target">On Target</option><option value="below_target">Below Target</option><option value="critical">Critical</option></select></div>
        <div class="kpi-filter-group"><label for="kpi-from">From</label><input id="kpi-from" type="date" name="from"></div>
        <div class="kpi-filter-group"><label for="kpi-to">To</label><input id="kpi-to" type="date" name="to"></div>
        <div class="kpi-filter-actions"><button class="kpi-button" type="submit"><i class="fas fa-filter"></i> Apply filters</button><button class="kpi-button kpi-button--muted" type="button" data-action="reset">Reset</button><button class="kpi-button kpi-button--outline" type="button" data-action="export"><i class="fas fa-file-export"></i> Export CSV</button></div>
    </form>

    <div class="kpi-status-bar"><span data-period>Loading performance data...</span><span data-last-updated></span></div>
    <section class="kpi-stat-grid" data-summary></section>

    <section class="kpi-chart-grid">
        <article class="kpi-card"><div class="kpi-card__heading"><h2>KPI Distribution</h2><span>Current status mix</span></div><div class="kpi-chart" id="kpi-distribution"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>KPI Weighting by Level</h2><span>Requires KPI weights in the data model</span></div><div class="kpi-chart" id="kpi-weighting"></div></article>
        <article class="kpi-card kpi-card--wide"><div class="kpi-card__heading"><h2>Actual Performance vs Target</h2><span>Aggregated by KPI category</span></div><div class="kpi-chart" id="kpi-actual-target"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>Performance Trend</h2><span>Monthly actuals and targets</span></div><div class="kpi-chart" id="kpi-trend"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>Store Performance Ranking</h2><span>Click a store for its detail view</span></div><div class="kpi-chart" id="kpi-stores"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>KPI Performance by Store</h2><select class="kpi-mini-select" data-category-select aria-label="KPI category"></select></div><div class="kpi-chart" id="kpi-store-kpis"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>KPI Achievement Trend</h2><span>Achievement against 100% target</span></div><div class="kpi-chart" id="kpi-achievement"></div></article>
        <article class="kpi-card"><div class="kpi-card__heading"><h2>KPI Status by Store</h2><span>Count of records by status</span></div><div class="kpi-chart" id="kpi-status-stores"></div></article>
    </section>

    <section class="kpi-card kpi-table-card"><div class="kpi-card__heading"><div><h2>KPI Summary by Store</h2><span data-table-count></span></div><input class="kpi-search" type="search" placeholder="Search stores" data-table-search></div><div class="kpi-table-wrap"><table class="kpi-table"><thead><tr><th>Store</th><th data-kpi-headings></th><th>Overall</th><th>Target</th><th>Variance</th><th>Status</th></tr></thead><tbody data-summary-table></tbody></table></div></section>
</div>

<div class="kpi-modal" data-modal hidden><div class="kpi-modal__panel"><button class="kpi-modal__close" type="button" data-action="close-modal" aria-label="Close"><i class="fas fa-times"></i></button><div data-modal-content></div></div></div>
@endsection

@push('scripts')
@vite(['resources/css/kpi-dashboard.css', 'resources/js/kpi-dashboard.js'])
@endpush