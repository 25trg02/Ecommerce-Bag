@if(isset($error))
<div class="alert alert-danger">{{ $error }}</div>
@else
<h2>Chi tiết đơn hàng GHN: {{ $order['order_code'] ?? '' }}</h2>
<ul>
    <li>Người gửi: {{ $order['from_name'] ?? '' }} - {{ $order['from_phone'] ?? '' }}</li>
    <li>Địa chỉ gửi: {{ $order['from_address'] ?? '' }}</li>
    <li>Người nhận: {{ $order['to_name'] ?? '' }} - {{ $order['to_phone'] ?? '' }}</li>
    <li>Địa chỉ nhận: {{ $order['to_address'] ?? '' }}</li>
    <li>Trạng thái: {{ $order['status'] ?? '' }}</li>
    <li>Khối lượng: {{ $order['weight'] ?? '' }} kg</li>
    <li>Ngày tạo đơn: {{ $order['order_date'] ?? '' }}</li>
    <li>Ghi chú: {{ $order['note'] ?? '' }}</li>
    <!-- Thêm các trường khác tùy ý -->
</ul>
<h4>Lịch sử trạng thái:</h4>
<ul>
    @foreach($order['log'] ?? [] as $log)
    <li>{{ $log['status'] ?? '' }} - {{ $log['updated_date'] ?? '' }}</li>
    @endforeach
</ul>
@endif