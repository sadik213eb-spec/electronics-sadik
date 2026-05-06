<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wholesale Electronics</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #ededed;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .section-title,
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav style="background:#1a1a1a; border-bottom:1px solid #2d2d2d; position:sticky; top:0; z-index:999;">
        <div
            style="max-width:1440px; margin:0 auto; padding:0 20px; display:flex; align-items:center; justify-content:space-between; height:65px;">

            <!-- Logo -->
            <a href="/" style="font-size:1.4rem; font-weight:800; color:#f59e0b; text-decoration:none;">
                ⚡ Wholesale Electronics
            </a>

            <!-- Nav Links -->
            <div style="display:flex; gap:24px; align-items:center;">
                <a href="/" style="color:#d1d5db; text-decoration:none; font-size:0.95rem;">Home</a>
                <a href="/products" style="color:#d1d5db; text-decoration:none; font-size:0.95rem;">Products</a>
                <a href="/categories" style="color:#d1d5db; text-decoration:none; font-size:0.95rem;">Categories</a>
                <a href="/contact" style="color:#d1d5db; text-decoration:none; font-size:0.95rem;">Contact</a>
            </div>

            <!-- Cart -->
            <a href="/cart"
                style="background:#d97706; color:#fff; padding:8px 18px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9rem; position:relative;">
                🛒 Cart
                <span id="cart-count"
                    style="position:absolute; top:-8px; right:-8px; background:#dc2626; color:#fff; border-radius:50%; width:20px; height:20px; font-size:0.7rem; display:flex; align-items:center; justify-content:center;">
                    0
                </span>
            </a>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer style="background:#1a1a1a; border-top:1px solid #2d2d2d; margin-top:60px; padding:40px 20px;">
        <div style="max-width:1440px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:32px;">

            <!-- Brand -->
            <div>
                <p style="color:#f59e0b; font-size:1.2rem; font-weight:700; margin-bottom:12px;">⚡ Wholesale Electronics
                </p>
                <p style="color:#6b7280; font-size:0.85rem; line-height:1.6;">Your trusted online store for authentic
                    electronics & home appliances.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <p style="color:#fff; font-weight:600; margin-bottom:12px;">Quick Links</p>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a href="/" style="color:#6b7280; font-size:0.85rem;">Home</a>
                    <a href="/products" style="color:#6b7280; font-size:0.85rem;">Products</a>
                    <a href="/categories" style="color:#6b7280; font-size:0.85rem;">Categories</a>
                    <a href="/contact" style="color:#6b7280; font-size:0.85rem;">Contact</a>
                </div>
            </div>

            <!-- Policy -->
            <div>
                <p style="color:#fff; font-weight:600; margin-bottom:12px;">Policy</p>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a href="#" style="color:#6b7280; font-size:0.85rem;">Privacy Policy</a>
                    <a href="#" style="color:#6b7280; font-size:0.85rem;">Return Policy</a>
                    <a href="#" style="color:#6b7280; font-size:0.85rem;">Delivery Policy</a>
                </div>
            </div>

            <!-- Contact -->
            <div>
                <p style="color:#fff; font-weight:600; margin-bottom:12px;">Contact</p>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <p style="color:#6b7280; font-size:0.85rem;">📞 +880 1234-567890</p>
                    <p style="color:#6b7280; font-size:0.85rem;">📧 info@wholesale.com</p>
                    <p style="color:#6b7280; font-size:0.85rem;">📍 Dhaka, Bangladesh</p>
                </div>
            </div>

        </div>

        <div style="border-top:1px solid #2d2d2d; margin-top:32px; padding-top:20px; text-align:center;">
            <p style="color:#6b7280; font-size:0.85rem;">© {{ date('Y') }} All rights reserved. Developed by <span
                    style="color:#f59e0b;">Md. Sadik</span></p>
        </div>
    </footer>
    <!-- Cart Toast Popup -->
    <div id="cart-toast"
        class="fixed bottom-10 right-10 z-100 bg-white border border-gray-100 shadow-2xl rounded-xl p-4 transition-all duration-500 transform translate-y-20 opacity-0 flex items-center justify-between gap-6 min-w-[380px]">

        <!-- Left Side: Icon and Text -->
        <div class="flex items-center gap-4">
            <div class="bg-yellow-100 p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="text-left">
                <h4 id="toast-title" class="font-bold text-gray-800 text-sm leading-tight">Added to Cart!</h4>
                <p id="toast-msg" class="text-gray-500 text-xs">Product added successfully.</p>
            </div>
        </div>

        <!-- Right Side: View Cart Link -->
        <a href="/cart"
            class="text-amber-600 font-bold text-sm hover:text-amber-700 transition-colors flex items-center gap-1">
            View Cart <span class="text-lg">→</span>
        </a>
    </div>


    <script>
        // Load cart count on every page
        fetch('/cart/count')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('cart-count');
                if (badge) badge.textContent = data.count;
            });
    </script>
</body>


</html>
