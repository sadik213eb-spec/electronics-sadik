@extends('layouts.app')

@section('content')
    <style>
        .hero-section {
            display: flex;
            gap: 12px;
            max-width: 1440px;
            margin: 20px auto;
            padding: 0 20px;
            align-items: flex-start;
        }

        .slider-wrap {
            flex: 0 0 66.7%;
            max-width: 66.7%;
        }

        .banner-wrap {
            flex: 0 0 calc(33.3% - 12px);
            max-width: calc(33.3% - 12px);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .banner-wrap a {
            display: block;
        }

        .banner-wrap a img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        /* Active dot becomes long */
        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.4s ease;
            border-radius: 4px;
        }

        .swiper-pagination-bullet-active {
            width: 28px !important;
            background: #d97706 !important;
            border-radius: 4px;
        }

        /* Section styles */
        .home-section {
            max-width: 1440px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .view-all {
            color: #ffffff;
            background: #d97706;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 6px 16px;
            border-radius: 4px;
            border: 1px solid #f59e0b;
            transition: 0.5s all ease-in-out;
        }

        .view-all:hover {
            transform: translateY(-5px);
        }

        /* PRODUCT CARD DESIGN (Updated to match Category Page) */
        .products-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 15px;
            position: relative;
            transition: 0.3s all ease-in-out;
            text-decoration: none;
            display: block;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 12px 0px #d9770634;
            transition: 0.5s all ease-in-out;
            border-color: #d97706;
        }

        .discount-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #16a34a;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50px;
            z-index: 10;
        }

        .product-img-wrap {
            width: 100%;
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .product-img-wrap img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 40px;
            line-height: 1.4;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .sale-price {
            color: #d97706;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .old-price {
            color: #9ca3af;
            text-decoration: line-through;
            font-size: 0.9rem;
        }

        .rating-stars {
            color: #fbbf24;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .rating-count {
            color: #9ca3af;
            font-size: 0.75rem;
            margin-left: 4px;
        }

        /* Category card */
        .categories-row {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 7px;
        }

        .category-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 10px 5px;
            text-align: center;
            border: 1px solid #dddddd;
            cursor: pointer;
            transition: transform 0.5s;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .category-card:hover {
            transform: translateY(-4px);
            transition: 0.5s all ease-in-out;
            border-color: #d97706
        }

        .category-card img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .category-card p {
            color: #2a2a2a;
            font-size: 0.92rem;
            font-weight: 500;
        }

        /* Image banner section */
        .image-section {
            display: flex;
            gap: 12px;
        }

        .image-section a {
            flex: 1;
        }

        .image-section a img {
            width: 100%;
            height: 299px;
            object-fit: cover;
            border-radius: 12px;
        }

        /* Mobile */
        @media (max-width: 410px) {

            .section-title {
                font-size: 1.5rem;
                font-weight: 700;
            }

            .view-all {
                font-size: 0.8rem;
                font-weight: 500;
                padding: 5px 14px;
                border-radius: 3px;
            }

            .view-all:hover {
                transform: translateY(-3px);
            }

            .hero-section {
                gap: 7px;
                flex-direction: column;
                margin: 10px auto;
                padding: 0 20px;
            }

            .slider-wrap {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .swiper.mySwiper.swiper-initialized.swiper-horizontal.swiper-backface-hidden {
                height: auto !important;
                border-radius: 5px !important;
            }

            .swiper-slide>a>img {
                width: 100%;
                height: auto !important;
            }

            .banner-wrap {
                flex: 0 0 100%;
                max-width: 100%;
                display: flex;
                flex-direction: row;
                gap: 7px;
            }

            .banner-wrap a {
                display: block;
            }

            .banner-wrap a img {
                width: 100%;
                height: auto !important;
                object-fit: cover;
                border-radius: 5px;
                display: block;
            }


            .products-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .product-card {
                border-radius: 5px;
            }

            .product-img-wrap {
                width: 100%;
                height: 150px;
                margin-bottom: 10px;
            }

            .product-name {
                font-size: 0.7rem;
                font-weight: 600;
                margin-bottom: 7px;
                height: 32px;
                line-height: 1.4;
            }

            .price-row {
                gap: 5px;
                margin-bottom: 5px;
            }

            .sale-price {
                font-size: 1.1rem;
            }

            .old-price {
                font-size: 0.75rem;
            }

            .rating-stars {
                font-size: 0.7rem;
            }

            .rating-count {
                color: #9ca3af;
                font-size: 0.7rem;
            }

            .categories-row {
                grid-template-columns: repeat(4, 1fr);
                gap: 5px;
            }

            .category-card {
                border-radius: 5px;
                padding: 7px;

            }

            .category-card img {
                width: 60px;
                height: 60px;
                margin-bottom: 10px;
                border-radius: 0px;
            }

            .category-card p {
                font-size: 0.7rem;
            }

            .image-section {
                flex-direction: column;
            }

            .image-section a img {
                width: 100% !important;
                object-fit: contain !important;
                border-radius: 10px;
                display: block;
                height: auto !important;
            }

            .home-section .image-section {
                margin-top: -30px !important;
                margin-bottom: -15px !important;
            }
        }
    </style>

    {{-- ========== HERO: SLIDER + BANNERS ========== --}}
    <div class="hero-section">

        {{-- SLIDER --}}
        <div class="slider-wrap">
            <div class="swiper mySwiper" style="border-radius:12px; overflow:hidden; height:auto; width:100%;">
                <div class="swiper-wrapper">
                    @forelse($sliders as $slider)
                        <div class="swiper-slide">
                            <a href="{{ $slider->link ?? '#' }}">
                                <img src="{{ asset('storage/' . $slider->image) }}"
                                    style="width:100%; height:auto; object-fit:cover;">
                            </a>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div
                                style="width:100%; height:auto; background:#1a1a1a; display:flex; align-items:center; justify-content:center;">
                                <p style="color:#6b7280;">No sliders available</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        {{-- SIDE BANNERS --}}
        <div class="banner-wrap">
            @forelse($banners as $banner)
                <a href="{{ $banner->link ?? '#' }}">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner">
                </a>
            @empty
                <div style="flex:1; background:#1a1a1a; border-radius:12px; height:194px;"></div>
                <div style="flex:1; background:#1a1a1a; border-radius:12px; height:194px;"></div>
            @endforelse
        </div>

    </div>

    {{-- ========== DYNAMIC SECTIONS ========== --}}
    @foreach ($sections as $section)
        <div class="home-section">

            {{-- IMAGE SECTION --}}
            @if ($section->type === 'image')
                @php $itemCount = $section->items->count(); @endphp
                <div class="image-section" style="{{ $itemCount === 1 ? 'display:block;' : 'display:flex; gap:12px;' }}">
                    @foreach ($section->items as $item)
                        <a href="{{ $item->link ?? '#' }}" style="{{ $itemCount === 1 ? 'display:block;' : 'flex:1;' }}">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="Banner"
                                style="width:100%; height:{{ $itemCount === 1 ? 'auto' : 'auto' }}; object-fit:cover; border-radius:10px; {{ $itemCount === 1 ? 'max-width:1260px; display:block; margin:0 auto;' : '' }}">
                        </a>
                    @endforeach
                </div>

                {{-- CATEGORY LIST SECTION --}}
            @elseif($section->type === 'category_list')
                <div class="section-header">
                    <h2 class="section-title">{{ $section->title }}</h2>
                    @if ($section->url)
                        <a href="{{ $section->url }}" class="view-all">View All →</a>
                    @endif
                </div>
                <div class="categories-row">
                    @foreach ($section->items as $item)
                        @php $category = $item->category; @endphp
                        @if ($category)
                            <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                @endif
                                <p>{{ $category->name }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- SIMPLE PRODUCT SECTION --}}
            @elseif($section->type === 'simple_product')
                <div class="section-header">
                    <h2 class="section-title">{{ $section->title }}</h2>
                    @if ($section->url)
                        <a href="{{ $section->url }}" class="view-all">View All →</a>
                    @endif
                </div>
                <div class="products-row">
                    @foreach ($section->items as $item)
                        @php $product = $item->product; @endphp
                        @if ($product)
                            @php
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

                            <div class="product-card" style="position:relative;">

                                {{-- Wishlist button top right --}}
                                <button onclick="event.preventDefault(); toggleWishlistCard({{ $product->id }}, this)"
                                    class="card-wishlist-btn {{ $isWishlisted ? 'wishlisted' : '' }}"
                                    style="position:absolute; top:8px; right:8px; width:34px; height:34px; border-radius:50%; border:1.5px solid {{ $isWishlisted ? '#dc2626' : '#dddddd' }}; background:#fff; cursor:pointer; font-size:1rem; display:flex; align-items:center; justify-content:center; z-index:10;">
                                    <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"
                                        style="color:{{ $isWishlisted ? '#dc2626' : '#9ca3af' }};"></i>
                                </button>

                                <a href="/products/{{ $product->slug }}">
                                    @if ($product->sale_price && $product->price)
                                        @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                                        <span class="discount-badge">-{{ $discount }}%</span>
                                    @endif

                                    <div class="product-img-wrap">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    </div>

                                    <h3 class="product-name">{{ $product->name }}</h3>

                                    <div class="price-row">
                                        <span
                                            class="sale-price">৳{{ number_format($product->sale_price ?? $product->price) }}</span>
                                        @if ($product->sale_price)
                                            <span class="old-price">৳{{ number_format($product->price) }}</span>
                                        @endif
                                    </div>

                                    <div class="rating-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span class="rating-count">(0)</span>
                                    </div>
                                </a>

                                {{-- Add to cart button bottom right --}}
                                <button onclick="event.preventDefault(); addToCartCard({{ $product->id }})"
                                    style="position:absolute; bottom:8px; right:8px; width:34px; height:34px; border-radius:50%; border:none; background:#d97706; color:#fff; cursor:pointer; font-size:0.95rem; display:flex; align-items:center; justify-content:center; z-index:10;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>

                            </div>
                        @endif
                    @endforeach
                </div>


                {{-- OFFER PRODUCT SECTION --}}
            @elseif($section->type === 'offer_product')
                <div class="section-header">
                    <h2 class="section-title">{{ $section->title }}</h2>
                    @if ($section->url)
                        <a href="{{ $section->url }}" class="view-all">View All →</a>
                    @endif
                </div>
                <div class="products-row">
                    @foreach ($section->items as $item)
                        @php $product = $item->product; @endphp
                        @if ($product)
                            @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                            <a href="/products/{{ $product->slug }}" class="product-card" style="text-decoration:none;">
                                <span class="discount-badge"
                                    style="margin:8px; display:inline-block;">-{{ $discount }}%</span>
                                @php $images = is_array($product->images) ? $product->images : json_decode($product->images, true); @endphp
                                <img src="{{ isset($images[0]) ? asset('storage/' . $images[0]) : asset('images/no-image.png') }}"
                                    alt="{{ $product->name }}">
                                <div class="product-card-body">
                                    <p class="product-name">{{ $product->name }}</p>
                                    <div>
                                        <span class="product-price">৳{{ number_format($product->sale_price) }}</span>
                                        <span class="product-old-price">৳{{ number_format($product->price) }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
    @endforeach

    {{-- Swiper Init --}}
    <script>
        new Swiper(".mySwiper", {
            loop: true,
            speed: 800,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
        });

        function toggleWishlistCard(productId, btn) {
            @if (!auth('customer')->check())
                window.location.href = '/login';
                return;
            @endif

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

        function addToCartCard(productId) {
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
    </script>
@endsection
