<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?= $invoiceNumber ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .header {
            display: inline-block;
            margin-bottom: 30px;
            width: 100%;
        }

        .header .logo {
            max-width: 250px;
            float: left;
        }

        .header .invoice-info {
            text-align: right;
            float: right;
        }

        .details {
            width: 100%;
            margin-bottom: 20px;
            margin-top: 20%;
            vertical-align: top;
        }

        .details .emitter, .details .receiver {
            width: 50%;
            box-sizing: border-box; /* Prevents overflow */
        }

        .details .emitter p, .details .receiver p {
            margin: 5px 0; /* Reduces spacing between lines */
        }

        .details-content {
            margin-right: 15px;
            margin-left: 15px;
        }

        .invoice-lines {
            margin-bottom: 20px;
        }

        .invoice-lines table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-lines th, .invoice-lines td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .invoice-lines th {
            background-color: #f1f1f1;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="<?= $logo ?>" alt="Moixa" style="max-width: 100%; height: auto;">
        </div>
        <div class="invoice-info">
            <h1>FACTURA</h1>
            <p><strong>Nº:</strong> <?= $invoiceNumber ?></p>
            <p><strong>Fecha:</strong> <?= $date ?></p>
        </div>
    </div>

    <!-- Emitter and Receiver Details -->
    <div class="details">
        <table>
            <tr>
                <td class="emitter">
                    <div class="details-content">
                        <h2>Emisor</h2>
                        <p><strong>Nombre:</strong> <?= $emitter['tax_name'] ?></p>
                        <p><strong>NIF:</strong> <?= $emitter['tax_id'] ?></p>
                        <p><strong>Dirección:</strong> <?= $emitter['address'] ?></p>
                    </div>
                </td>
                <!--<td></td>  Spacer column -->
                <td class="receiver">
                    <div class="details-content">
                        <h2>Receptor</h2>
                        <p><strong>Nombre:</strong> <?= $receiver['tax_name'] ?></p>
                        <p><strong>NIF:</strong> <?= $receiver['tax_id'] ?></p>
                        <p><strong>Dirección:</strong> <?= $receiver['address'] ?></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Invoice Lines -->
    <div class="invoice-lines">
        <table>
            <thead>
            <tr>
                <th>Concepto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>IVA (%)</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lines as $line) : ?>
                <tr>
                    <td><?= $line['concept'] ?></td>
                    <td><?= $line['price'] ?></td>
                    <td><?= $line['quantity'] ?></td>
                    <td><?= $line['vat_percent'] ?></td>
                    <td><?= $line['line_total'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="totals">
        <p><strong>Base Imponible:</strong> <?= $baseAmount ?></p>
        <p><strong>IVA:</strong> <?= $vatAmount ?></p>
        <p><strong>Total Factura:</strong> <?= $totalAmount ?></p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>IBAN: <?= $bankAccount ?></p>
    </div>
</div>
</body>
</html>