<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <nav class="w-100 p-4" style="height: 5rem">
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
                <h1 class="text-center fs-2 fw-bold">Shopping Display</h1>
                <a href="/cart">
                    <button class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i>
                        My Cart
                    </button>
                </a>
            </div>
        </nav>

        <div class="d-flex flex-column justify-content-center align-items-center mt-5">
            <h1>Welcome to Shopping Cart</h1>
            <p>Start adding products to your cart!</p>
        </div>

        <div class="d-flex flex-wrap justify-content-center" style="gap: 12px;">
            @foreach ($products as $product)
                <div class="card" style="width: 18rem;">
                    <img src="https://img.buyflowers.com.sg/p/t/buket-cinta-terkasih-peluk-keindahan-romansa-12-fa26896-003.webp"
                        class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Rp. {{ number_format($product->price, 0, '.', ',') }}</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-arrow-down"></i>
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
