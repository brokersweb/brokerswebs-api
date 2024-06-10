<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura| Estado de cuenta</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding-bottom: 100px;
        }

        .table_client {
            border-collapse: collapse;
            width: 100%;
            max-width: 650px;
            margin: 20px auto;
            border: 1px solid #ccc;
        }

        .table_client th,
        .table_client td {
            padding: 4px;
            text-align: left;
            border: 1px solid #ccc;
        }

        .table_client th,
        .table_dates th {
            background-color: #f0f0f0;
        }

        .table_dates {
            border-collapse: collapse;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            border: 1px solid #ccc;
        }

        .table_dates th,
        .table_dates td {
            padding: 6px;
            text-align: center;
            border: 1px solid #ccc;
        }

        .company_info {
            text-align: center;
            font-size: 13px;

        }

        .text-right {
            text-align: right;
        }

        .logue {
            width: 100%;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .logue img {
            width: 100%;
            max-height: 100%;
            object-fit: cover;
        }


        .invoice-consecutive {
            text-align: center;
            border: 1px solid #e6e7e7;
            padding: 5px auto;
        }

        .invoice-consecutive h4,
        .invoice-consecutive h5 {
            margin: 0;
            font-size: 1rem;
        }

        .invoice-consecutive h4 {
            font-weight: normal;
            margin-bottom: 0.1875rem;
        }

        .invoice-consecutive h5 {
            font-weight: bold;
            font-size: 0.75rem;
        }

        .totals {
            font-weight: bold;
        }


        .table-bordered {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .text-center {
            text-align: center;
        }

        .table-items tr,
        .table-items th,
        .table-items td {
            border: 1px solid #dee2e6;
        }

        .thead-gray {
            background-color: #e6e7e7;
            text-align: center;
        }

        .table_condition {
            width: 100%;
            text-align: left;
        }

        .text_condition {
            margin: 0px;
        }

        .table_total {
            width: 100%;
            margin: 0px;
        }

        .table_total td,
        .table_total tr,
        .table_total th {
            border: 1px solid #dee2e6;
            margin: 0px;
            padding: 0px;
        }

        .table_total td {
            padding: 5px;
        }

        .table_active {
            background-color: #e6e7e7;
        }

        .observation {
            margin-top: 20px;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px;
            font-size: 10px;
            color: #6c757d;
            box-sizing: border-box;
            text-align: center;
        }

        .footer p {
            margin: 0;
            text-align: center;
        }

        .footer strong {
            color: #495057;
        }

        .watermark {
            position: fixed;
            top: 30%;
            right: -10px;
            transform: translateY(-50%) rotate(-90deg);
            transform-origin: right top;
            font-size: 12px;
            font-weight: bold;
            color: black;
            z-index: -1;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            width: auto;
        }
    </style>

</head>

<body>
    <div class="container mt-4">
        <table class="table table-borderless" style="width: 100%;">
            <tr>
                <td style="width: 25%;">
                    <div class="logue mb-3">
                        <img src="assets/images/brand.png" alt="Logo">
                    </div>
                </td>
                <td class="company_info" style="width: 40%">
                    <strong>{{ $company['name'] }}</strong><br>
                    NIT {{ $company['nit'] }}<br>
                    {{ $company['address'] }}<br>
                    Tel: {{ $company['phone'] }}<br>
                    {{ $company['city'] }} - Colombia<br>
                    {{ $company['email'] }}
                </td>
                <td class="text-center" style="width: 20%">
                    {{-- {{ QrCode::size(100)->generate('Make me into a QrCode!') }} --}}
                   QR CODE
                </td>
                <td style="width: 15%">
                    <div class="invoice-consecutive">
                        <h4 class="invoice-details">Factura</h4>
                        <h5 class="invoice-details">No. {{ $invoice_number }}</h5>
                    </div>
                </td>
            </tr>
        </table>

        <table class="table table-borderless" style="width: 100%;">
            <tr>
                <td style="width: 70%;">
                    <table class="table_client">
                        <tr>
                            <th>Señores</th>
                            <td colspan="3">{{ $customer['name'] }}</td>
                        </tr>
                        <tr>
                            <th>NIT</th>
                            <td>{{ $customer['nit'] ?? '' }}</td>
                            <th>Teléfono</th>
                            <td>{{ $customer['phone'] }}</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>{{ $customer['address'] }}</td>
                            <th>Ciudad</th>
                            <td>Bogotá - Colombia</td>
                        </tr>
                        <tr>
                            <th>Tipo documento</th>
                            <td>CC</td>
                            <th>N° documento</th>
                            <td>43534534543</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%;">
                    <table class="table_dates">
                        <thead>
                            <tr>
                                <th>Fecha de Factura</th>
                                <th>Fecha de Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $date }}</td>
                                <td>{{ $date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table table-bordered table-items mt-2" style="width: 100%">
            <thead class="thead-gray">
                <tr>
                    <th>Ítem</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Vr. Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['description'] }}</td>
                        <td class="text-right">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ number_format($item['total'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table" style="width: 100%">
            <td style="width: 65%">
                <table class="table_condition">
                    <tr>
                        <td class="td_condition">
                            <strong>Valor en Letras:</strong>
                            <p class="text_condition">
                                Un millón noventa y dos mil ochocientos pesos.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_condition">
                            <strong>Condiciones de Pago:</strong>
                            <p class="text_condition">
                                Transferencia
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 35%">
                <table class="table table-bordered table_total" style="width: 100%">
                    <tbody>
                        <tr>
                            <td><strong>Total Bruto</strong></td>
                            <td class="text-right">{{ number_format($total, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>IVA {{ $iva['value'] }}%</td>
                            <td class="text-right">{{ number_format($iva['total'], 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Retefuente {{ $retention['value'] }}%</td>
                            <td class="text-right">{{ number_format($retention['total'], 2, ',', '.') }}</td>
                        </tr>
                        <tr class="table_active">
                            <td><strong>Total a Pagar</strong></td>
                            <td class="text-right totals">{{ number_format($total, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </table>


        <div class="mt-4 observation">
            <strong>Observaciones:</strong><br>
            {{ $observations }}
        </div>

        <footer class="footer">
            <p>
                A esta factura de venta aplican las normas relativas a la letra de cambio (artículo 5 Ley 1231 de 2008).
                Con esta el Comprador declara haber recibido real y materialmente las mercancías o prestación de servicios descritos en este título. <strong>Número Autorización Electrónica 342423 aprobado en 20240304 prefijo fac desde el número 1000 al 5000 Vigencia: 12 Meses</strong><br><br>
                Responsable de IVA - Actividad Económica: Actividades de administración empresarial.
            </p>
            <p>
                <strong>CUFE:</strong> 1234567890123456789012345678901234567890123456789012345678901234
            </p>
        </footer>
    </div>
    <div class="watermark">ELABORADO POR BROSERS SOLUCIONES S.A NIT {{ $company['nit'] }}</div>
</body>

</html>
