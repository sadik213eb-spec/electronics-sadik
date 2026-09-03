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

        /* ── Sidebar ── */
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

        .profile-points {
            font-size: 0.82rem;
            color: #d97706;
            font-weight: 600;
            margin-top: 4px;
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

        /* ── Content ── */
        .account-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .detail-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px 28px;
            border: 1px solid #eeeeee;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #d97706;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Points overview banner ── */
        .points-overview {
            background: linear-gradient(135deg, #d97706, #b45309);
            border-radius: 14px;
            padding: 22px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            flex-wrap: wrap;
            gap: 16px;
        }

        .points-overview-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .points-overview-icon {
            font-size: 2.2rem;
        }

        .points-overview-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .points-overview-value {
            font-size: 2rem;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.1;
        }

        .points-overview-right {
            text-align: right;
            font-size: 0.82rem;
            opacity: 0.9;
        }

        /* ── Tier cards ── */
        .tier-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .tier-card {
            background: #fafafa;
            border: 1px solid #eeeeee;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .tier-card.active {
            background: #fffbf0;
            border-color: #d97706;
        }

        .tier-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #9ca3af;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin: 0 auto 10px;
        }

        .tier-card.active .tier-icon {
            background: #d97706;
        }

        .tier-name {
            font-weight: 700;
            color: #2a2a2a;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .tier-range {
            font-size: 0.78rem;
            color: #9ca3af;
        }

        /* ── Activity table ── */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table thead th {
            background: #f9fafb;
            text-align: left;
            font-size: 0.78rem;
            font-weight: 600;
            color: #6b7280;
            padding: 10px 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-table tbody td {
            padding: 12px 14px;
            font-size: 0.88rem;
            color: #2a2a2a;
            border-bottom: 1px solid #f3f4f6;
        }

        .pt-earned {
            color: #16a34a;
            font-weight: 600;
        }

        .pt-spent {
            color: #dc2626;
            font-weight: 600;
        }

        .pt-expired {
            color: #9ca3af;
            font-weight: 600;
        }

        /* ── How-it-works info list ── */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item-row {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding-bottom: 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-item-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-item-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid #d97706;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .info-item-title {
            font-weight: 700;
            color: #2a2a2a;
            font-size: 0.92rem;
            margin-bottom: 2px;
        }

        .info-item-desc {
            font-size: 0.85rem;
            color: #6b7280;
        }

        @media (max-width: 1024px) {
            .account-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .tier-grid {
                grid-template-columns: 1fr 1fr;
            }

            .activity-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>

    <div class="account-wrapper">

        {{-- ── SIDEBAR ── --}}
        {{-- <div class="account-sidebar">
            <div class="sidebar-profile">
                <div class="profile-avatar">
                    @php $customer = auth('customer')->user(); @endphp
                    @if ($customer->profile_photo)
                        <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="{{ $customer->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <p class="profile-name">{{ $customer->name }}</p>
                <p class="profile-email">{{ $customer->email }}</p>
                <p class="profile-points">Points: {{ number_format($customer->totalRewardPoints()) }}</p>
            </div>

            <ul class="sidebar-menu">
                <li><a href="/account" class="{{ request()->is('account') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Account Details <span class="arrow">›</span></a></li>
                <li><a href="/account/orders" class="{{ request()->is('account/orders*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> My Orders <span class="arrow">›</span></a></li>
                <li><a href="{{ route('account.reward-points') }}"
                        class="{{ request()->is('account/reward-points') ? 'active' : '' }}">
                        <i class="fas fa-gift"></i> Reward Points <span class="arrow">›</span></a></li>
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
        </div> --}}

        @include('account.partials._sidebar')

        {{-- ── CONTENT ── --}}
        <div class="account-content">

            {{-- ── Points Overview Card ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <p class="card-title">🎁 My Reward Points</p>
                </div>

                <div class="points-overview">
                    <div class="points-overview-left">
                        <div class="points-overview-icon">🎁</div>
                        <div>
                            <div class="points-overview-label">Points Overview</div>
                            <div class="points-overview-value">{{ number_format($points) }}</div>
                        </div>
                    </div>
                    <div class="points-overview-right">
                        <div><i class="far fa-calendar"></i> Last Updated</div>
                        <div>{{ $lastUpdated ? $lastUpdated->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>
            </div>

            {{-- ── Tiers Card ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <p class="card-title">🏆 Reward Tiers</p>
                </div>

                <div class="tier-grid">
                    @foreach ($tiers as $tier)
                        <div class="tier-card {{ $currentTier === $tier['name'] ? 'active' : '' }}">
                            <div class="tier-icon">
                                @switch($tier['name'])
                                    @case('General')
                                        ⭐
                                    @break

                                    @case('Silver')
                                        🛡️
                                    @break

                                    @case('Silver Premium')
                                        🎖️
                                    @break

                                    @case('Gold')
                                        🏅
                                    @break
                                @endswitch
                            </div>
                            <div class="tier-name">{{ $tier['name'] }}</div>
                            <div class="tier-range">{{ number_format($tier['min']) }} - {{ number_format($tier['max']) }}
                                Points</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Recent Activity Card ── --}}
            <div class="detail-card">
                <div class="card-header">
                    <p class="card-title">📜 Recent Activity</p>
                    @if ($recentActivity->count() > 0)
                        <a href="#" style="font-size:0.85rem; color:#6b7280; text-decoration:none;">
                            View more <i class="fas fa-chevron-down"></i>
                        </a>
                    @endif
                </div>

                @if ($recentActivity->count() > 0)
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Earned</th>
                                <th>Spent</th>
                                <th>Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentActivity as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d M Y') }}</td>
                                    <td>{{ $activity->description ?? ucfirst($activity->type) }}</td>
                                    <td class="pt-earned">
                                        {{ $activity->type === 'earned' ? '+' . $activity->points : '-' }}</td>
                                    <td class="pt-spent">{{ $activity->type === 'spent' ? '-' . $activity->points : '-' }}
                                    </td>
                                    <td class="pt-expired">
                                        {{ $activity->type === 'expired' ? '-' . $activity->points : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="info-list">
                        <div class="info-item-row">
                            <div class="info-item-icon"><i class="fas fa-coins"></i></div>
                            <div>
                                <div class="info-item-title">Earn Reward Points by Orders</div>
                                <div class="info-item-desc">1.5 Reward Points for every 100 Taka you spend!</div>
                            </div>
                        </div>
                        <div class="info-item-row">
                            <div class="info-item-icon"><i class="fas fa-shopping-bag"></i></div>
                            <div>
                                <div class="info-item-title">How to Spend Reward Points</div>
                                <div class="info-item-desc">You can use your Reward Points directly at checkout!</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
