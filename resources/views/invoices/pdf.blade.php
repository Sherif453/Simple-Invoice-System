<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color:#222; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border:1px solid #888; padding:6px; text-align:left; }
    th { background:#f2f2f2; }
    h2 { margin-bottom: 6px; }
  </style>
</head>
<body>
  <h2>Invoice {{ $invoice->invoice_number }}</h2>
  <p><strong>Client:</strong> {{ $invoice->customer->name }}</p>
  <p><strong>Date:</strong> {{ $invoice->invoice_date }}</p>

  <table>
    <thead><tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
    <tbody>
      @foreach($invoice->items as $it)
      <tr>
        <td>{{ $it->description }}</td>
        <td>{{ $it->quantity }}</td>
        <td>{{ number_format($it->unit_price,2) }}</td>
        <td>{{ number_format($it->line_total,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table style="margin-top:12px; width:40%;">
    <tr><th>Subtotal</th><td style="text-align:right">{{ number_format($invoice->subtotal,2) }}</td></tr>
    <tr><th>Discount</th><td style="text-align:right">{{ number_format($invoice->discount,2) }}</td></tr>
    <tr><th>Tax</th><td style="text-align:right">{{ number_format($invoice->tax,2) }}</td></tr>
    <tr><th>Total</th><td style="text-align:right"><strong>{{ number_format($invoice->total,2) }}</strong></td></tr>
  </table>
</body>
</html>
