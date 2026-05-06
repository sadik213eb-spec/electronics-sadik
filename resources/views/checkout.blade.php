@extends('layouts.app')

@section('content')

    <style>
        .checkout-wrapper {
            max-width: 1440px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
            align-items: start;
            font-family: 'Poppins', sans-serif;
        }

        /* LEFT */
        .checkout-left {
            background: #fff;
            border-radius: 10px;
            padding: 32px;
            border: 1px solid #dddddd;
        }

        .checkout-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            /* margin-bottom: 16px; */
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dddddd;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 16px;
            margin-top: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #2a2a2a;
        }

        .form-label span {
            color: #dc2626;
        }

        .form-input {
            padding: 11px 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.2s;
            background: #fff;
        }

        .form-input:focus {
            border-color: #d97706;
        }

        .form-input.error {
            border-color: #d97706;
        }

        /* ✅ Saved Address Cards */
        .saved-addresses {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .address-card {
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            background: #fff;
            min-width: 160px;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }

        .address-card:hover {
            border-color: #d97706;
            background: #fffbf0;
        }

        .address-card.active {
            border-color: #d97706;
            background: #fffbf0;
        }

        .address-card-type {
            font-size: 0.95rem;
            font-weight: 700;
            color: #2a2a2a;
            margin-bottom: 3px;
        }

        .address-card-name {
            font-size: 0.82rem;
            color: #6b7280;
        }

        .address-card-city {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        /* Delivery Zone */
        .zone-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 4px;
        }

        .zone-option {
            border: 2px solid #dddddd;
            border-radius: 10px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .zone-option:hover {
            border-color: #d97706;
        }

        .zone-option.active {
            border-color: #d97706;
            background: #fffbf0;
        }

        .zone-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .zone-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 4px;
        }

        .zone-desc {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .zone-price {
            font-size: 0.95rem;
            font-weight: 700;
            color: #d97706;
            margin-top: 6px;
        }

        .zone-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #dddddd;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .zone-option.active .zone-check {
            background: #d97706;
            border-color: #d97706;
            color: #fff;
        }

        /* Payment */
        .payment-option {
            border: 2px solid #dddddd;
            border-radius: 10px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .payment-option.active {
            border-color: #d97706;
            background: #fffbf0;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .payment-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2a2a2a;
        }

        .payment-desc {
            font-size: 0.8rem;
            color: #6b7280;
        }

        /* RIGHT */
        .checkout-right {
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
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #dddddd;
        }

        .order-items {
            margin-bottom: 16px;
        }

        .order-item {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-item img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid #dddddd;
            background: #fff;
            padding: 3px;
            flex-shrink: 0;
        }

        .order-item-name {
            font-size: 0.82rem;
            color: #2a2a2a;
            font-weight: 500;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .order-item-qty {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 3px;
        }

        .order-item-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: #d97706;
            flex-shrink: 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 0.95rem;
            color: #2a2a2a;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-row:last-of-type {
            border-bottom: none !important;
        }

        .summary-row .label {
            color: #6b7280;
        }

        .summary-row .value {
            font-weight: 600;
        }

        .summary-row .value.highlight {
            color: #d97706;
            font-size: 1.1rem;
        }

        .discount-section {
            margin-bottom: 16px;
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

        .place-order-btn {
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
            margin-top: 16px;
            transition: background 0.2s;
        }

        .place-order-btn:hover {
            background: #b45309;
        }

        .terms-text {
            font-size: 0.78rem;
            color: #9ca3af;
            text-align: center;
            margin-top: 10px;
            line-height: 1.5;
        }

        .terms-text a {
            color: #d97706;
        }

        .error-msg {
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 4px;
        }

        @media (max-width: 1024px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
            }

            .checkout-right {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

            .zone-options {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="checkout-wrapper">

        {{-- LEFT: FORM --}}
        <div class="checkout-left">
            <h2 class="checkout-title">🛒 Checkout</h2>

            @if ($errors->any())
                <div
                    style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:12px 16px; margin-bottom:20px; color:#dc2626; font-size:0.9rem;">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-top:6px; padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/checkout" method="POST" id="checkout-form">
                @csrf

                {{-- SAVED ADDRESSES (logged in customers only) --}}
                @auth('customer')
                    @if ($savedAddresses->count() > 0)
                        <p class="form-section-title">Select Saved Address</p>
                        <div class="saved-addresses">
                            @php
                                $typeIcons = ['home' => '🏠', 'office' => '🏢', 'others' => '📍'];
                            @endphp
                            @foreach ($savedAddresses as $addr)
                                <button type="button"
                                    class="address-card {{ $defaultAddress && $defaultAddress->id === $addr->id ? 'active' : '' }}"
                                    onclick="fillAddress(
                                                                        this,
                                                                        '{{ addslashes($addr->name) }}',
                                                                        '{{ addslashes($addr->mobile) }}',
                                                                        '{{ addslashes($addr->address) }}',
                                                                        '{{ addslashes($addr->city) }}'
                                                                    )">
                                    <div class="address-card-type">
                                        {{ $typeIcons[$addr->type] ?? '📍' }} {{ ucfirst($addr->type) }}
                                    </div>
                                    <div class="address-card-name">{{ $addr->name }}</div>
                                    <div class="address-card-city">{{ $addr->city }}</div>
                                </button>
                            @endforeach

                            {{-- Option to use a different address --}}
                            <button type="button" class="address-card" onclick="clearAddress(this)" style="border-style: dashed;">
                                <div class="address-card-type">➕ New</div>
                                <div class="address-card-name">Different</div>
                                <div class="address-card-city">address</div>
                            </button>
                        </div>
                    @endif
                @endauth

                {{-- SHIPPING INFO --}}
                <p class="form-section-title">Shipping Information</p>
                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Full Name <span>*</span></label>
                        <input type="text" name="shipping_name" id="shipping_name"
                            class="form-input {{ $errors->has('shipping_name') ? 'error' : '' }}"
                            value="{{ old('shipping_name', $defaultAddress->name ?? '') }}"
                            placeholder="Enter your full name">
                        @error('shipping_name')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number <span>*</span></label>
                        <input type="tel" name="shipping_phone" id="shipping_phone"
                            class="form-input {{ $errors->has('shipping_phone') ? 'error' : '' }}"
                            value="{{ old('shipping_phone', $defaultAddress->mobile ?? '') }}" placeholder="01XXXXXXXXX">
                        @error('shipping_phone')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Full Address <span>*</span></label>
                        <input type="text" name="shipping_address" id="shipping_address"
                            class="form-input {{ $errors->has('shipping_address') ? 'error' : '' }}"
                            value="{{ old('shipping_address', $defaultAddress->address ?? '') }}"
                            placeholder="House, Road, Area">
                        @error('shipping_address')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">City / District <span>*</span></label>
                        <input type="text" name="shipping_city" id="shipping_city"
                            class="form-input {{ $errors->has('shipping_city') ? 'error' : '' }}"
                            value="{{ old('shipping_city', $defaultAddress->city ?? '') }}" placeholder="e.g. Dhaka">
                        @error('shipping_city')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">ZIP Code (Optional)</label>
                        <input type="text" name="shipping_zip" id="shipping_zip" class="form-input"
                            value="{{ old('shipping_zip') }}" placeholder="e.g. 1200">
                    </div>

                </div>

                {{-- DELIVERY ZONE --}}
                <p class="form-section-title">Delivery Method</p>
                <div class="zone-options">
                    <label class="zone-option active" id="zone-inside" onclick="selectZone('inside_dhaka', this)">
                        <input type="radio" name="shipping_zone" value="inside_dhaka" checked>
                        <div class="zone-check">✓</div>
                        <p class="zone-name">Inside Dhaka</p>
                        <p class="zone-desc">Get it within 1-2 days</p>
                        <p class="zone-price" id="price-inside">৳60</p>
                    </label>

                    <label class="zone-option" id="zone-outside" onclick="selectZone('outside_dhaka', this)">
                        <input type="radio" name="shipping_zone" value="outside_dhaka">
                        <div class="zone-check">✓</div>
                        <p class="zone-name">Outside Dhaka</p>
                        <p class="zone-desc">Get it within 3-5 days</p>
                        <p class="zone-price" id="price-outside">৳120</p>
                    </label>
                </div>

                {{-- PAYMENT METHOD --}}
                <p class="form-section-title">Payment Method</p>

                <label class="payment-option active" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" value="cod" checked>
                    <div class="payment-icon">💵</div>
                    <div>
                        <p class="payment-name">Cash on Delivery</p>
                        <p class="payment-desc">Pay when you receive your order</p>
                    </div>
                </label>

                <label class="payment-option" onclick="selectPayment(this)">
                    <input type="radio" name="payment_method" value="pfs">
                    <div class="payment-icon">🏪</div>
                    <div>
                        <p class="payment-name">Pickup from Showroom</p>
                        <p class="payment-desc">Collect your order from our showroom</p>
                    </div>
                </label>

                {{-- ORDER NOTE --}}
                <p class="form-section-title">Additional Instructions (Optional)</p>
                <textarea name="customer_note" class="form-input" rows="3" style="width:100%; resize:vertical;"
                    placeholder="Enter any additional instructions for your order">{{ old('customer_note') }}</textarea>

            </form>
        </div>

        {{-- RIGHT: ORDER SUMMARY --}}
        <div class="checkout-right">
            <h3 class="summary-title">Order Summary</h3>

            <div class="discount-section">
                <div class="discount-input-row">
                    <input type="text" id="discount_code" class="discount-input" placeholder="Enter discount code">
                    <button class="apply-btn" onclick="applyDiscount()">Apply</button>
                </div>
                <p id="discount-msg" style="font-size:0.8rem; margin-top:6px;"></p>
            </div>

            <div class="order-items">
                @foreach ($items as $item)
                    @php
                        $product = $item->product;
                        $price = $product->sale_price ?? $product->price;
                        $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                        $imageUrl = isset($images[0])
                            ? route('product.image', basename($images[0]))
                            : asset('images/no-image.png');
                    @endphp
                    <div class="order-item">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                        <div style="flex:1;">
                            <p class="order-item-name">{{ $product->name }}</p>
                            <p class="order-item-qty">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="order-item-price">৳{{ number_format($price * $item->quantity) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="summary-row">
                <span class="label">Subtotal ({{ $items->sum('quantity') }} items):</span>
                <span class="value">৳{{ number_format($subtotal) }}</span>
            </div>

            <div class="summary-row">
                <span class="label">
                    Discount:
                    @if($coupon)
                        <span
                            style="font-size:0.75rem; background:#dcfce7; color:#16a34a; padding:2px 8px; border-radius:50px; margin-left:4px;">
                            {{ $coupon['code'] }}
                        </span>
                    @endif
                </span>
                <span class="value" id="checkout-discount" style="{{ $discount > 0 ? 'color:#16a34a;' : '' }}">
                    {{ $discount > 0 ? '- ৳' . number_format($discount) : '৳ 0' }}
                </span>
            </div>

            <div class="summary-row">
                <span class="label">Shipping:</span>
                <span class="value" id="checkout-shipping">৳60</span>
            </div>

            <div class="summary-row" style="margin-top:8px; padding-top:8px; border-top:2px solid #dddddd;">
                <span class="label" style="font-weight:700; color:#2a2a2a; font-size:1rem;">Total:</span>
                <span class="value highlight" id="checkout-total">৳{{ number_format($subtotal + 60) }}</span>
            </div>

            <button class="place-order-btn" onclick="document.getElementById('checkout-form').submit()">
                ⚡ Proceed to Pay
            </button>

            <p class="terms-text">
                I have read and agree to the <a href="#">Terms and Conditions</a>,
                <a href="#">Privacy Policy</a> and <a href="#">Refund and Return Policy</a>
            </p>
        </div>

    </div>

    <script>
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount ?? 0 }};
        const shippingPrices = {
            inside_dhaka: {{ $items->max(fn($i) => $i->product->shipping_inside_dhaka ?? 60) }},
            outside_dhaka: {{ $items->max(fn($i) => $i->product->shipping_outside_dhaka ?? 120) }}
         };
        let currentShipping = shippingPrices.inside_dhaka;

        // Init shipping display
        document.getElementById('price-inside').textContent = '৳' + shippingPrices.inside_dhaka;
        document.getElementById('price-outside').textContent = '৳' + shippingPrices.outside_dhaka;
        document.getElementById('checkout-shipping').textContent = '৳' + currentShipping.toLocaleString('en-IN');
        document.getElementById('checkout-total').textContent = '৳' + Math.max(0, subtotal + currentShipping - discount).toLocaleString('en-IN');
        // ✅ Fill shipping fields from saved address card
        function fillAddress(btn, name, phone, address, city) {
            document.getElementById('shipping_name').value = name;
            document.getElementById('shipping_phone').value = phone;
            document.getElementById('shipping_address').value = address;
            document.getElementById('shipping_city').value = city;

            // Highlight active card
            document.querySelectorAll('.address-card').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
        }

        // ✅ Clear fields for new address
        function clearAddress(btn) {
            document.getElementById('shipping_name').value = '';
            document.getElementById('shipping_phone').value = '';
            document.getElementById('shipping_address').value = '';
            document.getElementById('shipping_city').value = '';
            document.getElementById('shipping_zip').value = '';
            document.getElementById('shipping_name').focus();

            document.querySelectorAll('.address-card').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
        }

        function selectZone(zone, el) {
            document.querySelectorAll('.zone-option').forEach(z => z.classList.remove('active'));
            el.classList.add('active');
            el.querySelector('input').checked = true;
            currentShipping = shippingPrices[zone];
            document.getElementById('checkout-shipping').textContent = '৳' + currentShipping.toLocaleString('en-IN');
            updateTotal();
        }

        function selectPayment(el) {
            document.querySelectorAll('.payment-option').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            el.querySelector('input').checked = true;
        }

        function updateTotal() {
    const total = Math.max(0, subtotal + currentShipping - discount);
    document.getElementById('checkout-total').textContent = '৳' + total.toLocaleString('en-IN');
}

        function applyDiscount() {
            const code = document.getElementById('discount_code').value;
            const msg = document.getElementById('discount-msg');
            if (!code) {
                msg.textContent = 'Please enter a discount code.';
                msg.style.color = '#dc2626';
                return;
            }
            msg.textContent = 'Invalid or expired discount code.';
            msg.style.color = '#dc2626';
        }
    </script>

@endsection
