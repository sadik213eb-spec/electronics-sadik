@extends('layouts.app')

@section('content')
    <style>
        .contact-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .contact-breadcrumb {
            font-size: 0.9rem;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .contact-breadcrumb a {
            color: #d97706;
            text-decoration: none;
        }

        .contact-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 30px;
            padding-bottom: 16px;
            border-bottom: 2px solid #d97706;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        /* INFO SIDE */
        .contact-info {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #dddddd;
        }

        .contact-info h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #d97706;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fffbf0;
            border: 1px solid #fde68a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d97706;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .info-text p {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        .info-text a,
        .info-text span {
            font-size: 0.95rem;
            color: #2a2a2a;
            font-weight: 500;
            text-decoration: none;
        }

        .info-text a:hover {
            color: #d97706;
        }

        /* FORM SIDE */
        .contact-form {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #dddddd;
        }

        .contact-form h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #2a2a2a;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
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
        }

        .form-control:focus {
            border-color: #d97706;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 130px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background: #b45309;
        }

        .success-msg {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .error-msg {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="contact-wrapper">

        {{-- Breadcrumb
        <div class="contact-breadcrumb">
            <a href="/">Home</a> &rsaquo; Contact Us
        </div> --}}

        {{-- Title --}}
        <h1 class="contact-title">Contact Us</h1>

        <div class="contact-grid">

            {{-- LEFT: Info --}}
            <div class="contact-info">
                <h2>Contact Information</h2>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-text">
                        <p>Head Office (Showroom):</p>
                        <span>Plot: 4-5, Section: 07, Mirpur-11 Bus Stand, Pallabi, Dhaka-1216.</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                    <div class="info-text">
                        <p>Phone</p>
                        <a href="tel:+8801329701348">+88 01329701348</a><br>
                        <a href="tel:+8809638811111">+88 09638811111</a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-text">
                        <p>Email</p>
                        <a href="mailto:info@wholesaleelectronics.com">info@wholesaleelectronics.com</a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div class="info-text">
                        <p>Service Hours</p>
                        <span>Every day: 10:00 am – 10:00 pm</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Form --}}
            <div class="contact-form">
                <h2>Send a Message</h2>

                @if (session('success'))
                    <div class="success-msg"> {{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="error-msg">{{ $error }}</div>
                    @endforeach
                @endif

                <form action="/contact" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Your Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Enter your name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="Enter your email">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                            placeholder="Enter subject">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control" placeholder="Write your message here...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
