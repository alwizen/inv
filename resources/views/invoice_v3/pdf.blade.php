<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #111827;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 0;
            margin: 0;
        }

        .wrapper {
            display: flex;
            width: 100%;
            min-height: 297mm;
        }

        .receipt-block {
            width: 105mm;
            min-height: 148.5mm;
            padding: 6mm;
            border-right: 1px dashed #999;
        }

        .spacer {
            flex: 1;
            min-height: 148.5mm;
            padding: 6mm;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .muted {
            color: #4b5563;
            font-size: 8px;
        }

        .divider {
            border-top: 1px dashed #111827;
            margin: 2.5mm 0;
        }

        .info {
            margin-bottom: 2mm;
        }

        .info div {
            margin-bottom: 0.5mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items th,
        .items td {
            padding: 1mm 0;
            vertical-align: top;
            font-size: 8px;
        }

        .items th {
            border-bottom: 1px dashed #111827;
            padding-bottom: 1mm;
            text-transform: uppercase;
        }

        .items th:nth-child(1),
        .items td:nth-child(1) {
            width: 52%;
            text-align: left;
            word-break: break-word;
        }

        .items th:nth-child(2),
        .items td:nth-child(2) {
            width: 12%;
            text-align: right;
            white-space: nowrap;
        }

        .items th:nth-child(3),
        .items td:nth-child(3) {
            width: 18%;
            text-align: right;
            white-space: nowrap;
        }

        .items th:nth-child(4),
        .items td:nth-child(4) {
            width: 18%;
            text-align: right;
            white-space: nowrap;
        }

        .qty,
        .price,
        .amount {
            text-align: right;
        }

        .totals {
            margin-top: 1mm;
        }

        .totals td {
            padding: 0.8mm 0;
        }

        .total-row {
            font-weight: 700;
            border-top: 1px dashed #111827;
            padding-top: 1.5mm;
        }

        .footer {
            margin-top: 3mm;
            font-size: 8px;
        }

        .thank-you {
            margin-top: 2mm;
            text-align: center;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="wrapper">
            <div class="receipt-block">
                <div class="center">
                    <div class="title">{{ $invoice->company->name ?? 'Invoice' }}</div>
                    <div class="muted">{{ $invoice->company->address ?? '-' }}</div>
                    <div class="muted">{{ $invoice->company->phone ?? '' }}</div>
                </div>

                <div class="divider"></div>

                <div class="info">
                    <div><strong>No Invoice:</strong> #{{ $invoice->invoice_number }}</div>
                    <div><strong>Tanggal:</strong> {{ optional($invoice->invoice_date)->format('d/m/Y') }}</div>
                    <div><strong>Pelanggan:</strong> {{ $invoice->recipient }}</div>
                    <div class="muted">{{ $invoice->recipient_address }}</div>
                </div>

                <table class="items">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="qty">Qty</th>
                            <th class="price">Harga</th>
                            <th class="amount">Sub</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td class="qty">{{ $item->quantity }}</td>
                                <td class="price">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td class="amount">{{ number_format($item->quantity * $item->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="divider"></div>

                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td class="amount">{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if ($invoice->use_ppn)
                        <tr>
                            <td>PPN ({{ $invoice->ppn_percentage }}%)</td>
                            <td class="amount">{{ number_format($invoice->ppn_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="amount">{{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                </table>

                @if (!empty($invoice->note))
                    <div class="footer"><strong>Catatan:</strong> {{ $invoice->note }}</div>
                @endif

                <div class="thank-you">Terima kasih</div>
            </div>

            <div class="spacer"></div>
        </div>
    </div>
</body>
</html>
