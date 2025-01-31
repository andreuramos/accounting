<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoiceNumber ?></title>
    <style>
        /* Print-friendly settings */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .invoice-header {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .business-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }

        .business-info div {
            width: 48%;
            margin-bottom: 10px;
        }
        
        .business-info p {
            margin: 0;
        }

        .business-info h3 {
            margin-bottom: 2px;
            font-size: 16px;
            color: #222;
        }

        .invoice-details {
            margin-bottom: 20px;
            text-align: right;
            font-size: 16px;
        }

        /* Invoice table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .invoice-table th {
            background: #FFC71D;
            color: black;
            font-weight: bold;
        }

        .totals {
            margin-top: 20px;
            text-align: right;
        }

        .totals p {
            font-size: 16px;
            margin: 5px 0;
        }

        .totals strong {
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        Factura #<?= $invoiceNumber ?>
    </div>

    <!-- Business Information -->
    <div class="business-info">
        <div>
            <h3>Facturado por:</h3>
            <p><strong>NIF:</strong> <?= $emitter['tax_id'] ?></p>
            <p><strong>Nombre:</strong> <?= $emitter['tax_name'] ?></p>
            <p><strong>Dirección:</strong> <?= $emitter['address'] ?></p>
        </div>
        <div>
            <h3>Facturado a:</h3>
            <p><strong>NIF:</strong> <?= $receiver['tax_id'] ?></p>
            <p><strong>Nombre:</strong> <?= $receiver['tax_name'] ?></p>
            <p><strong>Dirección:</strong> <?= $receiver['address'] ?></p>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <p><strong>Fecha:</strong> <?= $date ?></p>
    </div>

    <!-- Invoice Items Table -->
    <table class="invoice-table">
        <thead>
        <tr>
            <th>Concepto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>IVA</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lines as $line): ?>
            <tr>
                <td><?= $line['concept'] ?></td>
                <td><?= $line['price'] ?></td>
                <td><?= $line['quantity'] ?></td>
                <td><?= $line['vat'] ?> (<?= $line['vat_percent'] ?>)</td>
                <td><?= $line['line_total'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Invoice Totals -->
    <div class="totals">
        <p><strong>Base Imponible:</strong> <?= $baseAmount ?></p>
        <p><strong>IVA:</strong> <?= $vatAmount ?></p>
        <p><strong>Total:</strong> <span style="color:#FFC71D;"><?= $totalAmount ?></span></p>
    </div>
</div>

</body>
</html>
