<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .order-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .product-name {
            font-weight: 500;
            color: #1f2937;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: 600;
            background-color: #f9fafb;
            padding: 15px 12px;
        }
        .status-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .message {
            background-color: #f0f9ff;
            border-left: 4px solid #0284c7;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 14px;
            color: #0c4a6e;
        }
        .contact-section {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .contact-section h3 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .contact-section p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .footer a {
            color: #0284c7;
            text-decoration: none;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📦 Order Confirmation</h1>
            <p>Order #{{ $order->id }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            @if($recipient === 'buyer')
                <!-- Buyer's Email -->
                <p style="margin-bottom: 20px;">
                    Hi <strong>{{ $buyer->name }}</strong>,
                </p>

                <div class="message">
                    <strong>✓ Your order has been confirmed!</strong> Thank you for your purchase. We've received your order and it's being prepared for shipment. You can track your order status below.
                </div>

                <!-- Order Information -->
                <div class="section">
                    <h2>Order Details</h2>
                    <div class="order-info">
                        <div class="info-row">
                            <span class="info-label">Order ID:</span>
                            <span class="info-value">#{{ $order->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Order Date:</span>
                            <span class="info-value">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="status-badge">{{ ucfirst($order->status ?? 'pending') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Seller:</span>
                            <span class="info-value">{{ $seller->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Items Ordered -->
                <div class="section">
                    <h2>Items Ordered</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($orderItems as $item)
                                @php
                                    $itemTotal = $item->quantity * $item->unit_price;
                                    $total += $itemTotal;
                                @endphp
                                <tr>
                                    <td class="product-name">{{ $item->product->name }}</td>
                                    <td class="text-right">{{ $item->quantity }}</td>
                                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-right">${{ number_format($itemTotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3" class="text-right">Order Total:</td>
                                <td class="text-right">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Shipping Information -->
                <div class="section">
                    <h2>Shipping Address</h2>
                    <div class="order-info">
                        <p style="margin-bottom: 5px;"><strong>{{ $buyer->name }}</strong></p>
                        <p style="margin-bottom: 5px;">{{ $buyer->street ?? 'Address not provided' }}</p>
                        <p>{{ $buyer->city ?? 'City not provided' }}</p>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="section">
                    <h2>What Happens Next?</h2>
                    <p style="margin-bottom: 15px; font-size: 14px; color: #374151;">
                        Your order will be carefully packaged and shipped to you. You'll receive a shipping update email with tracking information as soon as your package is on its way.
                    </p>
                    <ul style="margin-left: 20px; font-size: 14px; color: #374151;">
                        <li style="margin-bottom: 8px;">✓ Your order is being prepared</li>
                        <li style="margin-bottom: 8px;">→ Shipping notification coming soon</li>
                        <li>📦 Track your package after shipment</li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="contact-section">
                    <h3>Need Help?</h3>
                    <p>If you have any questions about your order, please don't hesitate to contact us.</p>
                    <p>📧 <a href="mailto:support@crafty.com">support@crafty.com</a></p>
                </div>

            @else
                <!-- Seller's Email -->
                <p style="margin-bottom: 20px;">
                    Hi <strong>{{ $seller->name }}</strong>,
                </p>

                <div class="message">
                    <strong>📦 You have a new order!</strong> A customer has placed an order for your products. Please prepare the item(s) for shipment and update the order status when shipped.
                </div>

                <!-- Order Information -->
                <div class="section">
                    <h2>New Order Details</h2>
                    <div class="order-info">
                        <div class="info-row">
                            <span class="info-label">Order ID:</span>
                            <span class="info-value">#{{ $order->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Order Date:</span>
                            <span class="info-value">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Customer:</span>
                            <span class="info-value">{{ $buyer->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Customer Email:</span>
                            <span class="info-value">{{ $buyer->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Items to Ship -->
                <div class="section">
                    <h2>Items to Ship</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Quantity</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($orderItems as $item)
                                @php
                                    $itemTotal = $item->quantity * $item->unit_price;
                                    $total += $itemTotal;
                                @endphp
                                <tr>
                                    <td class="product-name">{{ $item->product->name }}</td>
                                    <td class="text-right">{{ $item->quantity }}</td>
                                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-right">${{ number_format($itemTotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3" class="text-right">Order Total:</td>
                                <td class="text-right">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Shipping Information -->
                <div class="section">
                    <h2>Customer Shipping Address</h2>
                    <div class="order-info">
                        <p style="margin-bottom: 5px;"><strong>{{ $buyer->name }}</strong></p>
                        <p style="margin-bottom: 5px;">{{ $buyer->street ?? 'Address not provided' }}</p>
                        <p style="margin-bottom: 5px;">{{ $buyer->city ?? 'City not provided' }}</p>
                        <p>{{ $buyer->email }}</p>
                    </div>
                </div>

                <!-- Action Items -->
                <div class="section">
                    <h2>Action Required</h2>
                    <ul style="margin-left: 20px; font-size: 14px; color: #374151;">
                        <li style="margin-bottom: 8px;">📋 Verify product availability</li>
                        <li style="margin-bottom: 8px;">📦 Pack and prepare for shipment</li>
                        <li style="margin-bottom: 8px;">🚚 Update order status with tracking info when shipped</li>
                    </ul>
                </div>

                <!-- Portal Link -->
                <div class="contact-section">
                    <h3>Manage Your Orders</h3>
                    <p>Log in to your seller dashboard to manage this order and view shipping details.</p>
                </div>

            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong><Canvas></Canvas></strong></p>
            <p>Email: support@crafty.com</p>
            <p style="margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>
