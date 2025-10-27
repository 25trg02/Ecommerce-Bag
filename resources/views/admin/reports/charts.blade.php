@extends('layouts.admin')
@section('content')
<style>
    .chart-wrap {
        min-height: 360px;
    }

    .chart-wrap canvas {
        width: 100% !important;
        height: 360px !important;
    }
</style>

<div class="container-fluid">
    <h2 class="mb-4">📊 Report Charts</h2>
    <div class="row">
        <!-- Doanh thu theo danh mục -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo danh mục</div>
                <div class="card-body chart-wrap">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo ngày -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo ngày (30 ngày)</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByDateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo tháng -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo tháng (12 tháng)</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByMonthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo năm -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo năm</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByYearChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo phương thức thanh toán -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Doanh thu theo phương thức thanh toán</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByPaymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const catLabels = @json($catLabels ?? []);
    const catRevenue = @json($catRevenue ?? []);
    const revDateLabels = @json($revDateLabels ?? []);
    const revDateData = @json($revDateData ?? []);
    const revMonthLabels = @json($revMonthLabels ?? []);
    const revMonthData = @json($revMonthData ?? []);
    const revYearLabels = @json($revYearLabels ?? []);
    const revYearData = @json($revYearData ?? []);
    const payLabels = @json($paymentMethodLabels ?? []);
    const payRevenue = @json($paymentMethodRevenue ?? []);

    window.addEventListener('DOMContentLoaded', () => {
        // Hàm tạo biểu đồ
        const mk = (el, type, labels, data, label, opts = {}) => new Chart(el, {
            type,
            data: {
                labels,
                datasets: [{
                    label,
                    data
                }]
            },
            options: Object.assign({
                responsive: true,
                maintainAspectRatio: false,
                scales: (type === 'pie' ? '' : {
                    y: {
                        beginAtZero: true
                    }
                })
            }, opts)
        });

        mk(document.getElementById('categoryRevenueChart'), 'bar', catLabels, catRevenue, 'Doanh thu (VNĐ)');
        mk(document.getElementById('revenueByDateChart'), 'line', revDateLabels, revDateData, 'Doanh thu (VNĐ)', {
            elements: {
                line: {
                    tension: 0.3
                }
            },
            datasets: {
                fill: true
            }
        });
        mk(document.getElementById('revenueByMonthChart'), 'bar', revMonthLabels, revMonthData, 'Doanh thu (VNĐ)');
        mk(document.getElementById('revenueByYearChart'), 'bar', revYearLabels, revYearData, 'Doanh thu (VNĐ)');

        new Chart(document.getElementById('revenueByPaymentMethodChart'), {
            type: 'pie',
            data: {
                labels: payLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: payRevenue
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection