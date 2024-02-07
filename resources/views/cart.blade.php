<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <nav class="w-100 p-4" style="height: 5rem">
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
                <a href="/">
                    <button class="btn btn-primary">
                        <i class="fas fa-cart-plus"></i>
                        Add More
                    </button>
                </a>
                <h1 class="text-center fs-2 fw-bold">Shopping Cart</h1>
            </div>
        </nav>
        <div class="mt-5">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">PRODUK</th>
                        <th scope="col">HARGA</th>
                        <th scope="col">KUANTITAS</th>
                        <th scope="col">SUBTOTAL</th>
                        <th scope="col">HAPUS</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($cartItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                                    <img src="https://img.buyflowers.com.sg/p/t/buket-cinta-terkasih-peluk-keindahan-romansa-12-fa26896-003.webp" alt="" width="40px" height="40px">
                                    <div>
                                        <p class="mb-0">{{ $item['name'] }}</p>
                                        <span style="font-size: 14px; color: gray;">{{ $item['code'] }}</span>
                                    </div>
                                </div>
                                <br>
                            </td>
                            <td>
                                @if ($item['original_price'] != $item['price'])
                                    <span class="text-decoration-line-through text-danger">
                                        Rp. {{ number_format($item['original_price'], 0, '.', ',') }}
                                    </span>
                                    &nbsp;
                                    <span>
                                        Rp. {{ number_format($item['price'], 0, '.', ',') }}
                                    </span>
                                @else
                                    <span>
                                        Rp. {{ number_format($item['price'], 0, '.', ',') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('cart.update', $item['id']) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="d-flex align-items-center" style="gap: 4px;">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                            min="1">
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </div>
                                </form>
                            </td>
                            <td>Rp. {{ number_format($item['price'] * $item['quantity'], 0, '.', ',') }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item['id']) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="w-100 text-end pb-3" style="border-bottom: 1px dotted #000;">
                <img src="https://img.floweradvisor.com/images/discount-ico.png" width="24px">
                <a class="discount-text" style="text-decoration: none; cursor: pointer;" data-bs-toggle="modal"
                    data-bs-target="#discountModal">
                    <span class="text ms-2">Gunakan Kode Diskon/Reward</span>
                </a>
            </div>

            <div class="mt-4 text-end">
                @if (Session::get('discount'))
                    <div class="alert alert-success" role="alert">
                        <h5>Discount <strong>{{ Session::get('discount')['code'] }}</strong></h5>
                        <p class="mb-0">{{ Session::get('discount')['description'] }}</p>
                    </div>

                    <p class="mb-0">Total:
                        <span class="text-decoration-line-through">Rp. {{ number_format($total, 0, '.', ',') }}</span>
                        <strong>Rp. {{ number_format($discountedTotal, 0, '.', ',') }}</strong>
                    </p>
                @else
                    <p>Total: <strong>Rp. {{ number_format($total, 0, '.', ',') }}</strong></p>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="discountModalLabel">Kode Diskon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="apply_discount_form" action="{{ route('cart.applyDiscount') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Discount Code"
                                aria-label="Discount Code" aria-describedby="basic-addon2" name="discount_code"
                                value="{{ Session::get('discount')['code'] ?? '' }}">
                            <button type="submit" class="btn btn-sm btn-danger" form="remove_discount_form">Remove
                                Discount</button>
                        </div>
                    </form>
                    <form id="remove_discount_form" action="{{ route('cart.removeDiscount') }}" method="post">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="apply_discount_form">Save changes</button>
                </div>
            </div>
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
