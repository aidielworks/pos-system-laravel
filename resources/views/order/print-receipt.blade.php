<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $order->order_no }}</title>

    <style>
        body {
            margin:0;
        }

        .container {
            width: 226px;
            padding: 38px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 9px;
        }
        hr {
            border: 1px dotted black;
        }
        table{
            width: 100%;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Company Details -->
        <div style="text-align: center">
            @if($company->logo_url)
                <img style="width:40px; height:40px; object-fit: cover" src="{{ asset($company->logo_url) }}" alt="">
            @endif
            <p>{{ $company->name }}</p>
            <div>
                <p>{{ $company->address }}</p>
                <p>{{ $company->postcode }}{{ ", ".$company->city }}</p>
                <p>{{ $company->state }}</p>
            </div>
            <p>Phone: {{ $company->phone_number }}</p>
            <p>Email: {{ $company->email }}</p>
        </div>

        <!-- Order Details -->
        <div>
            <hr>
            <table>
                @if(is_null($order->table_id))
                <tr>
                    <td>#{{$order->order_no}}</td>
                    <td style="text-align: end">{{$order->created_at->toDateString()}} {{$order->created_at->toTimeString()}}</td>
                </tr>
                @else
                <tr>
                    <td>Table No: {{$order->table->table_no}}</td>
                    <td style="text-align: end">{{$order->created_at->toDateString()}} {{$order->created_at->toTimeString()}}</td>
                </tr>
                <tr>
                    <td>#{{$order->order_no}}</td>
                </tr>
                @endif
            </table>
            <table>
                <thead>
                    <tr>
                        <td style=" font-weight: bold;">QTY</td>
                        <td style="width:100%; font-weight: bold;">ITEM</td>
                        <td style=" font-weight: bold;">SUBTOTAL</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->order_items as $item)
                    <tr>
                        <td style="vertical-align: top;">{{ $item->quantity }}</td>
                        <td style="vertical-align: top;">{{ $item->product()->withTrashed()->first()->product_name }}</td>
                        <td style="vertical-align: top;">RM{{ $item->subtotal }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr>
            <table>
                <tr>
                    <td colspan="2" style="width: 100%; text-align: end; padding-right: 10px">Subtotal</td>
                    <td>RM{{ $order->subtotal_amount }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 100%; text-align: end; padding-right: 10px">Discount</td>
                    <td>RM{{ $order->discount_amount }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 100%; font-size: 12px; font-weight: bold; text-align: end; padding-right: 10px">Total Due</td>
                    <td style="font-size: 12px; font-weight: bold">RM{{ $order->total_amount }}</td>
                </tr>
                @if($order->status == \App\Enum\OrderStatus::PAID)
                    <tr>
                        <td colspan="2" style="width: 100%; text-align: end; padding-right: 10px">Paid</td>
                        <td>RM{{ $order->paid_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 100%; text-align: end; padding-right: 10px">Change</td>
                        <td>RM{{ number_format($order->paid_amount  - $order->total_amount, 2, '.', ',')}}</td>
                    </tr>
                @endif
            </table>
        </div>

        <p style="text-align: center">Thank You. Please come again.</p>
        <p style="text-align: center">Power By {{ config('app.name', 'Pos System') }}</p>
    </div>
</body>
</html>
