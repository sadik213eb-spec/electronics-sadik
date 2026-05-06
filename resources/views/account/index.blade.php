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

        /* SIDEBAR */
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

        .profile-photo-section {
            display: none !important;
        }

        /* Sidebar Menu */
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
            font-size: 1rem;
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

        /* Info sections */
        .info-section {
            background: #f9fafb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .info-item {}

        .info-label {
            font-size: 0.82rem;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2a2a2a;
        }

        .edit-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 6px 16px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            text-decoration: none;
            transition: background 0.2s;
        }

        .edit-btn:hover {
            background: #b45309;
        }

        /* Edit form */
        .edit-form {
            display: none;
        }

        .edit-form.show {
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 16px;
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
            font-size: 0.85rem;
            font-weight: 500;
            color: #2a2a2a;
        }

        .form-input {
            padding: 10px 14px;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #2a2a2a;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: #d97706;
        }

        .save-btn {
            padding: 10px 24px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
            margin-top: 16px;
        }

        .save-btn:hover {
            background: #b45309;
        }

        .cancel-btn {
            padding: 10px 24px;
            background: #fff;
            color: #2a2a2a;
            border: 1px solid #dddddd;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            margin-top: 16px;
            margin-left: 8px;
            transition: all 0.2s;
        }

        .cancel-btn:hover {
            background: #f3f4f6;
        }

        /* Alert */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #16a34a;
            font-size: 0.88rem;
        }

        /* Recent orders */
        .order-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
        }

        .order-row:last-child {
            border-bottom: none;
        }

        .order-number {
            font-weight: 600;
            color: #2a2a2a;
        }

        .order-date {
            color: #9ca3af;
            font-size: 0.82rem;
        }

        .order-status {
            padding: 3px 12px;
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

        .order-total {
            font-weight: 700;
            color: #d97706;
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .account-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
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
                <li>
                    <a href="/account" class="{{ request()->is('account') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Account Details
                        <span class="arrow">›</span>
                    </a>
                </li>
                <li>
                    <a href="/account/orders" class="{{ request()->is('account/orders') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> My Orders
                        <span class="arrow">›</span>
                    </a>
                </li>
                <li>
                    <a href="/account/wishlist" class="{{ request()->is('account/wishlist') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i> My Wishlist
                        <span class="arrow">›</span>
                    </a>
                </li>
                <li>
                    <a href="/account/addresses" class="{{ request()->is('account/addresses') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                        <span class="arrow">›</span>
                    </a>
                </li>

                <div class="menu-divider"></div>

                <li>
                    <a href="#">
                        <i class="fas fa-shield-alt"></i> Privacy Policy
                        <span class="arrow">›</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-contract"></i> Terms & Conditions
                        <span class="arrow">›</span>
                    </a>
                </li>

                <div class="menu-divider"></div>

                <li>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit"
                            style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                            <a href="#" onclick="this.closest('form').submit()" style="color:#dc2626;">
                                <i class="fas fa-sign-out-alt"></i> Log Out
                                <span class="arrow">›</span>
                            </a>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- CONTENT --}}
        <div class="account-content">

            @if (session('success'))
                <div class="alert-success">✅ {{ session('success') }}</div>
            @endif

            {{-- ACCOUNT INFO --}}
            <h3 class="content-title">My Account Information</h3>

            {{-- Profile Info --}}
            <div class="info-section">
                <div class="profile-photo-section">
                    <img src="{{ $customer->profile_photo ? asset('storage/' . $customer->profile_photo) : asset('images/default-user.png') }}"
                        class="profile-image-preview" alt="Profile">
                </div>

                <button class="edit-btn" onclick="toggleEdit('profile-form')">Edit Profile</button>
                <div class="info-grid" id="profile-info">
                    <div class="info-item">
                        <p class="info-label">Full Name</p>
                        <p class="info-value">{{ $customer->name }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Mobile Number</p>
                        <p class="info-value">{{ $customer->mobile }}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Birthday</p>
                        <p class="info-value">{{ $customer->birthday ? $customer->birthday->format('d M Y') : '—' }}</p>
                    </div>
                </div>

                {{-- Edit Profile Form --}}
                <div class="edit-form" id="profile-form">
                    <!-- MUST HAVE enctype="multipart/form-data" and method="POST" -->
                    <form action="/account/profile" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-input" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-input" value="{{ $customer->name }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" name="mobile" class="form-input" value="{{ $customer->mobile }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input" value="{{ $customer->email }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Birthday</label>
                                <input type="date" name="birthday" class="form-input"
                                    value="{{ $customer->birthday ? $customer->birthday->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <button type="submit" class="save-btn">Save Changes</button>
                        <button type="button" class="cancel-btn" onclick="toggleEdit('profile-form')">Cancel</button>
                    </form>
                </div>


                {{-- Account Security --}}
                <h3 class="content-title" style="margin-top:24px;">Account Security</h3>
                <div class="info-section">
                    <button class="edit-btn" onclick="toggleEdit('password-form')">Change</button>
                    <div class="info-grid">
                        <div class="info-item">
                            <p class="info-label">Email</p>
                            <p class="info-value">{{ $customer->email }}</p>
                        </div>
                        <div class="info-item">
                            <p class="info-label">Password</p>
                            <p class="info-value">••••••••••••</p>
                        </div>
                    </div>

                    {{-- Change Password Form --}}
                    <div class="edit-form" id="password-form">
                        <form action="/account/password" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-grid">
                                <div class="form-group full">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-input" required>
                                    @error('current_password')
                                        <span style="font-size:0.8rem; color:#dc2626;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-input" required>
                                </div>
                            </div>
                            <button type="submit" class="save-btn">Update Password</button>
                            <button type="button" class="cancel-btn"
                                onclick="toggleEdit('password-form')">Cancel</button>
                        </form>
                    </div>
                </div>

                {{-- Recent Orders --}}
                @if ($recentOrders->count() > 0)
                    <h3 class="content-title" style="margin-top:24px;">Recent Orders</h3>
                    <div class="info-section">
                        @foreach ($recentOrders as $order)
                            <div class="order-row">
                                <div>
                                    <p class="order-number">#{{ $order->order_number }}</p>
                                    <p class="order-date">{{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <span class="order-status status-{{ $order->order_status }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                                <p class="order-total">৳{{ number_format($order->grand_total) }}</p>
                                <a href="/account/orders" style="color:#d97706; font-size:0.85rem; font-weight:600;">View
                                    →</a>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        <script>
            function toggleEdit(id) {
                const form = document.getElementById(id);
                form.classList.toggle('show');
            }
        </script>

    @endsection
