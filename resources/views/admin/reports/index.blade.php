{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<style>
    .report-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: rgb(116, 101, 220);
        text-align: center;
        margin-bottom: 2rem;
        letter-spacing: 2px;
    }

    .summary-cards {
        display: flex;
        gap: 2rem;
        justify-content: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .summary-card {
        background: linear-gradient(135deg, #f8f9ff 60%, #e0e7ff 100%);
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(116, 101, 220, 0.10);
        padding: 2rem 2.5rem;
        min-width: 220px;
        text-align: center;
        transition: box-shadow 0.2s;
        border: 1px solid #e0e7ff;
    }

    .summary-card:hover {
        box-shadow: 0 4px 16px rgba(116, 101, 220, 0.18);
    }

    .summary-card .icon {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        color: rgb(116, 101, 220);
    }

    .summary-card .value {
        font-size: 2rem;
        font-weight: bold;
        color: rgb(116, 101, 220);
        letter-spacing: 1px;
    }

    .summary-card .label {
        font-size: 1.1rem;
        color: #666;
    }

    .report-table {
        background: #fff;
        border-radius: 0.9rem;
        overflow: hidden;
        box-shadow: 0 1px 8px rgba(116, 101, 220, 0.07);
        margin-bottom: 2.5rem;
    }

    .report-table th {
        background: rgb(116, 101, 220);
        color: #fff;
        font-weight: 600;
        text-align: center;
        font-size: 1.1rem;
    }

    .report-table td {
        text-align: center;
        vertical-align: middle;
        font-size: 1.05rem;
        background: #f8f9fa;
    }

    .report-table tr:hover td {
        background: #ecebfa;
    }

    .section-title {
        color: rgb(116, 101, 220);
        font-size: 1.4rem;
        font-weight: 600;
        margin: 2.5rem 0 1rem 0;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .summary-cards {
            flex-direction: column;
            gap: 1rem;
        }

        .summary-card {
            padding: 1.2rem 1rem;
            min-width: 0;
        }

        .report-table th,
        .report-table td {
            font-size: 0.98rem;
        }
    }
</style>
<div class="container">
    <div class="report-title">
        <i class="fa fa-bar-chart" aria-hidden="true"></i> Báo cáo tổng quan
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <div class="icon"><i class="fa fa-shopping-cart"></i></div>
            <div class="value">{{ $totalOrders }}</div>
            <div class="label">Tổng số đơn hàng</div>
        </div>
        <div class="summary-card">
            <div class="icon"><i class="fa fa-users"></i></div>
            <div class="value">{{ $totalCustomers }}</div>
            <div class="label">Tổng số khách hàng</div>
        </div>
    </div>

    {{-- Doanh thu theo danh mục --}}
    <div class="section-title"><i class="fa fa-list-alt"></i> Doanh thu theo từng danh mục</div>
    <div class="table-responsive">
        <table class="table report-table">
            <thead>
                <tr>
                    <th>Danh mục</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryRevenue as $revenue)
                <tr>
                    <td>{{ $revenue->category_name ?? $revenue->category_id }}</td>
                    <td>{{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Doanh thu theo ngày --}}
    <div class="section-title"><i class="fa fa-calendar"></i> Doanh thu theo ngày</div>
    <div class="table-responsive">
        <table class="table report-table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenueByDate as $revenue)
                <tr>
                    <td>
                        @php
                        try {
                        $d = \Carbon\Carbon::parse($revenue->date)->format('d/m/Y');
                        } catch (\Exception $e) {
                        $d = $revenue->date;
                        }
                        @endphp
                        {{ $d }}
                    </td>
                    <td>{{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Doanh thu theo tháng --}}
    <div class="section-title"><i class="fa fa-calendar-o"></i> Doanh thu theo tháng</div>
    <div class="table-responsive">
        <table class="table report-table">
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenueByMonth as $revenue)
                <tr>
                    <td>
                        @php
                        $m = str_pad($revenue->month ?? '', 2, '0', STR_PAD_LEFT);
                        $label = isset($revenue->year) ? ($m . '/' . $revenue->year) : $m;
                        @endphp
                        {{ $label }}
                    </td>
                    <td>{{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Doanh thu theo năm --}}
    <div class="section-title"><i class="fa fa-calendar-check-o"></i> Doanh thu theo năm</div>
    <div class="table-responsive">
        <table class="table report-table">
            <thead>
                <tr>
                    <th>Năm</th>
                    <th>Tổng doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenueByYear as $revenue)
                <tr>
                    <td>{{ $revenue->year }}</td>
                    <td>{{ number_format((float) $revenue->total_revenue, 0, ',', '.') }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection