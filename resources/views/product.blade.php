@extends('layouts.app')

@section('content')

    <style>
        .breadcrumb {
            max-width: 1440px;
            margin: 20px auto;
            padding: 0 20px;
            font-size: 1rem;
            font-weight: 500;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
        }

        .breadcrumb a {
            color: #2a2a2a;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #d97706;
        }

        .product-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
            background: #ffffff;
            border-radius: 12px;
        }

        /* LEFT: IMAGES */
        .main-image-wrap {
            flex: 1;
            overflow: hidden;
            border-radius: 12px;
            position: relative;
            cursor: zoom-in;
        }

        .main-image {
            width: 100%;
            object-fit: contain;
            border-radius: 12px;
            background: #fff;
            padding: 12px;
            display: block;
            transition: transform 0.3s ease;
            transform-origin: center center;
        }

        .product-images {
            display: flex;
            gap: 10px;
        }

        .thumbnails {
            display: flex;
            flex-direction: column;
            gap: 8px;
            order: -1;
        }

        .thumbnail {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #dddddd;
            cursor: pointer;
            background: #ffffff;
            padding: 4px;
            transition: border-color 0.2s;
            flex-shrink: 0;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #d97706;
        }

        /* RIGHT: INFO */
        .product-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 10px;
            line-height: 1.25;
        }

        .product-brand-line {
            color: #2a2a2a;
            font-size: 1rem;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 5px;
        }

        .product-brand-line strong {
            color: #d97706;
            font-weight: 700;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 5px;
        }

        .stars {
            color: #d97706;
            font-size: 1.25rem;
        }

        .rating-count {
            color: #2a2a2a;
            font-size: 1rem;
        }

        .price-section {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .sale-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .original-price {
            font-size: 1rem;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .discount-badge-lg {
            background: #d97706;
            color: #fff;
            font-size: 0.8rem;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .info-label {
            color: #2a2a2a;
            min-width: 80px;
            flex-shrink: 0;
        }

        .info-value {
            color: #2a2a2a;
            font-weight: 500;
        }

        .in-stock {
            color: #16a34a;
            font-weight: 600;
        }

        .out-stock {
            color: #dc2626;
            font-weight: 600;
        }

        .divider {
            border: none;
            border-top: 1px solid #dddddd;
            margin: 16px 0;
        }

        /* ONE ROW: Qty + Cart + Buy */
        .order-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            flex-shrink: 0;
        }

        .qty-btn {
            width: 36px;
            height: 44px;
            border: none;
            background: #dddddd;
            cursor: pointer;
            font-size: 1rem;
            color: #2a2a2a;
            transition: 0.5s all ease-in-out;
        }

        .qty-btn:hover {
            background: #d97706;
            color: #ffffff;
        }

        .qty-input {
            width: 50px;
            height: 44px;
            border: none;
            border-left: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
            text-align: center;
            font-size: 0.95rem;
            color: #2a2a2a;
        }

        .btn-cart {
            flex: 1;
            padding: 12px 16px;
            background: #d97706;
            color: #ffffff;
            border: 1.5px solid #d97706;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.5s ease-in-out;
            white-space: nowrap;
        }

        .btn-cart:hover {
            background: transparent;
            color: #d97706;
        }

        .btn-buy {
            flex: 1;
            padding: 12px 16px;
            background: #2a2a2a;
            color: #ffffff;
            border: 1.5px solid #2a2a2a;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.5s ease-in-out;
            white-space: nowrap;
        }

        .btn-buy:hover {
            background: transparent;
            color: #2a2a2a;
        }

        /* Share */
        .share-section {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 500;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            flex-wrap: wrap;
        }

        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .social-btn:hover {
            opacity: 0.75;
        }

        .copy-link-btn {
            background: transparent;
            border: 1px solid #dddddd;
            padding: 8px 14px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            color: #2a2a2a;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .copy-link-btn:hover {
            background: #f3f4f6;
        }

        /* TABS */
        .tabs-section {
            max-width: 1440px;
            margin: 40px auto 0;
            padding: 20px;
            display: grid;
            border-radius: 12px;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
            background: #ffffff;
        }

        .tabs-header {
            display: flex;
            border-bottom: 2px solid #dddddd;
            margin-bottom: 0;
            gap: 12px;
        }

        .tab-btn {
            padding: 10px 24px;
            background: transparent;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            border: 1.5px solid #2a2a2a;
            transition: all 0.3s ease-in-out;
            border-radius: 5px;
            margin-bottom: 16px;
        }

        .tab-btn.active {
            color: #ffffff;
            border-color: #d97706;
            background: #d97706;
        }

        .tab-content {
            display: none;
            padding: 16px 0;
        }

        .tab-content.active {
            display: block;
        }

        /* Description content styling */
        .tab-content h1,
        .tab-content h2,
        .tab-content h3 {
            font-family: 'Montserrat', sans-serif;
            color: #d97706;
            margin: 16px 0 8px;
        }

        .tab-content p {
            font-family: 'Poppins', sans-serif;
            color: #2a2a2a;
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 5px;
        }

        .tab-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }

        .tab-content table td,
        .tab-content table th {
            padding: 8px 12px;
            border: 1px solid #dddddd;
            color: #2a2a2a;
        }

        .tab-content table tr:nth-child(even) {
            background: #f9fafb;
        }

        .tab-content ul,
        .tab-content ol {
            padding-left: 20px;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            color: #2a2a2a;
            line-height: 1.8;
        }

        /* Related products */
        .related-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #d97706;
        }

        .related-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .related-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dddddd;
            text-decoration: none;
            transition: transform 0.2s ease-in-out;
            display: block;
            padding: 5px;
        }

        .related-card:hover {
            transform: translateY(-3px);
        }

        .related-card img {
            width: 100%;
            height: 260px;
            object-fit: contain;
            background: #ffffff;
            display: block;
        }

        .related-card-body {
            padding: 10px 12px 12px;
        }

        .related-discount {
            background: #d97706;
            color: #fff;
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .related-card-name {
            font-size: 1rem;
            color: #2a2a2a;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .related-card-price {
            color: #d97706;
            font-weight: 600;
            font-size: 1.1rem;
            font-family: 'Montserrat', sans-serif;
        }

        .related-card-old {
            color: #9ca3af;
            font-size: 0.9rem;
            text-decoration: line-through;
            margin-left: 6px;
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .product-wrapper {
                grid-template-columns: 1fr;
            }

            .tabs-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .order-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>


    {{-- BREADCRUMB --}}
    <div class="breadcrumb">
        <a href="/">Home</a> &rsaquo;
        @if ($product->category)
            @if ($product->category->parent)
                <a href="/products?category={{ $product->category->parent->id }}">
                    {{ $product->category->parent->name }}
                </a> &rsaquo;
            @endif
            <a href="/products?category={{ $product->category->id }}">
                {{ $product->category->name }}
            </a>
        @endif
    </div>

    {{-- PRODUCT SECTION --}}
    <div class="product-wrapper">

        {{-- LEFT: IMAGES --}}
        <div class="product-images">
            @php
                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                $firstImage = isset($images[0])
                    ? route('product.image', basename($images[0]))
                    : asset('images/no-image.png');
            @endphp

            {{-- Thumbnails on LEFT (vertical) --}}
            <div class="thumbnails">
                @foreach ($images ?? [] as $image)
                    <img src="{{ route('product.image', basename($image)) }}"
                        class="thumbnail {{ $loop->first ? 'active' : '' }}"
                        onmouseover="changeImage(this, '{{ route('product.image', basename($image)) }}')" alt="thumbnail">
                @endforeach
            </div>

            {{-- Main image on RIGHT --}}
            <div class="main-image-wrap">
                <img id="mainImage" src="{{ $firstImage }}" class="main-image" alt="{{ $product->name }}">
            </div>
        </div>

        {{-- RIGHT: INFO --}}
        <div class="product-info">
            {{-- Title --}}
            <h1 class="product-title">{{ $product->name }}</h1>

            {{-- Brand --}}
            @if ($product->brand)
                <p class="product-brand-line">
                    Brand: <strong>{{ $product->brand->name }}</strong>
                </p>
            @endif

            {{-- Rating --}}
            <div class="product-rating">
                <span class="stars">★★★★★</span>
                <span class="rating-count">(0 Reviews)</span>
            </div>

            {{-- Price --}}
            <div class="price-section">
                <span class="sale-price">৳{{ number_format($product->sale_price ?? $product->price) }}</span>
                @if ($product->sale_price)
                    <span class="original-price">৳{{ number_format($product->price) }}</span>
                    @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                    <span class="discount-badge-lg">-{{ $discount }}%</span>
                @endif

                {{-- Wishlist Button --}}
                <button id="wishlist-btn" onclick="toggleWishlist({{ $product->id }})"
                    style="margin-left:auto; width:44px; height:44px; border-radius:50%; border:1.5px solid #dddddd; background:#fff; cursor:pointer; font-size:1.3rem; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">
                    <i id="wishlist-icon" class="far fa-heart" style="color:#9ca3af;"></i>
                </button>
            </div>

            <hr class="divider">

            {{-- Stock --}}
            <div class="info-row">
                <span class="info-label">Status:</span>
                @if ($product->stock_status === 'in_stock')
                    <span class="in-stock">✓ In Stock</span>
                @else
                    <span class="out-stock">✗ Out of Stock</span>
                @endif
            </div>

            {{-- SKU --}}
            @if ($product->sku)
                <div class="info-row">
                    <span class="info-label">SKU:</span>
                    <span class="info-value">{{ $product->sku }}</span>
                </div>
            @endif

            {{-- Warranty --}}
            @if ($product->warranty)
                <div class="info-row">
                    <span class="info-label">Warranty:</span>
                    <span class="info-value">{{ $product->warranty }}</span>
                </div>
            @endif

            <hr class="divider">

            {{-- ONE ROW: Qty + Cart + Buy --}}
            <div class="order-row">
                <div class="qty-controls">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input type="number" id="quantity" class="qty-input" value="1" min="1">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
                <button class="btn-cart" onclick="addToCart({{ $product->id }})">
                    🛒 Add To Cart
                </button>
                <button class="btn-buy" onclick="buyNow({{ $product->id }})">
                    ⚡ Buy Now
                </button>
            </div>

            {{-- Share --}}
            <div class="share-section">
                <span>Share:</span>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                    class="social-btn" style="background:#1877f2;">
                    <i class="fab fa-facebook-f"></i>
                </a>

                <a href="https://www.facebook.com/dialog/send?link={{ urlencode(request()->url()) }}" target="_blank"
                    class="social-btn" style="background:#0084ff;">
                    <i class="fab fa-facebook-messenger"></i>
                </a>

                <a href="https://wa.me/?text={{ urlencode(request()->url()) }}" target="_blank" class="social-btn"
                    style="background:#25d366;">
                    <i class="fab fa-whatsapp"></i>
                </a>

                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                    target="_blank" class="social-btn" style="background:#0077b5;">
                    <i class="fab fa-linkedin-in"></i>
                </a>

                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank"
                    class="social-btn" style="background:#000;">
                    <i class="fab fa-x-twitter"></i>
                </a>

                <button class="copy-link-btn social-btn" onclick="copyLink()">
                    <i class="far fa-copy"></i>
                </button>
            </div>
        </div>

    </div>

    {{-- TABS + RELATED --}}
    <div class="tabs-section">

        {{-- LEFT: TABS --}}
        <div>
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('description', this)">Description</button>
                <button class="tab-btn" onclick="switchTab('reviews', this)">Reviews</button>
            </div>

            <div id="tab-description" class="tab-content active">
                {!! $product->description !!}
            </div>

            <div id="tab-reviews" class="tab-content">
                <style>
                    .review-section {
                        font-family: 'Poppins', sans-serif;
                    }

                    .review-summary {
                        display: flex;
                        align-items: center;
                        gap: 30px;
                        background: #ffffff;
                        border: 1px solid #dddddd;
                        border-radius: 14px;
                        padding: 20px;
                        margin-bottom: 20px;
                    }

                    .avg-score {
                        text-align: center;
                        flex-shrink: 0;
                    }

                    .avg-number {
                        font-size: 2.5rem;
                        font-weight: 700;
                        color: #2a2a2a;
                        font-family: 'Montserrat', sans-serif;
                        line-height: 1;
                    }

                    .avg-stars {
                        font-size: 1.1rem;
                        margin: 4px 0;
                    }

                    .avg-count {
                        font-size: 0.9rem;
                        color: #9ca3af;
                    }

                    .rating-bars {
                        flex: 1;
                    }

                    .rating-bar-row {
                        display: flex;
                        align-items: center;
                        gap: 5px;
                        margin-bottom: 5px;
                        font-size: 0.8rem;
                        color: #6b7280;
                    }

                    .rating-bar-row span:first-child {
                        width: 16px;
                        text-align: right;
                        flex-shrink: 0;
                    }

                    .bar-track {
                        flex: 1;
                        height: 8px;
                        background: #f3f4f6;
                        border-radius: 50px;
                        overflow: hidden;
                    }

                    .bar-fill {
                        height: 100%;
                        background: #d97706;
                        border-radius: 50px;
                        transition: width 0.6s ease;
                    }

                    .bar-count {
                        width: 20px;
                        text-align: left;
                        flex-shrink: 0;
                    }

                    .reviews-list {
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                        margin-bottom: 10px;
                    }

                    .review-card {
                        background: #fff;
                        border: 1px solid #dddddd;
                        border-radius: 10px;
                        padding: 16px;
                    }

                    .review-card-top {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        margin-bottom: 10px;
                        flex-wrap: wrap;
                        gap: 8px;
                    }

                    .reviewer-info {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .reviewer-avatar {
                        width: 42px;
                        height: 42px;
                        border-radius: 50%;
                        background: #d97706;
                        color: #fff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.1rem;
                        font-weight: 700;
                        flex-shrink: 0;
                    }

                    .reviewer-name {
                        font-size: 0.9rem;
                        font-weight: 600;
                        color: #2a2a2a;
                    }

                    .review-date {
                        font-size: 0.78rem;
                        color: #9ca3af;
                        margin-top: 2px;
                    }

                    .review-stars {
                        font-size: 1rem;
                        color: #d97706;
                    }

                    .review-comment {
                        font-size: 0.88rem;
                        color: #4b5563;
                        line-height: 1.7;
                        margin-top: 8px;
                    }

                    .review-image {
                        margin-top: 10px;
                        border-radius: 5px;
                        max-height: 100px;
                        object-fit: cover;
                        border: 1px solid #dddddd;
                    }

                    .no-reviews {
                        text-align: center;
                        padding: 40px 20px;
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        color: #9ca3af;
                        margin-bottom: 16px;
                    }

                    .no-reviews p {
                        font-size: 0.9rem;
                        margin-top: 8px;
                    }

                    .success-msg {
                        background: #dcfce7;
                        border: 1px solid #bbf7d0;
                        color: #16a34a;
                        border-radius: 8px;
                        padding: 12px 16px;
                        font-size: 0.9rem;
                        margin-bottom: 16px;
                    }

                    /* Star selector */
                    .star-selector {
                        display: flex;
                        gap: 6px;
                        margin-top: 8px;
                        flex-direction: row-reverse;
                        justify-content: flex-end;
                    }

                    .star-selector input {
                        display: none;
                    }

                    .star-selector label {
                        font-size: 2rem;
                        color: #dddddd;
                        cursor: pointer;
                        transition: color 0.15s;
                    }

                    .star-selector input:checked~label,
                    .star-selector label:hover,
                    .star-selector label:hover~label {
                        color: #d97706;
                    }

                    /* Modal */
                    .review-modal-overlay {
                        display: none;
                        position: fixed;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.55);
                        z-index: 9999;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                    }

                    .review-modal-overlay.open {
                        display: flex;
                    }

                    .review-modal-box {
                        background: #fff;
                        border-radius: 16px;
                        padding: 32px;
                        width: 100%;
                        max-width: 560px;
                        max-height: 90vh;
                        overflow-y: auto;
                        position: relative;
                        animation: modalIn 0.2s ease;
                    }

                    @keyframes modalIn {
                        from {
                            transform: scale(0.95);
                            opacity: 0;
                        }

                        to {
                            transform: scale(1);
                            opacity: 1;
                        }
                    }

                    .modal-close {
                        position: absolute;
                        top: 14px;
                        right: 16px;
                        background: none;
                        border: none;
                        font-size: 1.4rem;
                        cursor: pointer;
                        color: #9ca3af;
                        line-height: 1;
                    }

                    .modal-close:hover {
                        color: #dc2626;
                    }

                    .modal-title {
                        font-size: 1.05rem;
                        font-weight: 700;
                        color: #2a2a2a;
                        font-family: 'Montserrat', sans-serif;
                        margin-bottom: 20px;
                        padding-bottom: 12px;
                        border-bottom: 2px solid #d97706;
                    }

                    .modal-form-group {
                        margin-bottom: 14px;
                    }

                    .modal-label {
                        font-size: 0.85rem;
                        font-weight: 500;
                        color: #2a2a2a;
                        display: block;
                        margin-bottom: 6px;
                    }

                    .modal-label span {
                        color: #dc2626;
                    }

                    .modal-input {
                        width: 100%;
                        padding: 10px 14px;
                        border: 1px solid #dddddd;
                        border-radius: 8px;
                        font-size: 0.9rem;
                        font-family: 'Poppins', sans-serif;
                        color: #2a2a2a;
                        outline: none;
                        transition: border-color 0.2s;
                        background: #fff;
                        box-sizing: border-box;
                    }

                    .modal-input:focus {
                        border-color: #d97706;
                    }

                    .modal-submit-btn {
                        padding: 12px 28px;
                        background: #d97706;
                        color: #fff;
                        border: none;
                        border-radius: 8px;
                        font-size: 0.95rem;
                        font-weight: 600;
                        font-family: 'Montserrat', sans-serif;
                        cursor: pointer;
                        transition: background 0.2s;
                    }

                    .modal-submit-btn:hover {
                        background: #b45309;
                    }

                    @media (max-width: 640px) {
                        .review-summary {
                            flex-direction: column;
                            gap: 16px;
                        }
                    }
                </style>

                <div class="review-section">

                    @if (session('review_success'))
                        <div class="success-msg">✅ {{ session('review_success') }}</div>
                    @endif

                    {{-- ── Top bar: summary + write button ── --}}
                    <div
                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
                        <p style="font-size:1rem; font-weight:700; color:#2a2a2a; font-family:'Montserrat',sans-serif;">
                            Customer Reviews
                            @if ($reviewCount > 0)
                                <span
                                    style="font-size:0.85rem; color:#9ca3af; font-weight:400;">({{ $reviewCount }})</span>
                            @endif
                        </p>
                        <button onclick="document.getElementById('review-modal').classList.add('open')"
                            style="padding:10px 20px; background:#d97706; color:#fff; border:none; border-radius:8px; font-size:0.88rem; font-weight:600; font-family:'Montserrat',sans-serif; cursor:pointer; transition:background 0.2s;"
                            onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                            ✍️ Write a Review
                        </button>
                    </div>

                    {{-- ── Rating Summary ── --}}
                    @if ($reviewCount > 0)
                        <div class="review-summary">
                            <div class="avg-score">
                                <div class="avg-number">{{ number_format($averageRating, 1) }}</div>
                                <div class="avg-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= round($averageRating) ? '⭐' : '☆' }}
                                    @endfor
                                </div>
                                <div class="avg-count">{{ $reviewCount }} review{{ $reviewCount > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="rating-bars">
                                @for ($star = 5; $star >= 1; $star--)
                                    @php
                                        $count = $reviews->where('rating', $star)->count();
                                        $percent = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                                    @endphp
                                    <div class="rating-bar-row">
                                        <span>{{ $star }}</span>
                                        <span style="font-size:0.9rem;">⭐</span>
                                        <div class="bar-track">
                                            <div class="bar-fill" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="bar-count">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endif

                    {{-- ── Review Cards ── --}}
                    @if ($reviewCount > 0)
                        <div class="reviews-list">
                            @foreach ($reviews as $review)
                                <div class="review-card">
                                    <div class="review-card-top">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                {{ strtoupper(substr($review->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="reviewer-name">{{ $review->name }}</p>
                                                <p class="review-date">{{ $review->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="review-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                {{ $i <= $review->rating ? '⭐' : '☆' }}
                                            @endfor
                                        </div>
                                    </div>

                                    @if ($review->comment)
                                        <p class="review-comment">{{ $review->comment }}</p>
                                    @endif

                                    @if ($review->image)
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Review image"
                                            class="review-image" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-reviews">
                            ⭐
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ══════════════════════════════════════
     REVIEW MODAL — outside tab-content div
     ══════════════════════════════════════ --}}
            <div id="review-modal" class="review-modal-overlay"
                onclick="if(event.target===this) this.classList.remove('open')">
                <div class="review-modal-box">

                    <button class="modal-close"
                        onclick="document.getElementById('review-modal').classList.remove('open')">✕</button>

                    <h3 class="modal-title">✍️ Write a Review</h3>

                    <form action="{{ route('product.review.store', $product->slug) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Star Rating --}}
                        <div class="modal-form-group">
                            <label class="modal-label">Your Rating <span>*</span></label>
                            <div class="star-selector">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="mstar{{ $i }}"
                                        value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="mstar{{ $i }}">★</label>
                                @endfor
                            </div>
                            @error('rating')
                                <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Name --}}
                        <div class="modal-form-group">
                            <label class="modal-label">Your Name <span>*</span></label>
                            <input type="text" name="name" class="modal-input"
                                value="{{ old('name', auth('customer')->user()->name ?? '') }}"
                                placeholder="Enter your name">
                            @error('name')
                                <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="modal-form-group">
                            <label class="modal-label">Your Review</label>
                            <textarea name="comment" class="modal-input" rows="4"
                                placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Photo --}}
                        <div class="modal-form-group">
                            <label class="modal-label">Photo (Optional)</label>
                            <input type="file" name="image" class="modal-input" accept="image/*">
                            @error('image')
                                <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="modal-submit-btn">Submit Review</button>
                        <p style="font-size:0.78rem; color:#9ca3af; margin-top:8px;">
                            Your review will be visible after approval.
                        </p>
                    </form>
                </div>
            </div>

            {{-- Auto open modal if validation errors --}}
            @if ($errors->has('rating') || $errors->has('name') || $errors->has('comment') || $errors->has('image'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('review-modal').classList.add('open');
                        // Switch to reviews tab
                        switchTab('reviews', document.querySelector('[onclick*="reviews"]'));
                    });
                </script>
            @endif
        </div>


        {{-- RIGHT: RELATED PRODUCTS --}}
        <div>
            <p class="related-title">Frequently Bought Together</p>
            <div class="related-grid">
                @foreach ($related->take(5) as $rel)
                    @php
                        $relImages = is_array($rel->images) ? $rel->images : json_decode($rel->images, true);
                        $relImage = isset($relImages[0])
                            ? route('product.image', basename($relImages[0]))
                            : asset('images/no-image.png');
                        $relDiscount = $rel->sale_price
                            ? round((($rel->price - $rel->sale_price) / $rel->price) * 100)
                            : null;
                    @endphp
                    <a href="/products/{{ $rel->slug }}" class="related-card">
                        @if ($relDiscount)
                            <div style="padding:8px 8px 0;">
                                <span class="related-discount">-{{ $relDiscount }}%</span>
                            </div>
                        @endif
                        <img src="{{ $relImage }}" alt="{{ $rel->name }}">
                        <div class="related-card-body">
                            <p class="related-card-name">{{ $rel->name }}</p>
                            <div>
                                <span
                                    class="related-card-price">৳{{ number_format($rel->sale_price ?? $rel->price) }}</span>
                                @if ($rel->sale_price)
                                    <span class="related-card-old">৳{{ number_format($rel->price) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>

        <!-- Cart Success Toast -->
        <div id="cart-toast"
            class="fixed bottom-5 right-5 bg-white border-l-4 border-amber-600 shadow-2xl p-4 rounded-lg transform translate-y-20 opacity-0 transition-all duration-300 ease-in-out z-9999 flex items-center gap-4"
            style="min-width: 300px;">
            <div class="bg-amber-100 text-amber-600 p-2 rounded-full">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-800" id="toast-title">Added to Cart!</p>
                <p class="text-xs text-gray-500" id="toast-msg">The product has been added successfully.</p>
            </div>
            <a href="/cart" class="ml-auto text-xs font-bold text-amber-600 hover:underline">View Cart →</a>
        </div>


        <script>
            function changeImage(thumb, url) {
                document.getElementById('mainImage').src = url;
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            }

            function changeQty(val) {
                const input = document.getElementById('quantity');
                const newVal = parseInt(input.value) + val;
                if (newVal >= 1) input.value = newVal;
            }

            function switchTab(tab, btn) {
                document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('tab-' + tab).classList.add('active');
                btn.classList.add('active');
            }

            function copyLink() {
                navigator.clipboard.writeText(window.location.href);
                alert('Link copied!');
            }

            // Zoom on hover
            const mainWrap = document.querySelector('.main-image-wrap');
            const mainImg = document.getElementById('mainImage');

            mainWrap.addEventListener('mousemove', function(e) {
                const rect = mainWrap.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                mainImg.style.transformOrigin = `${x}% ${y}%`;
                mainImg.style.transform = 'scale(2)';
            });

            mainWrap.addEventListener('mouseleave', function() {
                mainImg.style.transform = 'scale(1)';
                mainImg.style.transformOrigin = 'center center';
            });

            function addToCart(productId) {
                const qty = document.getElementById('quantity').value;
                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: parseInt(qty)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount(data.count);
                            showCartPopup(productId, qty);
                        }
                    })
                    .catch(err => console.error('Cart error:', err));
            }

            function buyNow(productId) {
                const qty = document.getElementById('quantity').value;
                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: parseInt(qty)
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount(data.count);
                            window.location.href = '/cart';
                        }
                    });
            }

            function updateCartCount(count) {
                const badge = document.getElementById('cart-count');
                if (badge) badge.textContent = count;
            }

            function showCartPopup(productId, qty) {
                const toast = document.getElementById('cart-toast');
                document.getElementById('toast-title').textContent = 'Added to Cart!';
                document.getElementById('toast-msg').textContent = `Quantity: ${qty} item(s) added successfully.`;
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }

            // Wishlist status on page load
            @auth('customer')
                const isWishlisted =
                    {{ App\Models\Wishlist::where('customer_id', auth('customer')->id())->where('product_id', $product->id)->exists()? 'true': 'false' }};
                if (isWishlisted) {
                    document.getElementById('wishlist-icon').className = 'fas fa-heart';
                    document.getElementById('wishlist-icon').style.color = '#dc2626';
                    document.getElementById('wishlist-btn').style.borderColor = '#dc2626';
                }
            @endauth

            function toggleWishlist(productId) {
                @if (!auth('customer')->check())
                    window.location.href = '/login?redirect=/products/{{ $product->slug }}';
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
                        const icon = document.getElementById('wishlist-icon');
                        const btn = document.getElementById('wishlist-btn');
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
