<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - {{ $order->numero_orden }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .customer-section {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .customer-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #007bff;
        }
        .customer-details {
            display: table;
            width: 100%;
        }
        .customer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .customer-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #007bff;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        .totals-table .total-row {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .payment-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="company-info">
            <div class="company-name">{{ $empresa['nombre'] }}</div>
            <div>{{ $empresa['direccion'] }}</div>
            <div>Tel: {{ $empresa['telefono'] }}</div>
            <div>Email: {{ $empresa['email'] }}</div>
            <div><strong>NIT:</strong> {{ $empresa['nit'] }}</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">FACTURA</div>
            <div><strong>No. Orden:</strong> {{ $order->numero_orden }}</div>
            <div><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div><strong>Estado:</strong> <span class="status-badge">{{ ucfirst($order->estado) }}</span></div>
            @if($order->guia_envio)
                <div style="margin-top: 10px;">
                    <strong>Guía de Envío:</strong><br>
                    {{ $order->empresa_envio }}<br>
                    {{ $order->guia_envio }}
                </div>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div class="customer-section">
        <div class="customer-title">INFORMACIÓN DEL CLIENTE</div>
        <div class="customer-details">
            <div class="customer-left">
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    {{ $order->nombre_cliente }}
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    {{ $order->email_cliente }}
                </div>
                <div class="info-row">
                    <span class="info-label">Teléfono:</span>
                    {{ $order->telefono_cliente }}
                </div>
            </div>
            <div class="customer-right">
                <div class="info-row">
                    <span class="info-label">DPI:</span>
                    {{ $order->dpi }}
                </div>
                <div class="info-row">
                    <span class="info-label">NIT:</span>
                    {{ $order->nit }}
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección:</span>
                    {{ $order->direccion_entrega }}<br>
                    {{ $order->ciudad }}, {{ $order->departamento }}
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="payment-info">
        <strong>MÉTODO DE PAGO: CONTRA ENTREGA</strong><br>
        El pago se realizará al momento de recibir los productos.
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Código</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->nombre_producto }}</strong>
                    @if($item->descripcion_producto)
                        <br><small>{{ $item->descripcion_producto }}</small>
                    @endif
                </td>
                <td>{{ $item->codigo_producto }}</td>
                <td class="text-center">{{ $item->cantidad }}</td>
                <td class="text-right">Q{{ number_format($item->precio_unitario, 2) }}</td>
                <td class="text-right">Q{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="clearfix">
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">Q{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Envío:</td>
                    <td class="text-right">Q{{ number_format($order->envio, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="text-right">Q{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($order->notas_cliente)
    <div style="margin-top: 30px;">
        <strong>Notas del Cliente:</strong><br>
        {{ $order->notas_cliente }}
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>{{ $empresa['nombre'] }} - {{ $empresa['direccion'] }}</p>
        <p>Tel: {{ $empresa['telefono'] }} | Email: {{ $empresa['email'] }} | NIT: {{ $empresa['nit'] }}</p>
        <p><strong>Gracias por su compra</strong></p>
    </div>
</body>
</html> 