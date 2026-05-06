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
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #ededed;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .section-title, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        a { text-decoration: none; }

        /* Dropdown styles */
        .dropdown-menu {
            display: none;
            position: fixed;
            background: #fff;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            min-width: 130px;
            z-index: 99999;
            padding: 10px 0;
            border-top: 2px solid #d97706;
        }

        .dropdown-menu a {
            display: block;
            padding: 5px 10px;
            color: #2a2a2a;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .dropdown-menu a:hover {
            background: #fffbf0;
            color: #d97706;
            padding-left: 20px;
        }

        #search-results::-webkit-scrollbar { width: 4px; }
        #search-results::-webkit-scrollbar-thumb { background: #d97706; border-radius: 4px; }

        .nav-scroll::-webkit-scrollbar { display: none; }
        .nav-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body>

    <!-- TOP BAR -->
    <nav style="background:#1a1a1a; border-bottom:1px solid #2d2d2d; position:sticky; top:0; z-index:9999;">

        <!-- FIRST ROW: Logo + Search + Icons -->
        <div style="max-width:1440px; margin:0 auto; padding:0 20px; display:flex; align-items:center; justify-content:space-between; height:65px; gap:20px;">

            <!-- Logo -->
            <a href="/" style="font-size:1.4rem; font-weight:800; color:#f59e0b; text-decoration:none; white-space:nowrap;">
                <img src="http://127.0.0.1:8000/storage/media/wholesale-electronics-tm-1-768x63png-removebg-preview.png" alt="Wholesale Electronics" width="280">
            </a>

            <!-- Search Bar -->
            <div style="flex:1; max-width:600px; position:relative;">
                <input type="text" id="search-input" placeholder="Search for products, brands and more..."
                    style="width:100%; padding:10px 50px 10px 16px; border-radius:8px; border:none; font-size:0.9rem; font-family:'Poppins',sans-serif; outline:none; background:#fff; color:#2a2a2a;">
                <button onclick="doSearch()"
                    style="position:absolute; right:0; top:0; bottom:0; padding:0 16px; background:#d97706; border:none; border-radius:0 8px 8px 0; cursor:pointer; color:#fff; font-size:1rem;">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Live Search Results -->
                <div id="search-results"
                    style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border-radius:8px; box-shadow:0 10px 40px rgba(0,0,0,0.15); z-index:99999; max-height:400px; overflow-y:auto;">
                </div>
            </div>

            <!-- Right Icons -->
            <div style="display:flex; align-items:center; gap:20px;">
                <!-- Cart -->
                <a href="/cart" style="position:relative; text-decoration:none; display:flex; flex-direction:column; align-items:center;">
                    <i class="fas fa-shopping-cart" style="font-size:1.3rem; color:#d1d5db;"></i>
                    <span id="cart-count" style="position:absolute; top:-8px; right:-8px; background:#dc2626; color:#fff; border-radius:50%; width:18px; height:18px; font-size:0.65rem; display:flex; align-items:center; justify-content:center;">0</span>
                    <span style="color:#9ca3af; font-size:0.72rem; margin-top:2px;">Cart</span>
                </a>

                <!-- Account -->
                @auth('customer')
                    <a href="/account" style="text-decoration:none; display:flex; flex-direction:column; align-items:center;">
                        <i class="fas fa-user" style="font-size:1.3rem; color:#d1d5db;"></i>
                        <span style="color:#9ca3af; font-size:0.72rem; margin-top:2px;">Account</span>
                    </a>
                @else
                    <a href="/login" style="text-decoration:none; display:flex; flex-direction:column; align-items:center;">
                        <i class="fas fa-user" style="font-size:1.3rem; color:#d1d5db;"></i>
                        <span style="color:#9ca3af; font-size:0.72rem; margin-top:2px;">Login</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- SECOND ROW: Dynamic Nav Menu -->
        <div style="background:#222222; border-top:1px solid #333;">
            <div class="nav-scroll" style="max-width:1440px; margin:0 auto; padding:0 20px; display:flex; align-items:center;">
                @foreach ($topMenus as $menu)
                    <div class="nav-item" data-id="{{ $menu->id }}">
                        <a href="{{ $menu->url }}" class="nav-link"
                            style="display:flex; align-items:center; gap:5px; padding:12px 0px; padding-right:20px; color:#d1d5db; text-decoration:none; font-size:1rem; font-weight: 400; white-space:nowrap;">
                            {{ $menu->name }}
                            @if ($menu->children->count())
                                <i class="fas fa-chevron-down" style="font-size:0.65rem; opacity:0.7;"></i>
                            @endif
                        </a>

                        {{-- Dropdown --}}
                        @if ($menu->children->count())
                            <div class="dropdown-menu" id="dropdown-{{ $menu->id }}">
                                @foreach ($menu->children as $child)
                                    <a href="{{ $child->url }}">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer style="background:#1a1a1a; border-top:1px solid #2d2d2d; margin-top:60px; padding:40px 20px;">
        <div style="max-width:1440px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:32px;">

            <div>
                <p style="color:#f59e0b; font-size:1.2rem; font-weight:700; margin-bottom:12px;">⚡ Wholesale Electronics</p>
                <p style="color:#6b7280; font-size:0.85rem; line-height:1.6;">Your trusted online store for authentic electronics & home appliances.</p>
            </div>

            <div>
                <p style="color:#fff; font-weight:600; margin-bottom:12px;">Quick Links</p>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach ($footerMenus as $menu)
                        <a href="{{ $menu->url }}" style="color:#6b7280; font-size:0.85rem;"
                            onmouseover="this.style.color='#f59e0b'"
                            onmouseout="this.style.color='#6b7280'">
                            {{ $menu->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <p style="color:#fff; font-weight:600; margin-bottom:12px;">Policy</p>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a href="/pages/privacy-policy" style="color:#6b7280; font-size:0.85rem;">Privacy Policy</a>
                    <a href="/pages/return-policy" style="color:#6b7280; font-size:0.85rem;">Return Policy</a>
                    <a href="/pages/delivery-policy" style="color:#6b7280; font-size:0.85rem;">Delivery Policy</a>
                </div>
            </div>

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
            <p style="color:#6b7280; font-size:0.85rem;">© {{ date('Y') }} All rights reserved. Developed by <span style="color:#f59e0b;">Md. Sadik</span></p>
        </div>
    </footer>

    <!-- Cart Toast Popup -->
    <div id="cart-toast"
        class="fixed bottom-10 right-10 z-100 bg-white border border-gray-100 shadow-2xl rounded-xl p-4 transition-all duration-500 transform translate-y-20 opacity-0 flex items-center justify-between gap-6 min-w-[380px]">
        <div class="flex items-center gap-4">
            <div class="bg-yellow-100 p-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="text-left">
                <h4 id="toast-title" class="font-bold text-gray-800 text-sm leading-tight">Added to Cart!</h4>
                <p id="toast-msg" class="text-gray-500 text-xs">Product added successfully.</p>
            </div>
        </div>
        <a href="/cart" class="text-amber-600 font-bold text-sm hover:text-amber-700 transition-colors flex items-center gap-1">
            View Cart <span class="text-lg">→</span>
        </a>
    </div>

    <script>
        // Cart count
        fetch('/cart/count')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('cart-count');
                if (badge) badge.textContent = data.count;
            });

        // ✅ Dropdown using fixed positioning based on nav-item position
        document.querySelectorAll('.nav-item').forEach(item => {
            const dropdown = item.querySelector('.dropdown-menu');
            if (!dropdown) return;

            const link = item.querySelector('.nav-link');

            item.addEventListener('mouseenter', () => {
                const rect = item.getBoundingClientRect();
                dropdown.style.top = rect.bottom + 'px';
                dropdown.style.left = rect.left + 'px';
                dropdown.style.display = 'block';
                link.style.color = '#f59e0b';
            });

            item.addEventListener('mouseleave', () => {
                dropdown.style.display = 'none';
                link.style.color = '#d1d5db';
            });

            dropdown.addEventListener('mouseenter', () => {
                dropdown.style.display = 'block';
                link.style.color = '#f59e0b';
            });

            dropdown.addEventListener('mouseleave', () => {
                dropdown.style.display = 'none';
                link.style.color = '#d1d5db';
            });
        });

        // Live Search
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            const results = document.getElementById('search-results');

            if (q.length < 2) {
                results.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            results.innerHTML = `
                                <div style="padding:16px; text-align:center; color:#9ca3af; font-size:0.9rem;">
                                    No products found for "${q}"
                                </div>
                                <a href="/search?q=${encodeURIComponent(q)}"
                                    style="display:block; text-align:center; padding:12px; background:#1a1a1a; color:#fff; font-size:0.88rem; font-weight:600; text-decoration:none; border-radius:0 0 8px 8px;"
                                    onmouseover="this.style.background='#d97706'"
                                    onmouseout="this.style.background='#1a1a1a'">
                                    See all results →
                                </a>
                            `;
                        } else {
                            results.innerHTML = data.map(p => `
                                <a href="${p.url}" style="display:flex; align-items:center; gap:12px; padding:10px 14px; text-decoration:none; border-bottom:1px solid #f3f4f6; transition:background 0.2s;"
                                    onmouseover="this.style.background='#fffbf0'"
                                    onmouseout="this.style.background='transparent'">
                                    <img src="${p.image}" style="width:48px; height:48px; object-fit:contain; border-radius:6px; border:1px solid #f3f4f6; background:#fff;">
                                    <div style="flex:1;">
                                        <p style="font-size:0.88rem; font-weight:600; color:#2a2a2a; margin-bottom:2px;">${p.name}</p>
                                        <p style="font-size:0.75rem; color:#9ca3af;">${p.brand} · ${p.category}</p>
                                    </div>
                                    <p style="font-size:0.9rem; font-weight:700; color:#d97706; white-space:nowrap;">৳${p.price}</p>
                                </a>
                            `).join('') + `
                                <a href="/search?q=${encodeURIComponent(q)}"
                                    style="display:block; text-align:center; padding:12px; background:#1a1a1a; color:#fff; font-size:0.88rem; font-weight:600; text-decoration:none; border-radius:0 0 8px 8px;"
                                    onmouseover="this.style.background='#d97706'"
                                    onmouseout="this.style.background='#1a1a1a'">
                                    See all results →
                                </a>
                            `;
                        }
                        results.style.display = 'block';
                    });
            }, 300);
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!document.getElementById('search-input').contains(e.target)) {
                document.getElementById('search-results').style.display = 'none';
            }
        });

        // Search on Enter key
        document.getElementById('search-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') doSearch();
        });

        function doSearch() {
            const q = document.getElementById('search-input').value.trim();
            if (q) window.location.href = `/search?q=${encodeURIComponent(q)}`;
        }
    </script>

</body>
</html>
