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
            padding: 0;
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

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d97706;
        }

        .content-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .add-btn {
            padding: 8px 18px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
        }

        .add-btn:hover {
            background: #b45309;
        }

        /* Address Cards */
        .addresses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }

        .address-card {
            border: 1px solid #dddddd;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            transition: all 0.2s;
            background: #fff;
        }

        .address-card:hover {
            border-color: #d97706;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .address-card.default {
            border-color: #d97706;
            background: #fffbf0;
        }

        .type-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-home {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-office {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-other {
            background: #f3f4f6;
            color: #4b5563;
        }

        .default-label {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #d97706;
            color: #fff;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 50px;
            font-weight: 600;
        }

        .address-name {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            margin-bottom: 4px;
            font-family: 'Montserrat', sans-serif;
            display: block;
            margin-top: 10px;
        }

        .address-phone {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 12px;
            display: block;
        }

        .address-text {
            font-size: 0.88rem;
            color: #2a2a2a;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .address-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .delete-address-btn {
            padding: 6px 12px;
            background: #fff;
            color: #dc2626;
            border: 1px solid #fee2e2;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .delete-address-btn:hover {
            background: #fef2f2;
            border-color: #dc2626;
        }

        /* Add Address Form */
        .add-address-form {
            display: none;
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dddddd;
        }

        .add-address-form.show {
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
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
            background: #fff;
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
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #16a34a;
            font-size: 0.88rem;
        }

        @media (max-width: 1024px) {
            .account-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .addresses-grid {
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
                            <a href="#" onclick="this.closest('form').submit()"
                                style="color:#dc2626; text-decoration:none; display:flex; align-items:center; gap:12px; padding:12px 14px; font-size:0.95rem; font-weight:500;">
                                <i class="fas fa-sign-out-alt"></i> Log Out <span class="arrow"
                                    style="margin-left:auto;">›</span>
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

            <div class="content-header">
                <h3 class="content-title">My Addresses</h3>
                <button class="add-btn" onclick="toggleAddForm()">+ Add New Address</button>
            </div>

            {{-- Add Address Form --}}
            <div class="add-address-form" id="add-address-form">
                <form action="/account/addresses" method="POST">
                    @csrf
                    <div class="form-grid">
                        {{-- Address Type Select --}}
                        <div class="form-group">
                            <label class="form-label">Address Type</label>
                            <select name="type" class="form-input" required>
                                <option value="home" selected>🏠 Home</option>
                                <option value="office">🏢 Office</option>
                                <option value="other">📍 Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="shipping_name" class="form-input" value="{{ $customer->name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mobile Number</label>
                            <input type="tel" name="shipping_mobile" class="form-input" value="{{ $customer->mobile }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="shipping_city" class="form-input" required>
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Full Address</label>
                            <input type="text" name="shipping_address" class="form-input" required>
                        </div>
                    </div>
                    <button type="submit" class="save-btn">Save Address</button>
                    <button type="button" class="cancel-btn" onclick="toggleAddForm()">Cancel</button>
                </form>
            </div>

            {{-- Saved Addresses Grid --}}
            <div class="addresses-grid">
                @forelse ($addresses as $address)
                    <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                        @if ($address->is_default)
                            <span class="default-label">Default</span>
                        @endif

                        {{-- Dynamic Badge based on type --}}
                        <span
                            class="type-badge {{ $address->type == 'home' ? 'badge-home' : ($address->type == 'office' ? 'badge-office' : 'badge-other') }}">
                            {{ ucfirst($address->type) }}
                        </span>

                        <span class="address-name">{{ $address->name }}</span>
                        <span class="address-phone">{{ $address->mobile }}</span>
                        <p class="address-text">
                            {{ $address->address }}<br>
                            {{ $address->city }}
                        </p>

                        <div class="address-actions">
                            <form action="/account/address/{{ $address->id }}/delete" method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-address-btn">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="grid-column:span 2; text-align:center; padding:40px; color:#9ca3af;">
                        <i class="fas fa-map-marker-alt"
                            style="font-size:2.5rem; margin-bottom:12px; color:#dddddd; display:block;"></i>
                        <p style="font-size:1rem; color:#2a2a2a; font-weight:600; margin-bottom:6px;">No addresses saved
                        </p>
                        <p style="font-size:0.88rem;">Add a new address to speed up checkout.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        function toggleAddForm() {
            const form = document.getElementById('add-address-form');
            form.classList.toggle('show');
        }
    </script>
@endsection
