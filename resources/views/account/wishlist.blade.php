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

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .wishlist-card {
            border: 1px solid #dddddd;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            transition: transform 0.2s;
        }

        .wishlist-card:hover {
            transform: translateY(-3px);
        }

        .wishlist-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            background: #fff;
            padding: 8px;
        }

        .wishlist-card-body {
            padding: 10px 12px 12px;
        }

        .wishlist-card-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a2a2a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .wishlist-card-price {
            color: #d97706;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
        }

        .wishlist-card-old {
            color: #9ca3af;
            font-size: 0.8rem;
            text-decoration: line-through;
            margin-left: 4px;
        }

        .wishlist-card-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-add-cart {
            flex: 1;
            padding: 8px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
        }

        .btn-add-cart:hover {
            background: #b45309;
        }

        .btn-remove-wish {
            width: 36px;
            height: 36px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn-remove-wish:hover {
            background: #dc2626;
            color: #fff;
        }

        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-wishlist i {
            font-size: 3rem;
            margin-bottom: 16px;
            color: #dddddd;
            display: block;
        }

        .empty-wishlist h3 {
            font-size: 1.1rem;
            color: #2a2a2a;
            margin-bottom: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        .empty-wishlist a {
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
            <h3 class="content-title">My Wishlist ({{ $wishlists->count() }})</h3>

            @if ($wishlists->isEmpty())
                <div class="empty-wishlist">
                    <i class="fas fa-heart"></i>
                    <h3>Your wishlist is empty</h3>
                    <p>Save your favourite products here for later.</p>
                    <a href="/">Start Shopping</a>
                </div>
            @else
                <div class="wishlist-grid">
                    @foreach ($wishlists as $item)
                        @php
                            $images = is_array($item->product->images)
                                ? $item->product->images
                                : json_decode($item->product->images, true);
                            $image = isset($images[0])
                                ? route('product.image', basename($images[0]))
                                : asset('images/no-image.png');
                        @endphp
                        <div class="wishlist-card" id="wish-{{ $item->id }}">
                            <a href="/products/{{ $item->product->slug }}">
                                <img src="{{ $image }}" alt="{{ $item->product->name }}">
                            </a>
                            <div class="wishlist-card-body">
                                <a href="/products/{{ $item->product->slug }}">
                                    <p class="wishlist-card-name">{{ $item->product->name }}</p>
                                </a>
                                <div>
                                    <span
                                        class="wishlist-card-price">৳{{ number_format($item->product->sale_price ?? $item->product->price) }}</span>
                                    @if ($item->product->sale_price)
                                        <span class="wishlist-card-old">৳{{ number_format($item->product->price) }}</span>
                                    @endif
                                </div>
                                <div class="wishlist-card-actions">
                                    <button class="btn-add-cart" onclick="addToCart({{ $item->product->id }})">
                                        🛒 Add to Cart
                                    </button>
                                    <button class="btn-remove-wish"
                                        onclick="removeWishlist({{ $item->product->id }}, {{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function addToCart(productId) {
            fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('cart-count');
                        if (badge) badge.textContent = data.count;
                    }
                });
        }

        function removeWishlist(productId, itemId) {
            fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'removed') {
                        document.getElementById('wish-' + itemId).remove();
                    }
                });
        }
    </script>
@endsection
