{{-- resources/views/account/partials/_sidebar.blade.php --}}
@php
    $customer = auth('customer')->user();
@endphp

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
        <div class="profile-reward-points">
            <i class="fas fa-trophy"></i> {{ number_format($customer->totalRewardPoints()) }} Reward Points
        </div>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/account" class="{{ request()->is('account') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Account Details
                <span class="arrow">›</span>
            </a>
        </li>
        <li>
            <a href="/account/orders" class="{{ request()->is('account/orders*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> My Orders
                <span class="arrow">›</span>
            </a>
        </li>
        <li>
            <a href="{{ route('account.reward-points') }}"
                class="{{ request()->is('account/reward-points') ? 'active' : '' }}">
                <i class="fas fa-gift"></i> Reward Points
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
