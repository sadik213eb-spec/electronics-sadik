@extends('layouts.app')

@section('content')
    <style>
        .category-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .main-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #d97706;
            margin-bottom: 20px;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            align-items: start;
        }

        .products-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .items-found-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #dddddd;
        }

        .items-found-text h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: #2a2a2a;
            margin: 0;
        }

        .items-found-text p {
            font-size: 0.85rem;
            color: #9ca3af;
            margin: 0;
        }

        .filter-sidebar {
            background: #fff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 20px;
            position: sticky;
            top: 80px;
        }

        .filter-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2a2a2a;
        }

        .filter-group {
            margin-bottom: 25px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 15px;
        }

        .filter-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 10px;
            display: block;
        }

        .price-inputs {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .price-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #dddddd;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .apply-btn {
            width: 100%;
            background: #2a2a2a;
            color: #fff;
            padding: 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .apply-btn:hover {
            background: #d97706;
        }

        .brand-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .brand-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #2a2a2a;
            margin-bottom: 8px;
            cursor: pointer;
        }

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
            transition: 0.5s all ease-in-out;
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
            font-size: 0.85rem;
            font-weight: 500;
            color: #2a2a2a;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            text-decoration: none;
            transition: 0.3s all ease-in-out;
            height: 35px;
        }

        .product-name:hover { color: #2a2a2a90; }

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
            flex-wrap: wrap;
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

        .sort-dropdown {
            padding: 6px 12px;
            border: 1px solid #dddddd;
            border-radius: 6px;
            font-size: 0.85rem;
            outline: none;
            cursor: pointer;
        }

        .pagination-wrap {
            margin: 40px 0;
            display: flex;
            justify-content: center;
        }

        .empty-search {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #dddddd;
        }

        .empty-search i {
            font-size: 3rem;
            color: #dddddd;
            margin-bottom: 16px;
            display: block;
        }

        .empty-search h3 {
            font-size: 1.1rem;
            color: #2a2a2a;
            margin-bottom: 8px;
            font-family: 'Montserrat', sans-serif;
        }

        .empty-search p {
            font-size: 0.9rem;
            color: #9ca3af;
        }

        /* ── Mobile Filter Bar ── */
        .mobile-filter-bar {
            display: none;
        }

        /* ── Filter Drawer ── */
        .filter-drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
        }

        .filter-drawer-overlay.open { display: block; }

        .filter-drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 20px 20px 0 0;
            padding: 20px;
            z-index: 9999;
            max-height: 85vh;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .filter-drawer.open { transform: translateY(0); }

        .drawer-handle {
            width: 40px;
            height: 4px;
            background: #dddddd;
            border-radius: 50px;
            margin: 0 auto 16px;
        }

        .drawer-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #2a2a2a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .drawer-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #9ca3af;
            cursor: pointer;
        }

        /* ── Mobile Responsive ── */
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }

            .filter-sidebar {
                display: none;
                position: static;
            }

            .mobile-filter-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                margin-bottom: 16px;
            }

            .mobile-filter-btn {
                flex: 1;
                padding: 10px;
                background: #2a2a2a;
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                font-family: 'Poppins', sans-serif;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .mobile-sort-wrap {
                flex: 1;
            }

            .mobile-sort-wrap select {
                width: 100%;
                padding: 10px;
                border: 1px solid #dddddd;
                border-radius: 8px;
                font-size: 0.9rem;
                font-family: 'Poppins', sans-serif;
                outline: none;
                cursor: pointer;
                background: #fff;
            }

            .items-found-bar .flex {
                display: none;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-top-section {
                height: 160px;
            }

            .sale-price {
                font-size: 1rem;
            }

            .product-name {
                font-size: 0.78rem;
            }

            .cart-btn {
                width: 32px;
                height: 32px;
            }

            .cart-btn svg {
                width: 16px !important;
                height: 16px !important;
            }
        }
    </style>

    <div class="category-container">

        {{-- BREADCRUMB --}}
        <div class="text-base text-gray-500 mb-2 mt-6">
            <a href="/" class="hover:text-amber-600">Home</a> &rsaquo;
            <span class="text-gray-800 font-medium">Search results for "{{ $query }}"</span>
        </div>

        {{-- MAIN TITLE --}}
        <h1 class="main-title">Search results for "{{ $query }}"</h1>

        {{-- ── Mobile Filter Bar (outside form) ── --}}
        <div class="mobile-filter-bar">
            <button type="button" class="mobile-filter-btn" onclick="openFilterDrawer()">
                <i class="fas fa-sliders-h"></i> Filter
            </button>
            <div class="mobile-sort-wrap">
                <form method="GET" action="{{ url()->current() }}" id="mobile-sort-form">
                    <input type="hidden" name="q" value="{{ $query }}">
                    @foreach(request()->except(['sort','q']) as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach
                    <select name="sort" onchange="document.getElementById('mobile-sort-form').submit()">
                        <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Newest</option>
                        <option value="price_low"  {{ request('sort') == 'price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- ── Filter Drawer Overlay (outside form) ── --}}
        <div class="filter-drawer-overlay" id="drawer-overlay" onclick="closeFilterDrawer()"></div>

        {{-- ── Filter Drawer (outside form) ── --}}
        <div class="filter-drawer" id="filter-drawer">
            <div class="drawer-handle"></div>
            <div class="drawer-title">
                <i class="fas fa-filter"></i> Filters
                <button class="drawer-close" onclick="closeFilterDrawer()">✕</button>
            </div>
            <form method="GET" action="{{ url()->current() }}">
                <input type="hidden" name="q" value="{{ $query }}">
                <div class="filter-group">
                    <span class="filter-label">Filter by price</span>
                    <div class="price-inputs">
                        <input type="number" name="min_price" class="price-input" placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="price-input" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" class="apply-btn">Apply</button>
                </div>
                <div class="filter-group" style="border:none">
                    <span class="filter-label">By Brand</span>
                    <div class="brand-list">
                        @foreach ($brands as $brand)
                            <label class="brand-item">
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                    {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                {{ $brand->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        {{-- ── Main Form ── --}}
        <form method="GET" action="{{ url()->current() }}">
            <input type="hidden" name="q" value="{{ $query }}">

            <div class="main-layout">

                {{-- LEFT SIDEBAR (desktop) --}}
                <aside class="filter-sidebar">
                    <div class="filter-title">
                        <i class="fas fa-filter"></i> Filters
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Filter by price</span>
                        <div class="price-inputs">
                            <input type="number" name="min_price" class="price-input" placeholder="Min" value="{{ request('min_price') }}">
                            <input type="number" name="max_price" class="price-input" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" class="apply-btn">Apply</button>
                    </div>
                    <div class="filter-group" style="border:none">
                        <span class="filter-label">By Brand</span>
                        <div class="brand-list">
                            @foreach ($brands as $brand)
                                <label class="brand-item">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    {{ $brand->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </aside>

                {{-- RIGHT COLUMN --}}
                <div class="products-column">

                    <div class="items-found-bar">
                        <div class="items-found-text">
                            <h2>Search: "{{ $query }}"</h2>
                            <p>{{ $products->total() }} items found</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-500">Sort By:</span>
                            <select name="sort" class="sort-dropdown" onchange="this.form.submit()">
                                <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Newest</option>
                                <option value="price_low"  {{ request('sort') == 'price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    {{-- PRODUCT GRID --}}
                    @if ($products->isEmpty())
                        <div class="empty-search">
                            <i class="fas fa-search"></i>
                            <h3>No products found</h3>
                            <p>Try searching with different keywords</p>
                        </div>
                    @else
                        <div class="product-grid">
                            @foreach ($products as $product)
                                @php
                                    $savings = $product->sale_price ? $product->price - $product->sale_price : 0;
                                    $percent = $product->sale_price
                                        ? round((($product->price - $product->sale_price) / $product->price) * 100)
                                        : 0;
                                    $images = is_array($product->images)
                                        ? $product->images
                                        : json_decode($product->images, true);
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

                                    {{-- Top Section --}}
                                    <div class="product-top-section">
                                        @if ($percent > 0)
                                            <span class="discount-percent-badge">-{{ $percent }}%</span>
                                        @endif
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-img-wrap">
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>

                                    {{-- Bottom Section --}}
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
                                            <button title="Add to Cart" onclick="addToCart(event, {{ $product->id }})" class="cart-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px; height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l3-6H6.4M7 13L5.4 5M7 13l-1.5 3h11M9 21a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="price-row">
                                            <span class="sale-price">৳{{ number_format($product->sale_price ?? $product->price) }}</span>
                                            @if ($product->sale_price)
                                                <span class="old-price">৳{{ number_format($product->price) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pagination-wrap">
                            {{ $products->links() }}
                        </div>
                    @endif

                </div>{{-- end products-column --}}
            </div>{{-- end main-layout --}}
        </form>

    </div>{{-- end category-container --}}

    <script>
        function openFilterDrawer() {
            document.getElementById('filter-drawer').classList.add('open');
            document.getElementById('drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeFilterDrawer() {
            document.getElementById('filter-drawer').classList.remove('open');
            document.getElementById('drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function addToCart(event, productId) {
            if (event) event.preventDefault();
            const qty = 1;
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.count);
                    showCartPopup(productId, qty);
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
            document.getElementById('toast-msg').textContent = `${qty} item(s) added successfully.`;
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function toggleWishlistCard(productId, btn) {
            const isLoggedIn = {{ auth('customer')->check() ? 'true' : 'false' }};
            if (!isLoggedIn) { window.location.href = '/login'; return; }
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
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
