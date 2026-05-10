@extends('layouts.app')

@section('content')
    <style>
        .offers-container {
            max-width: 1440px;
            margin: 30px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        /* Page Title */
        .offers-page-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2a2a2a;
            margin-bottom: 24px;
            display: inline-block;
        }

        /* 3 Column Grid */
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Offer Card */
        .offer-card {
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: block;
            background: #f3f4f6;
        }

        .offer-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        /* Card Image */
        .offer-card-img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            display: block;
        }

        /* Bottom overlay */
        .offer-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, transparent 100%);
            padding: 20px 16px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        /* Countdown inside card */
        .card-countdown {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .card-time-box {
            background: #fff;
            color: #2a2a2a;
            border-radius: 6px;
            padding: 6px 10px;
            text-align: center;
            min-width: 48px;
        }

        .card-time-box .num {
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            display: block;
            line-height: 1;
        }

        .card-time-box .lbl {
            font-size: 0.6rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-top: 3px;
        }

        .card-sep {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2a2a2a;
        }

        /* View Details Button */
        .view-details-btn {
            display: inline-block;
            padding: 8px 24px;
            background: #fff;
            color: #2a2a2a;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            border: 2px solid #fff;
        }

        .offer-card:hover .view-details-btn {
            background: #d97706;
            color: #fff;
            border-color: #d97706;
        }

        /* Empty state */
        .offers-empty {
            text-align: center;
            padding: 80px 20px;
            color: #9ca3af;
            grid-column: 1 / -1;
        }

        .offers-empty p {
            font-size: 1rem;
            margin-top: 8px;
        }

        @media (max-width: 1024px) {
            .offers-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .offers-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="offers-container">

        {{-- Breadcrumb --}}
        {{-- <div style="font-size:0.9rem; color:#9ca3af; margin-bottom:12px;">
            <a href="/" style="color:#9ca3af; text-decoration:none;">Home</a>
            &rsaquo; <span style="color:#2a2a2a;">Latest Offers</span>
        </div> --}}

        <h1 class="offers-page-title">Latest Offers</h1>

        <div class="offers-grid">
            @forelse($offers as $offer)
                <a href="{{ route('offer.show', $offer->slug) }}" class="offer-card">

                    {{-- Offer Image --}}
                    @if ($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" alt="{{ $offer->name }}" class="offer-card-img" />
                    @else
                        <div
                            style="width:100%; aspect-ratio:1/1; background:#e5e7eb; display:flex; align-items:center; justify-content:center; color:#9ca3af;">
                            No Image
                        </div>
                    @endif

                    {{-- Overlay: Timer + Button --}}
                    <div class="offer-card-overlay">

                        {{-- Countdown Timer --}}
                        {{-- @if ($offer->show_timer) --}}
                        <div class="card-countdown" data-end="{{ $offer->end_date->toIso8601String() }}"
                            id="countdown-{{ $offer->id }}">
                            <div class="card-time-box">
                                <span class="num days">00</span>
                                <span class="lbl">DAY</span>
                            </div>
                            <span class="card-sep">:</span>
                            <div class="card-time-box">
                                <span class="num hours">00</span>
                                <span class="lbl">HRS</span>
                            </div>
                            <span class="card-sep">:</span>
                            <div class="card-time-box">
                                <span class="num minutes">00</span>
                                <span class="lbl">MIN</span>
                            </div>
                            <span class="card-sep">:</span>
                            <div class="card-time-box">
                                <span class="num seconds">00</span>
                                <span class="lbl">SEC</span>
                            </div>
                        </div>
                        {{-- @endif --}}

                        <span class="view-details-btn">View Details</span>
                    </div>

                </a>
            @empty
                <div class="offers-empty">
                    <p>No active offers at the moment. Check back soon!</p>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        // Run countdown for each offer card
        document.querySelectorAll('[id^="countdown-"]').forEach(function(el) {
            const endDate = new Date(el.getAttribute('data-end'));

            function update() {
                const now = new Date();
                const diff = endDate - now;

                if (diff <= 0) {
                    el.querySelectorAll('.num').forEach(n => n.textContent = '00');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                el.querySelector('.days').textContent = String(days).padStart(2, '0');
                el.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                el.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                el.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
            }

            update();
            setInterval(update, 1000);
        });
    </script>
@endsection
