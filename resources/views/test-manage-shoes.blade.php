<!DOCTYPE html>
<html>
<head>
    <title>Manage Shoes</title>

    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        .shoe-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        input,
        textarea {
            width: 300px;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
            margin-right: 10px;
        }

        .variation-card {
            border: 1px solid #eee;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
        }

        .option-card {
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 30px;
        }
    </style>
</head>

<body>

<h1>Manage Shoes</h1>

@if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
@endif

@foreach($shoes as $shoe)

    <div class="shoe-card">

        {{-- UPDATE SHOE --}}
        <form action="/shoes/{{ $shoe->id }}" method="POST">

            @csrf
            @method('PUT')

            <h2>
                Shoe #{{ $shoe->id }}
            </h2>

            <label>
                Shoe Name
            </label>

            <br>

            <input
                type="text"
                name="shoe_name"
                value="{{ $shoe->shoe_name }}"
            >

            <br>

            <label>
                Description
            </label>

            <br>

            <textarea name="shoe_description">{{ $shoe->shoe_description }}</textarea>

            <br>

            <label>
                Price
            </label>

            <br>

            <input
                type="number"
                name="shoe_price"
                value="{{ $shoe->shoe_price }}"
            >

            <br>

            <button type="submit">
                Update Shoe
            </button>

        </form>

        {{-- CLONE SHOE --}}
        <form
            action="/shoes/{{ $shoe->id }}/clone"
            method="POST"
        >
            @csrf

            <button type="submit">
                Clone Shoe
            </button>
        </form>

        {{-- DELETE SHOE --}}
        <form
            action="/shoes/{{ $shoe->id }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                onclick="return confirm('Delete shoe?')"
            >
                Delete Shoe
            </button>
        </form>

        {{-- OPTIONS --}}
        <h3>Options</h3>

        @foreach($shoe->options as $option)

            <div class="option-card">

                <form
                    action="/shoes/options/{{ $option->id }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <input
                        type="text"
                        name="option_name"
                        value="{{ $option->option_name }}"
                    >

                    <button type="submit">
                        Update Option
                    </button>

                </form>

            </div>

        @endforeach

        {{-- SKU VARIATIONS --}}
<h3>SKU Variations</h3>

@foreach($shoe->variations as $variation)

    <div class="variation-card">

        <form
            action="/shoes/variations/{{ $variation->id }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            {{-- SKU CODE --}}
            <label>
                SKU Code
            </label>

            <br>

            <input
                type="text"
                value="{{ $variation->sku_code }}"
                readonly
            >

            <br>

            {{-- ATTRIBUTES --}}
            @foreach($variation->attributes as $key => $value)

                <label>
                    {{ $key }}
                </label>

                <br>

                <input
                    type="text"
                    name="attributes[{{ $key }}]"
                    value="{{ $value }}"
                >

                <br>

            @endforeach

            {{-- STOCK --}}
            <label>
                Stock Quantity
            </label>

            <br>

            <input
                type="number"
                name="stock"
                value="{{ $variation->stock_quantity }}"
            >

            <br>

            <button type="submit">
                Update SKU
            </button>

        </form>

    </div>

@endforeach
    </div>

@endforeach

</body>
</html>