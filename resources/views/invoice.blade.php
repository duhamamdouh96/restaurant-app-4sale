<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h3 style="text-align: center">Invoice</h3>
    <table border="1" style="margin-bottom:15px; width:100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                @if($order->withTaxAndService())
                    <th>Tax</th>
                    <th>Service</th>
                @endif
                @if($order->withServiceOnly())
                    <th>Service</th>
                @endif
                <th>Customer name</th>
                <th>Customer email</th>
                <th>Paid at</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center; width:100%;">
                <td>{{ $order->unique_id }}</td>
                <td>{{ $order->paid_amount }}</td>
                @if($order->withTaxAndService())
                    <td>{{ $order->tax_percentage }} %</td>
                    <td>{{ $order->service_percentage }} %</td>
                @endif
                @if($order->withServiceOnly())
                    <td>{{ $order->service_percentage }} %</td>
                @endif
                <td>{{ $order->customer->name ?? ''}}</td>
                <td>{{ $order->customer->email ?? '' }}</td>


                <td>{{ $order->paid_at ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="text-align: center">Table</h3>

    <table border="1" style="margin-bottom:15px; width:100%;">
        <thead>
            <tr>
                <th>Table No.</th>
                <th>Guests count</th>
                <th>From</th>
                <th>To</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center;">
                <td>{{ $order->reservation->table->id ?? '' }}</td>
                <td>{{ $order->reservation->guests_count ?? '' }}</td>
                <td>{{ $order->reservation->from_date_time ?? '' }}</td>
                <td>{{ $order->reservation->to_date_time ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="text-align: center">Meals</h3>

    <table border="1" style="margin-bottom:15px;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Price</th>
                <th>Discount</th>
                <th>After discount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $detail)
                <tr style="text-align: center;">
                    <td>{{ $detail->meal->description ?? ''}}</td>
                    <td>{{ $detail->meal->price ?? ''}}</td>
                    <td>{{ $detail->meal->discount ?? ''}}</td>
                    <td>{{ $detail->amount_to_pay }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <button style="padding:5px 10px;" onclick="printPDF()">Print</button>

    <script>
        function printPDF() {
            window.print();
        }
    </script>
</body>
</html>
