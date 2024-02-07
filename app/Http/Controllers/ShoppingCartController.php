<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Discount;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    public function showLandingPage()
    {
        $products = Product::all();
        return view('landing', compact('products'));
    }

    public function addToCart(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('landing')->with('error', 'Product not found.');
        }

        $quantity = $request->input('quantity', 1);
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Product added to cart successfully.');
    }

    public function showCart()
    {
        $cartItems = $this->getCartItems();

        $discount = Session::get('discount', []);

        $total = $this->calculateTotal($cartItems, 'original_price');
        $discountedTotal = $this->calculateTotal($cartItems, 'price');

        if (!empty($discount)) :
            if ($discount->type != Discount::TYPE_FIXED) {
                $discountedTotal = $total * ((100 - $discount->amount) / 100);
            } else if ($discount->type == Discount::TYPE_TIME_BASED) {
                // Check if discount applies on Tuesday and within the specified time range
                $currentTime = now();
                $discountStartTime = Carbon::parse($discount->start_time);
                $discountEndTime = Carbon::parse($discount->end_time);
        
                if ($currentTime->dayOfWeek === Carbon::WEDNESDAY && $currentTime->between($discountStartTime, $discountEndTime)) {
                    // Apply 5% discount
                    $discountedTotal = $total * (1 - ($discount->amount / 100));
                } else {
                    $discountedTotal = $total;
                }
            }
        endif;

        return view('cart', compact('cartItems', 'total', 'discountedTotal'));
    }

    public function updateQuantity(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('cart')->with('error', 'Product not found.');
        }

        $quantity = $request->input('quantity', 1);
        if ($quantity < 1) {
            return redirect()->route('cart')->with('error', 'Invalid quantity.');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
        }

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Cart updated successfully.');
    }

    public function removeProduct($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return redirect()->route('cart')->with('success', 'Product removed from cart successfully.');
        }

        return redirect()->route('cart')->with('error', 'Product not found in cart.');
    }

    public function applyDiscount(Request $request)
    {
        $discountCode = $request->input('discount_code');

        // Check if the discount code exists
        $discount = Discount::where('code', $discountCode)->first();

        if (!$discount) {
            return redirect()->route('cart')->with('error', 'Invalid discount code.');
        }

        $cartItems = $this->getCartItems();

        // Update the session with the discount
        Session::put('discount', $discount);

        return redirect()->route('cart')->with('success', 'Discount applied successfully.');
    }

    public function removeDiscount()
    {
        // Remove the discountedTotal session variable
        Session::forget('discountedTotal');
        
        // Remove the discount session variable
        Session::forget('discount');

        // Redirect back to the cart page or wherever appropriate
        return redirect()->route('cart')->with('success', 'Discount removed successfully.');
    }

    private function getCartItems()
    {
        $cart = Session::get('cart', []);

        $cartItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $original_price = $product->price;
                $price = $this->calculateDiscount($product);

                $cartItems[] = [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'original_price' => $original_price,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartItems;
    }

    private function calculateTotal($cartItems, $key)
    {
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item[$key] * $item['quantity'];
        }
        
        return $total;
    }

    private function calculateDiscount($product) {        
        $product_code = $product->code;
        $product_price = $product->price;

        $discount = Session::get('discount', []);

        if (!empty($discount)) :
            $min_price = $discount->min_purchase_amount;
    
            // Apply the discount based on the discount type
            switch ($discount->type) {
                case Discount::TYPE_PERCENTAGE:
                    // Apply discount based on conditions
                    if ($min_price && ($product_price > $min_price)) {
                        $discountedTotal = $product->price * ((100 - $discount->amount) / 100);
                    } else {
                        $discountedTotal = $product->price;
                    }
    
                    return $discountedTotal;
                    
                    break;
    
                case Discount::TYPE_FIXED:
                    if ($product_code == 'FA4532') {
                        $discountedTotal = $product_price - $discount->amount;
                    } else {
                        $discountedTotal = $product_price;
                    }
    
                    return $discountedTotal;
    
                    break;
                case Discount::TYPE_TIME_BASED:
                    $discountedTotal = $product_price;
    
                    return $discountedTotal;
    
                    break;
                default:
                    $discountedTotal = $product_price;
    
                    return $discountedTotal;
    
                    break;
            }
        endif;

        return $product_price;
    }
}
