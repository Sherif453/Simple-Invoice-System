@extends('layouts.app')

@section('content')
  <div class="row mb">
    <h3 style="flex:1">Invoice: {{ $invoice->invoice_number }}</h3>
    <form method="POST" action="{{ route('invoices.status', $invoice) }}" style="display:flex; gap:8px; align-items:center">
      @csrf @method('PATCH')
      <label>Status&nbsp;
        <select name="status" class="select">
          <option value="unpaid"  {{ $invoice->status==='unpaid'  ? 'selected' : '' }}>Unpaid</option>
          <option value="paid"    {{ $invoice->status==='paid'    ? 'selected' : '' }}>Paid</option>
          <option value="partial" {{ $invoice->status==='partial' ? 'selected' : '' }}>Partial</option>
        </select>
      </label>
      <button class="btn small">Update</button>
    </form>
  </div>

  <p><strong>Client:</strong> {{ $invoice->customer->name ?? '-' }}</p>
  <p><strong>Date:</strong> {{ $invoice->invoice_date }}</p>
  @if($invoice->due_date)
    <p><strong>Due:</strong> {{ $invoice->due_date }}</p>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>Description</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoice->items as $it)
        <tr>
          <td>{{ $it->description }}</td>
          <td>{{ $it->quantity }}</td>
          <td>{{ number_format($it->unit_price, 2) }}</td>
          <td>{{ number_format($it->line_total, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row mt">
    <div class="col" style="flex:0 0 320px; margin-left:auto;">
      <table class="table">
        <tr>
          <th>Subtotal</th>
          <td class="right">{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
          <th>Discount</th>
          <td class="right">{{ number_format($invoice->discount, 2) }}</td>
        </tr>
        <tr>
          <th>Tax</th>
          <td class="right">{{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr>
          <th>Total</th>
          <td class="right"><strong>{{ number_format($invoice->total, 2) }}</strong></td>
        </tr>
      </table>
    </div>
  </div>

  <div class="mt">
    <a class="btn primary" href="{{ route('invoices.pdf', $invoice) }}">Download PDF</a>
    <a class="btn" href="{{ route('invoices.index') }}">Back</a>
  </div>
@endsection
