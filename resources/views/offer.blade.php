@extends('layouts.app')

@section('content')
    <style>
        .offer-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        /* Banner */
        .offer-banner {
            width: 100%;
            height: auto !important;
            border-radius: 10px;
            overflow: hidden;
            margin: 30px 0 20px;
        }

        .offer-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 1rem;
            color: #9ca3af;
            margin-top: 15px;
            font-weight: 600;
        }

        .breadcrumb a {
            color: #d97706;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #2a2a2a;
        }

        /* Title + Timer Row */
        .offer-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .offer-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #d97706;
            margin: 0;
        }

        /* Countdown Timer */
        .countdown-wrap {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .countdown-box {
            background: #ffffff;
            color: #d97706;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: center;
            min-width: 64px;
            border: 1px solid #dddddd;
        }

        .countdown-box .num {
            font-size: 1.5rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            display: block;
            line-height: 1;
        }

        .countdown-box .lbl {
            font-size: 0.7rem;
            color: #2a2a2a;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-top: 4px;
            font-weight: 600;
        }

        .countdown-sep {
            font-size: 1.5rem;
            font-weight: 700;
            color: #d97706;
        }

        /* Items bar */
        .items-found-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #dddddd;
            margin-bottom: 20px;
        }

        .items-found-bar p {
            font-size: 0.9rem;
            color: #2a2a2a;
            margin: 0;
        }

        .sort-dropdown {
            padding: 6px 12px;
            border: 1px solid #dddddd;
            border-radius: 6px;
            font-size: 0.85rem;
            outline: none;
            cursor: pointer;
        }

        /* Product Grid — same as category page */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.5s all ease-in-out;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .product-card:hover {
            box-shadow: 0 0 12px 0px #d977062c;
            border-color: #d97706;
        }

        .product-top-section {
            position: relative;
            background: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 250px;
            overflow: hidden;
        }

        .discount-percent-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #16a34a;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 50px;
            z-index: 2;
        }

        .product-img-wrap {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img-wrap img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.5s ease-in-out;
        }

        .product-img-wrap img:hover {
            transform: scale(1.15);
        }

        .product-bottom-section {
            background: #f8f9fa;
            padding: 15px;
            border-top: 1px solid #dddddd;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 500;
            color: #2a2a2a;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            text-decoration: none;
            height: 40px;
        }

        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .save-amount-badge {
            background: #d97706;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
        }

        .cart-btn {
            background: #2a2a2a;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: 0.3s all ease-in-out;
        }

        .cart-btn:hover {
            background: #d97706;
            transform: scale(1.1);
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sale-price {
            color: #d97706;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .old-price {
            color: #9ca3af;
            text-decoration: line-through;
            font-size: 0.9rem;
        }

        .pagination-wrap {
            margin: 40px 0;
            display: flex;
            justify-content: center;
        }

        /* Expired notice */
        .offer-expired {
            text-align: center;
            padding: 40px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #dc2626;
            font-weight: 600;
            margin-bottom: 20px;
        }

        @media (max-width: 640px) {
            .offer-header-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .countdown-box {
                min-width: 50px;
                padding: 8px 10px;
            }

            .countdown-box .num {
                font-size: 1.2rem;
            }

            .offer-banner {
                border-radius: 5px;
                margin: 20px 0 10px;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }

            .offer-title {
                font-size: 1.5rem;
            }

            .countdown-box .num {
                font-size: 1.2rem;
            }

            .countdown-sep {
                font-size: 1.2rem;
            }

            .product-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }
    </style>

    <div class="offer-container">

        {{-- Banner --}}
        @if ($offer->banner)
            <div class="offer-banner">
                <img src="{{ asset('storage/' . $offer->banner) }}" alt="{{ $offer->name }}">
            </div>
        @endif

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="/">Home</a> &rsaquo;
            <a href="#">Latest Offers</a> &rsaquo;
            <span style="color:#2a2a2a;">{{ $offer->name }}</span>
        </div>

        {{-- Title + Timer --}}
        <div class="offer-header-row">
            <h1 class="offer-title">{{ $offer->name }}!</h1>

            @if ($offer->show_timer)
                <div class="countdown-wrap">
                    <div class="countdown-box">
                        <span class="num" id="days">00</span>
                        <span class="lbl">DAY</span>
                    </div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-box">
                        <span class="num" id="hours">00</span>
                        <span class="lbl">HRS</span>
                    </div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-box">
                        <span class="num" id="minutes">00</span>
                        <span class="lbl">MIN</span>
                    </div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-box">
                        <span class="num" id="seconds">00</span>
                        <span class="lbl">SEC</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Expired notice --}}
        @if (!$offer->isActive())
            <div class="offer-expired">
                ⚠️ This offer has ended.
            </div>
        @endif

        {{-- Items bar + Sort --}}
        <form method="GET" action="{{ url()->current() }}">
            <div class="items-found-bar">
                <p>{{ $products->total() }} items found</p>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:0.85rem; color:#6b7280;">Sort By</span>
                    <select name="sort" class="sort-dropdown" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to
                            Low</option>
                    </select>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="product-grid">
                @forelse($products as $product)
                    @php
                        $savings = $product->sale_price ? $product->price - $product->sale_price : 0;
                        $percent = $product->sale_price
                            ? round((($product->price - $product->sale_price) / $product->price) * 100)
                            : 0;
                        $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                        $imageUrl = isset($images[0])
                            ? route('product.image', basename($images[0]))
                            : asset('images/no-image.png');
                        $isWishlisted = auth('customer')->check()
                            ? App\Models\Wishlist::where('customer_id', auth('customer')->id())
                                ->where('product_id', $product->id)
                                ->exists()
                            : false;
                    @endphp

                    <div class="product-card">
                        {{-- Wishlist --}}
                        <button onclick="event.preventDefault(); toggleWishlistCard({{ $product->id }}, this)"
                            style="position:absolute; top:8px; right:8px; width:34px; height:34px; border-radius:50%; border:1.5px solid {{ $isWishlisted ? '#dc2626' : '#dddddd' }}; background:#fff; cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center; z-index:10;">
                            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"
                                style="color:{{ $isWishlisted ? '#dc2626' : '#9ca3af' }};"></i>
                        </button>

                        {{-- Image --}}
                        <div class="product-top-section">
                            @if ($percent > 0)
                                <span class="discount-percent-badge">-{{ $percent }}%</span>
                            @endif
                            <a href="{{ route('product.show', $product->slug) }}" class="product-img-wrap">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                            </a>
                        </div>

                        {{-- Info --}}
                        <div class="product-bottom-section">
                            <a href="{{ route('product.show', $product->slug) }}" class="product-name">
                                {{ $product->name }}
                            </a>

                            <div class="action-row">
                                @if ($savings > 0)
                                    <span class="save-amount-badge">Save ৳{{ number_format($savings) }}</span>
                                @else
                                    <div style="width:80px;"></div>
                                @endif

                                <button title="Add to Cart" onclick="addToCart(event, {{ $product->id }})"
                                    class="cart-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px; height:22px;" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l3-6H6.4M7 13L5.4 5M7 13l-1.5 3h11M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="price-row">
                                <span
                                    class="sale-price">৳{{ number_format($product->sale_price ?? $product->price) }}</span>
                                @if ($product->sale_price)
                                    <span class="old-price">৳{{ number_format($product->price) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1; text-align:center; padding:60px 20px; color:#9ca3af;">
                        <p style="font-size:1rem;">No products found in this offer.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>
        </form>

    </div>

    {{-- Countdown Timer Script --}}
    @if ($offer->show_timer)
        <script>
            const endDate = new Date("{{ $offer->end_date->toIso8601String() }}");

            function updateCountdown() {
                const now = new Date();
                const diff = endDate - now;

                if (diff <= 0) {
                    document.getElementById('days').textContent = '00';
                    document.getElementById('hours').textContent = '00';
                    document.getElementById('minutes').textContent = '00';
                    document.getElementById('seconds').textContent = '00';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        </script>
    @endif

    <script>
        function addToCart(event, productId) {
            if (event) event.preventDefault();
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
                        updateCartCount(data.count);
                        showCartPopup(productId, 1);
                    }
                });
        }

        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (badge) badge.textContent = count;
        }

        function showCartPopup(productId, qty) {
            const toast = document.getElementById('cart-toast');
            if (!toast) return;
            document.getElementById('toast-title').textContent = 'Added to Cart!';
            document.getElementById('toast-msg').textContent = `Quantity: ${qty} item(s) added successfully.`;
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function toggleWishlistCard(productId, btn) {
            const isLoggedIn = {{ auth('customer')->check() ? 'true' : 'false' }};
            if (!isLoggedIn) {
                window.location.href = '/login';
                return;
            }
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
                    const icon = btn.querySelector('i');
                    if (data.status === 'added') {
                        icon.className = 'fas fa-heart';
                        icon.style.color = '#dc2626';
                        btn.style.borderColor = '#dc2626';
                    } else {
                        icon.className = 'far fa-heart';
                        icon.style.color = '#9ca3af';
                        btn.style.borderColor = '#dddddd';
                    }
                });
        }
    </script>
@endsection
