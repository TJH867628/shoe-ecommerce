<!DOCTYPE html>
<html>
<head>
    <title>Factory Pattern Payment Test</title>

    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
        }

        select,
        input,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        .result {
            margin-top: 20px;
            color: green;
        }
    </style>
</head>

<body>

    <h1>Factory Pattern Payment Test</h1>

    <div class="card">

        <form action="/checkout" method="POST">

            @csrf

            <label>
                Amount
            </label>

            <input
                type="number"
                name="amount"
                value="500"
            >

            <label>
                Payment Method
            </label>

            <select name="payment_type">

                <option value="FPX">
                    FPX
                </option>

                <option value="Card">
                    Card
                </option>

            </select>

            <button type="submit">
                Pay Now
            </button>

        </form>

        @if(session('success'))

            <div class="result">
                {{ session('success') }}
            </div>

        @endif

    </div>

</body>
</html>