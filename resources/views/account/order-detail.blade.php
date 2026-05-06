@extends('layouts.app')

@section('content')
    <style>
        .account-wrapper {
            max-width: 1440px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            align-items: start;
            font-family: 'Poppins', sans-serif;
        }

        /* ── Sidebar ── */
        .account-sidebar {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #dddddd;
        }

        .sidebar-profile {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #dddddd;
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 2rem;
            color: #9ca3af;
            border: 2px solid #dddddd;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 4px;
        }

        .profile-email {
            font-size: 0.82rem;
            color: #9ca3af;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #2a2a2a;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .sidebar-menu li a:hover {
            background: #fffbf0;
            color: #d97706;
        }

        .sidebar-menu li a.active {
            background: #d97706;
            color: #fff;
        }

        .sidebar-menu li a .arrow {
            margin-left: auto;
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .sidebar-menu li a.active .arrow {
            color: #fff;
        }

        .menu-divider {
            border-top: 1px solid #dddddd;
            margin: 12px 0;
        }

        /* ── Content ── */
        .account-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Back button */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            font-size: 0.88rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #d97706;
        }

        /* Cards */
        .detail-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px 28px;
            border: 1px solid #eeeeee;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #d97706;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-processing {
            background: #fef3c7;
            color: #d97706;
        }

        .status-shipped {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-delivered {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-pending {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {}

        .info-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 0.92rem;
            font-weight: 600;
            color: #2a2a2a;
        }

        .info-value.orange {
            color: #d97706;
        }

        /* Order items */
        .order-item {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid #eeeeee;
            background: #fafafa;
            padding: 4px;
            flex-shrink: 0;
        }

        .item-name {
            font-size: 0.92rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .item-meta {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .item-price {
            font-size: 1rem;
            font-weight: 700;
            color: #d97706;
            flex-shrink: 0;
            margin-left: auto;
        }

        /* Summary */
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 9px 0;
            font-size: 0.92rem;
            border-bottom: 1px solid #f3f4f6;
            color: #6b7280;
        }

        .summary-row .val {
            font-weight: 600;
            color: #2a2a2a;
        }

        .summary-row.total {
            border-top: 2px solid #dddddd;
            border-bottom: none;
            margin-top: 8px;
            padding-top: 14px;
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
        }

        .summary-row.total .val {
            color: #d97706;
            font-size: 1.15rem;
        }

        /* Timeline */
        .timeline {
            display: flex;
            gap: 0;
            margin-top: 4px;
        }

        .timeline-step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            right: -50%;
            height: 2px;
            background: #dddddd;
            z-index: 0;
        }

        .timeline-step:last-child::before {
            display: none;
        }

        .timeline-step.done::before {
            background: #d97706;
        }

        .step-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 2px solid #dddddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 1rem;
            position: relative;
            z-index: 1;
            transition: all 0.3s;
        }

        .timeline-step.done .step-dot {
            background: #d97706;
            border-color: #d97706;
            color: #fff;
        }

        .timeline-step.active .step-dot {
            background: #fff;
            border-color: #d97706;
            color: #d97706;
            box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.15);
        }

        .step-label {
            font-size: 0.75rem;
            color: #9ca3af;
            font-weight: 500;
        }

        .timeline-step.done .step-label,
        .timeline-step.active .step-label {
            color: #d97706;
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .account-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .timeline {
                gap: 0;
            }

            .step-label {
                font-size: 0.65rem;
            }
        }
    </style>

    <div class="account-wrapper">

        {{-- ── SIDEBAR ── --}}
        <div class="account-sidebar">
            <div class="sidebar-profile">
                <div class="profile-avatar">
                    @php $customer = auth('customer')->user(); @endphp
                    @if ($customer->profile_photo)
                        <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="{{ $customer->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <p class="profile-name">{{ $customer->name }}</p>
                <p class="profile-email">{{ $customer->email }}</p>
            </div>

            <ul class="sidebar-menu">
                <li><a href="/account" class="{{ request()->is('account') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Account Details <span class="arrow">›</span></a></li>
                <li><a href="/account/orders" class="active">
                        <i class="fas fa-box"></i> My Orders <span class="arrow">›</span></a></li>
                <li><a href="/account/wishlist" class="{{ request()->is('account/wishlist') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i> My Wishlist <span class="arrow">›</span></a></li>
                <li><a href="/account/addresses" class="{{ request()->is('account/addresses') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i> Addresses <span class="arrow">›</span></a></li>
                <div class="menu-divider"></div>
                <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy <span class="arrow">›</span></a>
                </li>
                <li><a href="#"><i class="fas fa-file-contract"></i> Terms & Conditions <span
                            class="arrow">›</span></a></li>
                <div class="menu-divider"></div>
                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit"
                            style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                            <a href="#" onclick="this.closest('form').submit()" style="color:#dc2626;">
                                <i class="fas fa-sign-out-alt"></i> Log Out <span class="arrow">›</span>
                            </a>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="account-content">

            {{-- Back link --}}
            <a href="/account/orders" class="back-link">
                ← Back to My Orders
            </a>

            {{-- ── Order Header Card ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <div>
                        <p class="card-title">📋 Order #{{ $order->order_number }}</p>
                        <p style="font-size:0.82rem; color:#9ca3af; margin-top:4px;">
                            Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    <span class="status-badge status-{{ $order->order_status }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Payment Method</p>
                        <p class="info-value">
                            {{ $order->payment_method === 'cod' ? '💵 Cash on Delivery' : '🏪 Pickup from Showroom' }}
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Payment Status</p>
                        <p class="info-value">
                            <span class="status-badge status-{{ $order->payment_status }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Delivery Zone</p>
                        <p class="info-value">
                            {{ $order->shipping_zone === 'inside_dhaka' ? '🏙️ Inside Dhaka' : '🌍 Outside Dhaka' }}
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Grand Total</p>
                        <p class="info-value orange">৳{{ number_format($order->grand_total) }}</p>
                    </div>
                </div>
            </div>

            {{-- ── Order Status Timeline ── --}}
            <div class="detail-card">
                <p class="card-title" style="margin-bottom:24px;">🚚 Order Progress</p>
                @php
                    $steps = ['processing', 'shipped', 'delivered'];
                    $currentStep = $order->order_status;
                    $currentIndex = array_search($currentStep, $steps);
                    $icons = ['processing' => '⚙️', 'shipped' => '📦', 'delivered' => '✅'];
                    $labels = ['processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                @endphp

                @if ($order->order_status === 'cancelled')
                    <div style="text-align:center; padding:16px 0; color:#dc2626; font-weight:600;">
                        ❌ This order has been cancelled.
                    </div>
                @else
                    <div class="timeline">
                        @foreach ($steps as $index => $step)
                            @php
                                $isDone = $currentIndex !== false && $index < $currentIndex;
                                $isActive = $step === $currentStep;
                                $class = $isDone ? 'done' : ($isActive ? 'active' : '');
                            @endphp
                            <div class="timeline-step {{ $class }}">
                                <div class="step-dot">{{ $icons[$step] }}</div>
                                <p class="step-label">{{ $labels[$step] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Shipping Info ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <p class="card-title">📦 Shipping Information</p>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Recipient</p>
                        <p class="info-value">{{ $order->shipping_name }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Phone</p>
                        <p class="info-value">{{ $order->shipping_phone }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">City</p>
                        <p class="info-value">{{ $order->shipping_city }}</p>
                    </div>
                    @if ($order->shipping_zip)
                        <div class="info-item">
                            <p class="info-label">ZIP Code</p>
                            <p class="info-value">{{ $order->shipping_zip }}</p>
                        </div>
                    @endif
                    <div class="info-item" style="grid-column: span 2;">
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $order->shipping_address }}</p>
                    </div>
                    @if ($order->customer_note)
                        <div class="info-item" style="grid-column: span 2;">
                            <p class="info-label">Note</p>
                            <p class="info-value">{{ $order->customer_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Order Items ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <p class="card-title">🛒 Ordered Items</p>
                </div>

                @foreach ($order->orderItems as $item)
                    @php
                        $product = $item->product;
                        $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                        $imageUrl = isset($images[0])
                            ? route('product.image', basename($images[0]))
                            : asset('images/no-image.png');
                    @endphp
                    <div class="order-item">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                        <div style="flex:1;">
                            <p class="item-name">{{ $product->name }}</p>
                            <p class="item-meta">Qty: {{ $item->quantity }} × ৳{{ number_format($item->unit_price) }}</p>
                        </div>
                        <p class="item-price">৳{{ number_format($item->subtotal) }}</p>
                    </div>
                @endforeach

                {{-- Totals --}}
                <div style="margin-top:16px; padding-top:4px;">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="val">৳{{ number_format($order->total_amount) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="val">৳{{ number_format($order->shipping_cost) }}</span>
                    </div>
                    @if ($order->discount_amount > 0)
                        <div class="summary-row">
                            <span>Discount</span>
                            <span class="val" style="color:#16a34a;">-
                                ৳{{ number_format($order->discount_amount) }}</span>
                        </div>
                    @endif
                    <div class="summary-row total">
                        <span>Grand Total</span>
                        <span class="val">৳{{ number_format($order->grand_total) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
