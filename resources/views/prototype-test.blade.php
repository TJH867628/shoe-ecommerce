<!DOCTYPE html>
<html>
<head>
    <title>Prototype Pattern Test</title>

    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h1>Prototype Pattern Test</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <h2>{{ $shoe->shoe_name }}</h2>

        <p>
            <strong>Price:</strong>
            RM {{ $shoe->shoe_price }}
        </p>

        <p>
            <strong>Brand:</strong>
            {{ $shoe->brand->brand_name ?? 'No Brand' }}
        </p>

        <hr>

        <h3>Variations</h3>

        <ul>
            @foreach($shoe->variations as $variation)

                <li>
                    SKU:
                    {{ $variation->sku_code }}

                    <br>

                    Stock:
                    {{ $variation->stock_quantity }}

                    <br>

                    Attributes:
                    {{ json_encode($variation->attributes) }}
                </li>

                <br>

            @endforeach
        </ul>

        <hr>

        <form
            action="/shoes/{{ $shoe->id }}/clone"
            method="POST"
        >
            @csrf

            <button type="submit">
                Clone Shoe
            </button>
        </form>

    </div>

</body>
</html>