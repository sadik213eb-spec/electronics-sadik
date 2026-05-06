@extends('layouts.app')

@section('content')
    <style>
        .page-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 16px;
            border-bottom: 2px solid #d97706;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2a2a2a;
            font-family: 'Montserrat', sans-serif;
        }

        .page-breadcrumb {
            font-size: 0.9rem;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .page-breadcrumb a {
            color: #d97706;
            text-decoration: none;
        }

        .page-breadcrumb a:hover {
            text-decoration: underline;
        }

        .page-content {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #dddddd;
            line-height: 1.8;
            color: #2a2a2a;
            font-size: 1rem;
        }

        .page-content h1,
        .page-content h2,
        .page-content h4,
        .page-content h3 {
            font-family: 'Montserrat', sans-serif;
            color: #d97706;
        }

        .page-content h1 {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif;
            margin: 20px 0 10px;
        }

        .page-content h2 {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            font-family: 'Montserrat', sans-serif;
            margin: 20px 0 10px;
        }

        .page-content h3 {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            font-family: 'Montserrat', sans-serif;
            margin: 16px 0 8px;
        }

        .page-content h4 {
            font-size: 1.2rem !important;
            font-weight: 600 !important;
            margin: 14px 0 6px;
        }

        .page-content p {
            font-size: 1rem !important;
            line-height: 1.5;
            font-weight: 400;
        }

        .page-content ul,
        .page-content ol {
            padding-left: 24px !important;
            margin-bottom: 14px !important;
            font-size: 1rem !important;
        }

        .page-content ul li {
            list-style: disc !important;
            margin-bottom: 6px;
        }

        .page-content ol li {
            list-style: decimal !important;
            margin-bottom: 6px;
        }

        .page-content strong {
            font-weight: 700 !important;
        }

        .page-content a {
            color: #2a2a2a !important;
            text-decoration: underline !important;
        }

        .page-content a:hover {
            color: #d97706 !important;
        }

        .page-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .page-content table td,
        .page-content table th {
            padding: 10px 14px;
            border: 1px solid #dddddd;
        }

        .page-content table tr:nth-child(even) {
            background: #f9fafb;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                margin: 20px auto;
            }

            .page-content {
                padding: 20px;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="page-wrapper">

        {{-- Breadcrumb --}}
        {{-- <div class="page-breadcrumb">
            <a href="/">Home</a> &rsaquo; {{ $page->title }}
        </div> --}}

        {{-- Title --}}
        <div class="page-header">
            <h1 class="page-title">{{ $page->title }}</h1>
        </div>

        {{-- Content --}}
        <div class="page-content">
            {!! $page->content !!}
        </div>

    </div>
@endsection
