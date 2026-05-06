@extends('layouts.app')

@section('content')
    <style>
        .success-wrapper {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .success-hero {
            background: #fff;
            border-radius: 20px;
            padding: 60px 40px 50px;
            border: 1px solid #eeeeee;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
        }

        /* Check circle */
        .check-wrap {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 32px;
        }

        .check-circle {
            width: 100px;
            height: 100px;
            background: #d97706;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            color: #fff;
            font-weight: 300;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes popIn {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 0;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        /* Orbiting dots */
        .dot {
            position: absolute;
            border-radius: 50%;
            background: #d97706;
            animation: dotPulse 2s ease-in-out infinite;
        }

        .dot-1 {
            width: 10px;
            height: 10px;
            top: 0%;
            left: 42%;
            animation-delay: 0s;
            opacity: 0.5;
        }

        .dot-2 {
            width: 7px;
            height: 7px;
            top: 12%;
            left: 88%;
            animation-delay: 0.3s;
            opacity: 0.35;
        }

        .dot-3 {
            width: 9px;
            height: 9px;
            top: 50%;
            left: 100%;
            animation-delay: 0.6s;
            opacity: 0.5;
        }

        .dot-4 {
            width: 6px;
            height: 6px;
            top: 88%;
            left: 82%;
            animation-delay: 0.9s;
            opacity: 0.3;
        }

        .dot-5 {
            width: 10px;
            height: 10px;
            top: 100%;
            left: 42%;
            animation-delay: 1.2s;
            opacity: 0.5;
        }

        .dot-6 {
            width: 7px;
            height: 7px;
            top: 88%;
            left: 5%;
            animation-delay: 1.5s;
            opacity: 0.35;
        }

        .dot-7 {
            width: 9px;
            height: 9px;
            top: 50%;
            left: -8%;
            animation-delay: 1.8s;
            opacity: 0.5;
        }

        .dot-8 {
            width: 6px;
            height: 6px;
            top: 12%;
            left: 5%;
            animation-delay: 0.1s;
            opacity: 0.3;
        }

        @keyframes dotPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.2;
            }

            50% {
                transform: scale(1.6);
                opacity: 0.8;
            }
        }

        .success-title {
            font-size: 1.9rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 12px;
        }

        .success-subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 4px;
        }

        .order-number-text {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 36px;
        }

        .order-number-text span {
            color: #d97706;
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-outline {
            padding: 13px 32px;
            background: #fff;
            color: #2a2a2a;
            border: 2px solid #dddddd;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            border-color: #2a2a2a;
            background: #2a2a2a;
            color: #fff;
        }

        .btn-primary {
            padding: 13px 32px;
            background: #d97706;
            color: #fff;
            border: 2px solid #d97706;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #b45309;
            border-color: #b45309;
        }

        @media (max-width: 480px) {
            .success-hero {
                padding: 40px 20px;
            }

            .success-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-outline,
            .btn-primary {
                text-align: center;
            }
        }
    </style>

    <div class="success-wrapper">
        <div class="success-hero">

            {{-- Animated check with dots --}}
            <div class="check-wrap">
                <div class="dot dot-1"></div>
                <div class="dot dot-2"></div>
                <div class="dot dot-3"></div>
                <div class="dot dot-4"></div>
                <div class="dot dot-5"></div>
                <div class="dot dot-6"></div>
                <div class="dot dot-7"></div>
                <div class="dot dot-8"></div>
                <div class="check-circle">✓</div>
            </div>

            <h1 class="success-title">Thank you for your purchase</h1>
            <p class="success-subtitle">We've received your order and will start processing it right away.</p>
            <p class="order-number-text">Your order number is <span>#{{ $order->order_number }}</span></p>

            <div class="action-buttons">
                <a href="/account/orders/{{ $order->id }}" class="btn-outline">View Order</a>
                <a href="/" class="btn-primary">Continue Shopping</a>
            </div>

        </div>
    </div>
@endsection
