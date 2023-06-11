<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>

    <style>
        body {
            margin:0;
        }

        .container {
            width: 226px;
            padding: 38px;

        }
        hr {
            border: 1px dotted black;
        }

        table{
            width: 100%;
        }

        .order .meal_type{
            border: 3px dashed black;
            padding: 10px;
            margin: 20px 0;
        }

        @media print {
            .order {page-break-after: always;}
        }
    </style>
</head>
<body>
    <div class="container">
        @if(isset($foods) && !empty($foods))
            <div class="order">
                <h3 style="margin:0; text-align: center">{{ $order->order_no }}</h3>
                    <div class="meal_type">
                    <h3 style="margin:0;">Food</h3>
                    <table>
                        @foreach($foods as $food)
                            <tr>
                                <td>{{ $food->product_name ?? $food->product->product_name ?? '' }}</td>
                                <td style="text-align: end">{{ $food->quantity }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif

        @if(isset($drinks) && !empty($drinks))
            <div class="order">
                <h3 style="margin:0; text-align: center">{{ $order->order_no }}</h3>
                <div class="meal_type">
                    <h3 style="margin:0;">Drinks</h3>
                    <table>
                        @foreach($drinks as $drink)
                            <tr>
                                <td>{{ $drink->product_name ?? $drink->product->product_name ?? '' }}</td>
                                <td style="text-align: end">{{ $drink->quantity }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
