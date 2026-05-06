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

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
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

        /* CONTENT */
        .account-content {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            border: 1px solid #dddddd;
        }

        .content-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d97706;
        }

        /* Order Card */
        .order-card {
            border: 1px solid #dddddd;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .order-card-header {
            background: #f9fafb;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: 1px solid #dddddd;
        }

        .order-number {
            font-size: 0.95rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .order-date {
            font-size: 0.82rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        .order-status {
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
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
            background: #fef2f2;
            color: #dc2626;
        }

        .status-pending {
            background: #f3f4f6;
            color: #6b7280;
        }

        .order-card-body {
            padding: 16px 20px;
        }

        .order-item {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #dddddd;
            background: #fff;
            padding: 3px;
            flex-shrink: 0;
        }

        .order-item-name {
            font-size: 0.88rem;
            font-weight: 500;
            color: #2a2a2a;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .order-item-qty {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 3px;
        }

        .order-item-price {
            font-size: 0.95rem;
            font-weight: 700;
            color: #d97706;
            flex-shrink: 0;
        }

        .order-card-footer {
            padding: 14px 20px;
            background: #f9fafb;
            border-top: 1px solid #dddddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-total {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .order-total span {
            color: #d97706;
        }

        .empty-orders {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-orders i {
            font-size: 3rem;
            margin-bottom: 16px;
            color: #dddddd;
        }

        .empty-orders h3 {
            font-size: 1.1rem;
            color: #2a2a2a;
            margin-bottom: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        .empty-orders a {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 24px;
            background: #d97706;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            text-decoration: none;
        }

        @media (max-width: 1024px) {
            .account-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="account-wrapper">

        {{-- SIDEBAR --}}
        <div class="account-sidebar">
            <div class="sidebar-profile">
                <div class="profile-avatar">
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
                <li><a href="/account/orders" class="{{ request()->is('account/orders') ? 'active' : '' }}">
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

        {{-- CONTENT --}}
        <div class="account-content">
            <h3 class="content-title">My Orders</h3>

            @if ($orders->isEmpty())
                <div class="empty-orders">
                    <i class="fas fa-box-open"></i>
                    <h3>No orders yet</h3>
                    <p>You haven't placed any orders yet.</p>
                    <a href="/">Start Shopping</a>
                </div>
            @else
                @foreach ($orders as $order)
                    <div class="order-card">
                        <div class="order-card-header">
                            <div>
                                <p class="order-number">#{{ $order->order_number }}</p>
                                <p class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <span class="order-status status-{{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>

                        <div class="order-card-body">
                            @foreach ($order->orderItems as $item)
                                @php
                                    $product = $item->product;
                                    $images = is_array($product->images)
                                        ? $product->images
                                        : json_decode($product->images, true);
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
                                    <p class="order-item-price">৳{{ number_format($item->subtotal) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-card-footer">
                            <p class="order-total">
                                Grand Total: <span>৳{{ number_format($order->grand_total) }}</span>
                            </p>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <span style="font-size:0.82rem; color:#9ca3af;">
                                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Pickup from Showroom' }}
                                </span>
                                {{-- Order Details Button --}}
                                <a href="/account/orders/{{ $order->id }}"
                                    style="padding:8px 18px; background:#d97706; color:#fff; border-radius:8px; font-size:0.82rem; font-weight:600; font-family:'Montserrat',sans-serif; text-decoration:none; transition:background 0.2s; white-space:nowrap;"
                                    onmouseover="this.style.background='#b45309'"
                                    onmouseout="this.style.background='#d97706'">
                                    Order Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div style="margin-top:20px;">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
