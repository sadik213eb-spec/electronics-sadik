@extends('layouts.app')

@section('content')

    <style>
        .cart-wrapper {
            max-width: 1440px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            align-items: start;
            font-family: 'Poppins', sans-serif;
        }

        /* LEFT: CART ITEMS */
        .cart-left {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #dddddd;
        }

        .cart-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-table-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 40px;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #dddddd;
            color: #9ca3af;
            font-size: 0.9rem;
            text-align: center;
        }

        .cart-table-header div:first-child {
            text-align: left;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 40px;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }

        .cart-product {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .cart-product img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #dddddd;
            background: #fff;
            padding: 4px;
            flex-shrink: 0;
        }

        .cart-product-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .cart-product-brand {
            font-size: 0.82rem;
            color: #9ca3af;
        }

        .cart-price {
            text-align: center;
        }

        .cart-price .sale {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            display: block;
        }

        .cart-price .original {
            font-size: 0.8rem;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            border: 1px solid #dddddd;
            border-radius: 50px;
            overflow: hidden;
            background: #ffffff;
            justify-content: center;
            width: fit-content;
            margin: 0 auto;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 1rem;
            color: #2a2a2a;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            color: #d97706;
        }

        .qty-input {
            width: 40px;
            height: 32px;
            border: none;
            border-left: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
            text-align: center;
            font-size: 0.95rem;
            color: #2a2a2a;
        }

        .cart-subtotal {
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            color: #d97706;
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #dc2626;
            font-size: 1.1rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            color: #b91c1c;
            transform: scale(1.1);
        }

        .continue-shopping {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            color: #2a2a2a;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .continue-shopping:hover {
            color: #d97706;
        }

        /* Empty cart */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 16px;
            color: #dddddd;
        }

        .empty-cart h3 {
            font-size: 1.2rem;
            color: #2a2a2a;
            margin-bottom: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        .empty-cart p {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .empty-cart a {
            background: #d97706;
            color: #fff;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
        }

        /* RIGHT: ORDER SUMMARY */
        .cart-right {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #dddddd;
            position: sticky;
            top: 80px;
        }

        .summary-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 20px;
        }

        /* Discount code */
        .discount-section {
            margin-bottom: 20px;
        }

        .discount-label {
            font-size: 0.9rem;
            color: #2a2a2a;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .discount-input-row {
            display: flex;
            gap: 8px;
        }

        .discount-input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            outline: none;
        }

        .discount-input:focus {
            border-color: #d97706;
        }

        .apply-btn {
            padding: 10px 18px;
            background: #2a2a2a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
        }

        .apply-btn:hover {
            background: #d97706;
        }

        /* Summary rows */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.95rem;
            color: #2a2a2a;
        }

        .summary-row:last-of-type {
            border-bottom: none;
        }

        .summary-row .label {
            color: #6b7280;
        }

        .summary-row .value {
            font-weight: 600;
            color: #2a2a2a;
        }

        .summary-row .value.highlight {
            color: #d97706;
            font-size: 1.1rem;
        }

        .summary-row .value.shipping {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .checkout-btn {
            width: 100%;
            padding: 16px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            margin-top: 20px;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .checkout-btn:hover {
            background: #b45309;
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .cart-wrapper {
                grid-template-columns: 1fr;
            }

            .cart-right {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-table-header {
                display: none;
            }

            .cart-item {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .cart-price,
            .cart-subtotal {
                text-align: left;
            }

            .qty-controls {
                margin: 0;
            }
        }
    </style>

    <div class="cart-wrapper">

        {{-- LEFT: CART ITEMS --}}
        <div class="cart-left">
            <h2 class="cart-title">
                🛒 Shopping Cart ({{ $items->count() }})
            </h2>

            @if ($items->isEmpty())
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added anything to your cart yet.</p>
                    <a href="/">Continue Shopping</a>
                </div>
            @else
                <div class="cart-table-header">
                    <div>Products details</div>
                    <div>Price</div>
                    <div>Quantity</div>
                    <div>Subtotal</div>
                    <div></div>
                </div>

                @foreach ($items as $item)
                    @php
                        $product = $item->product;
                        $price = $product->sale_price ?? $product->price;
                        $itemSubtotal = $price * $item->quantity;
                        $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                        $imageUrl = isset($images[0])
                            ? route('product.image', basename($images[0]))
                            : asset('images/no-image.png');
                    @endphp

                    <div class="cart-item" id="cart-item-{{ $item->id }}">
                        {{-- Product --}}
                        <div class="cart-product">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                            <div>
                                <p class="cart-product-name">{{ $product->name }}</p>
                                @if ($product->brand)
                                    <p class="cart-product-brand">Brand: {{ $product->brand->name }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="cart-price">
                            <span class="sale">৳{{ number_format($price) }}</span>
                            @if ($product->sale_price)
                                <span class="original">৳{{ number_format($product->price) }}</span>
                            @endif
                        </div>

                        {{-- Quantity --}}
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty({{ $item->id }}, -1)">−</button>
                            <input type="number" class="qty-input" id="qty-{{ $item->id }}"
                                value="{{ $item->quantity }}" min="1" readonly>
                            <button class="qty-btn" onclick="updateQty({{ $item->id }}, 1)">+</button>
                        </div>

                        {{-- Subtotal --}}
                        <div class="cart-subtotal" id="subtotal-{{ $item->id }}">
                            ৳{{ number_format($itemSubtotal) }}
                        </div>

                        {{-- Remove --}}
                        <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach

                <a href="/" class="continue-shopping">
                    ← Continue shopping
                </a>
            @endif
        </div>

        {{-- RIGHT: ORDER SUMMARY --}}
        <div class="cart-right">
            <h3 class="summary-title">Order Summary</h3>

            {{-- Discount Code --}}
            <div class="discount-section">
                <span class="discount-label">Apply discount code</span>
                <div class="discount-input-row">
                    <input type="text" id="discount_code" class="discount-input" placeholder="Enter discount code">
                    <button class="apply-btn" onclick="applyDiscount()">Apply</button>
                </div>
                <p id="discount-msg" style="font-size:0.8rem; margin-top:6px;"></p>
            </div>

            {{-- Summary --}}
            <div class="summary-row">
                <span class="label">Subtotal ({{ $items->sum('quantity') }}):</span>
                <span class="value highlight" id="total-subtotal">৳{{ number_format($subtotal) }}</span>
            </div>

            <div class="summary-row">
                <span class="label">Discount:</span>
                <span class="value" id="discount-value">৳ 0</span>
            </div>

            <div class="summary-row">
                <span class="label">Shipping:</span>
                <span class="value shipping">will be added</span>
            </div>

            <div class="summary-row">
                <span class="label" style="font-weight:700; color:#2a2a2a;">Total:</span>
                <span class="value highlight" id="grand-total">৳{{ number_format($subtotal) }}</span>
            </div>

            {{-- <a href="/checkout" class="checkout-btn">Checkout</a> --}}
            @auth('customer')
                <a href="{{ route('checkout.index') }}" class="checkout-btn">Checkout</a>
            @else
                <a href="{{ route('login') }}?redirect=checkout" class="checkout-btn">Login to Checkout</a>
            @endauth
        </div>

    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function updateQty(id, change) {
            const input = document.getElementById('qty-' + id);
            const newQty = Math.max(1, parseInt(input.value) + change);
            input.value = newQty;

            fetch(`/cart/update/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        quantity: newQty
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('subtotal-' + id).textContent = '৳' + data.subtotal;
                        updateCartCount(data.count);
                        recalcTotal();
                    }
                });
        }

        function removeItem(id) {
            // Confirmation line removed for instant deletion

            fetch(`/cart/remove/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remove the element from the HTML immediately
                        const element = document.getElementById('cart-item-' + id);
                        if (element) {
                            element.remove();
                        }

                        updateCartCount(data.count);
                        recalcTotal();

                        // Reload if cart is empty to show the "Empty Cart" message
                        if (data.count === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(err => console.error('Error removing item:', err));
        }

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                const val = el.textContent.replace('৳', '').replace(/,/g, '');
                total += parseFloat(val) || 0;
            });
            document.getElementById('total-subtotal').textContent = '৳' + total.toLocaleString('en-IN');
            document.getElementById('grand-total').textContent = '৳' + total.toLocaleString('en-IN');
        }

        function applyDiscount() {
            const code = document.getElementById('discount_code').value;
            const msg = document.getElementById('discount-msg');
            if (!code) {
                msg.textContent = 'Please enter a discount code.';
                msg.style.color = '#dc2626';
                return;
            }
            // Placeholder - implement discount logic later
            msg.textContent = 'Invalid or expired discount code.';
            msg.style.color = '#dc2626';
        }

        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (badge) badge.textContent = count;
        }
    </script>

@endsection
