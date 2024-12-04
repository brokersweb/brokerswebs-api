<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Stock Bajo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .logo {
            max-width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <img src="{{ $companyInfo['logo'] }}" class="logo" alt="Logo">
            <h2>{{ $companyInfo['name'] }}</h2>
            <p>NIT: {{ $companyInfo['nit'] }}</p>
            <p>Teléfono: {{ $companyInfo['phone'] }}</p>
        </div>
        <div>
            <h3>Reporte de Materiales con Stock Bajo</h3>
            <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
            <p>Umbral de Stock: {{ $threshold }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Cantidad Actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materials as $material)
                <tr>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->code }}</td>
                    <td>{{ $material->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
