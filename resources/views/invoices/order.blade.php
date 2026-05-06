<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #1a1a1a;
            background: #f5f5f5;
        }

        .page-wrapper {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* ---- HEADER ---- */
        .invoice-header {
            background: linear-gradient(135deg, #1e1e2e 0%, #2d2d44 100%);
            color: #fff;
            padding: 36px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-info h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 6px;
            color: #f97316;
        }

        .company-info p {
            font-size: 12px;
            color: #aaa;
            line-height: 1.8;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #f97316;
            margin-bottom: 12px;
        }

        .invoice-meta table {
            margin-left: auto;
        }

        .invoice-meta td {
            padding: 3px 8px;
            font-size: 12px;
            color: #ccc;
        }

        .invoice-meta td:first-child {
            color: #888;
            text-align: right;
        }

        /* ---- BADGES ---- */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #374151;
        }

        /* ---- ORDER INFO STRIP ---- */
        .order-info-strip {
            display: flex;
            border-bottom: 1px solid #eee;
        }

        .info-cell {
            flex: 1;
            padding: 14px 20px;
            border-right: 1px solid #eee;
            text-align: center;
        }

        .info-cell:last-child {
            border-right: none;
        }

        .info-cell .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #999;
            margin-bottom: 5px;
        }

        .info-cell .value {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* ---- ADDRESSES ---- */
        .addresses {
            display: flex;
            gap: 20px;
            padding: 24px 40px;
            background: #fafafa;
            border-bottom: 1px solid #eee;
        }

        .address-box {
            flex: 1;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px 20px;
        }

        .address-box h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #f97316;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #f0d5c0;
        }

        .address-box p {
            font-size: 12px;
            color: #444;
            line-height: 1.8;
        }

        .address-box strong {
            color: #111;
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        /* ---- ITEMS TABLE ---- */
        .items-section {
            padding: 28px 40px 10px;
        }

        .items-section h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            margin-bottom: 14px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.items-table thead tr {
            background: #1e1e2e;
            color: #fff;
        }

        table.items-table thead th {
            padding: 11px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        table.items-table thead th:nth-child(3),
        table.items-table thead th:nth-child(4),
        table.items-table thead th:nth-child(5),
        table.items-table thead th:nth-child(6) {
            text-align: right;
        }

        table.items-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        table.items-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        table.items-table tbody td {
            padding: 12px 14px;
            font-size: 12px;
            color: #333;
            vertical-align: middle;
        }

        table.items-table tbody td:nth-child(3),
        table.items-table tbody td:nth-child(4),
        table.items-table tbody td:nth-child(5),
        table.items-table tbody td:nth-child(6) {
            text-align: right;
        }

        .product-name {
            font-weight: 600;
            color: #111;
        }

        .product-warranty {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .no-items {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }

        /* ---- TOTALS ---- */
        .totals-section {
            padding: 20px 40px 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .totals-box {
            width: 320px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .totals-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-box td {
            padding: 10px 16px;
            font-size: 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #444;
        }

        .totals-box tr:last-child td {
            border-bottom: none;
        }

        .totals-box td:last-child {
            text-align: right;
            font-weight: 600;
            color: #111;
        }

        .totals-box .discount-row td {
            color: #dc2626;
        }

        .totals-box .discount-row td:last-child {
            color: #dc2626;
        }

        .totals-box .grand-row {
            background: #1e1e2e;
        }

        .totals-box .grand-row td {
            font-size: 14px;
            font-weight: 700;
            color: #fff !important;
            padding: 13px 16px;
            border-bottom: none;
        }

        .notes-box {
            flex: 1;
            padding: 10px;
        }

        .customer-note strong {
            display: block;
            margin-bottom: 4px;
        }

        .customer-note p {
            margin: 0;
            color: #555;
        }

        /* ---- FOOTER ---- */
        .invoice-footer {
            padding: 18px 40px;
            background: #fafafa;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-footer p {
            font-size: 11px;
            color: #999;
            line-height: 1.6;
        }

        .thank-you {
            font-size: 13px;
            font-weight: 600;
            color: #f97316;
        }

        /* ---- PRINT BUTTON ---- */
        .print-bar {
            text-align: center;
            padding: 20px;
            background: #f0f0f0;
        }

        .print-btn {
            background: #f97316;
            color: #fff;
            border: none;
            padding: 12px 40px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: background 0.2s;
        }

        .print-btn:hover {
            background: #ea6a0a;
        }

        /* ---- PRINT STYLES ---- */
        @media print {
            body {
                background: #fff;
            }

            .page-wrapper {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
                max-width: 100%;
            }

            .print-bar {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    {{-- Print Button --}}
    <div class="print-bar">
        <button class="print-btn" onclick="window.print()">🖨 Print Invoice</button>
    </div>

    <div class="page-wrapper">

        {{-- ---- HEADER ---- --}}
        <div class="invoice-header">
            <div class="company-info">
                <h1>Wholesale Electronics</h1>
                <p>
                    Matikata, Dhaka, Bangladesh<br>
                    📞 +880 1789-8885423<br>
                    ✉ info@wholesaleelectronics.com
                </p>
            </div>
            <div class="invoice-meta">
                <h2>INVOICE</h2>
                <table>
                    <tr>
                        <td>Invoice No:</td>
                        <td><strong style="color:#fff;">{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        {{-- ✅ Date and time on same line --}}
                        <td>Date:</td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ---- ORDER INFO STRIP ---- --}}
        <div class="order-info-strip">
            <div class="info-cell">
                <div class="label">Order Status</div>
                <div class="value">
                    @php
                        $osColor = match ($order->order_status) {
                            'processing' => 'warning',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                            default => 'gray',
                        };
                    @endphp
                    <span class="badge badge-{{ $osColor }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>
            <div class="info-cell">
                <div class="label">Payment Status</div>
                <div class="value">
                    @php
                        $psColor = match ($order->payment_status) {
                            'paid' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            'refunded' => 'gray',
                            default => 'gray',
                        };
                    @endphp
                    <span class="badge badge-{{ $psColor }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
            <div class="info-cell">
                <div class="label">Payment Method</div>
                <div class="value">
                    {{ match ($order->payment_method) {
                        'cod' => 'Cash on Delivery',
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'stripe' => 'Card/Stripe',
                        default => $order->payment_method,
                    } }}
                </div>
            </div>
            <div class="info-cell">
                <div class="label">Shipping Zone</div>
                <div class="value">
                    @php
                        $zoneColor = $order->shipping_zone === 'outside_dhaka' ? 'warning' : 'info';
                    @endphp
                    <span class="badge badge-{{ $zoneColor }}">
                        {{ $order->shipping_zone === 'outside_dhaka' ? 'Outside Dhaka' : 'Inside Dhaka' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ---- ADDRESSES ---- --}}
        <div class="addresses">
            <div class="address-box">
                <h4>Bill To</h4>
                <p>
                    <strong>{{ $order->customer->name ?? 'N/A' }}</strong>
                    {{ $order->customer->mobile ?? '' }}<br>
                    @if ($order->customer->email)
                        {{ $order->customer->email ?? '' }}<br>
                    @endif
                    {{ $order->customer->city ?? '' }}<br>
                    {{ $order->customer->address ?? '' }}
                </p>
            </div>
            <div class="address-box">
                <h4>Ship To</h4>
                <p>
                    <strong>{{ $order->shipping_name }}</strong>
                    {{ $order->shipping_phone }}<br>
                    {{ $order->shipping_city }}<br>
                    {{ $order->shipping_address }}
                </p>
            </div>
        </div>

        {{-- ---- ORDER ITEMS ---- --}}
        <div class="items-section">
            <h3>Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Product</th> {{-- ✅ 50% width --}}
                        <th style="width: 15%; text-align:right;">MRP (৳)</th>
                        <th style="width: 15%; text-align:right;">Sale Price (৳)</th>
                        <th style="width: 10%; text-align:right;">Qty</th>
                        <th style="width: 20%; text-align:right;">Subtotal (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="product-name">
                                    {{ $item->product->name ?? 'Product #' . $item->product_id }}
                                </div>
                                @if ($item->product?->warranty)
                                    <div class="product-warranty">Warranty: {{ $item->product->warranty }}</div>
                                @endif
                            </td>
                            {{-- unit_price = MRP, sale_price = selling price --}}
                            <td>৳{{ number_format($item->unit_price ?? 0, 2) }}</td>
                            <td>৳{{ number_format($item->sale_price ?? ($item->unit_price ?? 0), 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>৳{{ number_format($item->subtotal ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-items">No items found for this order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ---- TOTALS ---- --}}
        <div class="totals-section">
            <!-- Customer Note -->
            <div class="notes-box">
                @if (!empty($order->customer_note))
                    <div class="customer-note">
                        <strong>Customer Note:</strong>
                        <p>{{ $order->customer_note }}</p>
                    </div>
                @endif
            </div>

            <div class="totals-box">
                <table>
                    <tr>
                        <td>Total Sale Amount</td>
                        <td>৳{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    </tr>
                    @if (($order->discount_amount ?? 0) > 0)
                        <tr class="discount-row">
                            <td>Discount</td>
                            <td>− ৳{{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Shipping Cost</td>
                        <td>৳{{ number_format($order->shipping_cost ?? 0, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td>Grand Total</td>
                        <td>৳{{ number_format($order->grand_total ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ---- FOOTER ---- --}}
        <div class="invoice-footer">
            <p>
                Generated on {{ now()->format('d M Y, h:i A') }}<br>
                Thank you for shopping with us!
            </p>
            <p class="thank-you">⭐Developed by Md. Sadikuzzaman⭐</p>
        </div>

    </div>

</body>

</html>
