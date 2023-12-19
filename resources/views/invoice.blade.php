<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                @if($order->tax)
                    <th>Tax</th>
                @endif
                @if($order->service)
                    <th>Service</th>
                @endif
                <th>Customer name</th>
                <th>Customer email</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->unique_id }}</td>
                <td>{{ $order->paid_amount }}</td>
                @if($order->tax)
                    <td>{{ $order->tax }} %</td>
                @endif
                @if($order->service)
                    <td>{{ $order->service }} %</td>
                @endif
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->customer->email }}</td>
            </tr>
        </tbody>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th>Meal description</th>
                <th>Meal Price</th>
                <th>Meal Discount</th>
                <th>Amount to pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->meal->description }}</td>
                    <td>{{ $detail->meal->price }}</td>
                    <td>{{ $detail->meal->discount }}</td>
                    <td>{{ $detail->amount_to_pay }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <button onclick="printPDF()">Print</button>

    <script>
        function printPDF() {
            window.print();
        }
    </script>
</body>
</html>
