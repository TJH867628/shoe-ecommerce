<!DOCTYPE html>
<html>

<head>
    <title>Payment Result</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .success {
            color: #16a34a;
        }

        .failed {
            color: #dc2626;
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .info {
            margin-top: 25px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            text-align: left;
        }

        .row {
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background: #111827;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }

        .btn:hover {
            background: black;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        @if(session('failed'))
            <div class="icon failed">✖</div>
            <h1 class="failed">Payment Failed</h1>
            <p>{{ session('failed') }}</p>
        @else
            <div class="icon success">✔</div>
            <h1 class="success">Payment Successful</h1>
            <p>{{ session('success') }}</p>
        @endif

        @if($result)
            <div class="info">

                <div class="row">
                    <strong>Status ID:</strong>
                    {{ $result['status_id'] ?? '-' }}
                </div>

                <div class="row">
                    <strong>Bill Code:</strong>
                    {{ $result['billcode'] ?? '-' }}
                </div>

                <div class="row">
                    <strong>Order ID:</strong>
                    {{ $result['order_id'] ?? '-' }}
                </div>

            </div>
        @endif

        <a href="{{ route('user.profile') }}" class="btn">
            Go To My Orders
        </a>

    </div>

</div>

</body>

</html>