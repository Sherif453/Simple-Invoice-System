@extends('layouts.app')
@section('content')
  <div class="row mb">
    <h3 style="flex:1">Invoices</h3>
    <div><a class="btn primary" href="{{ route('invoices.create') }}">+ New Invoice</a></div>
  </div>

  <table class="table">
    <thead><tr><th>#</th><th>Number</th><th>Client</th><th>Date</th><th>Total</th><th>Status</th><th class="right">Actions</th></tr></thead>
    <tbody>
      @foreach($invoices as $inv)
        <tr>
          <td>{{ $inv->id }}</td>
          <td>{{ $inv->invoice_number }}</td>
          <td>{{ $inv->customer->name ?? '-' }}</td>
          <td>{{ $inv->invoice_date }}</td>
          <td>{{ number_format($inv->total,2) }}</td>
          <td>
            <form method="POST" action="{{ route('invoices.status', $inv) }}" style="display:inline">
            @csrf @method('PATCH')
            <select name="status" class="select" onchange="this.form.submit()">
              <option value="unpaid"  {{ $inv->status==='unpaid'  ? 'selected' : '' }}>Unpaid</option>
              <option value="paid"    {{ $inv->status==='paid'    ? 'selected' : '' }}>Paid</option>
              <option value="partial" {{ $inv->status==='partial' ? 'selected' : '' }}>Partial</option>
            </select>
            </form>
          </td>
          <td class="right">
            <a class="btn small" href="{{ route('invoices.show',$inv) }}">View</a>
            <a class="btn small" href="{{ route('invoices.pdf',$inv) }}">PDF</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if ($invoices->hasPages())
    <div class="pager mt">
      @if ($invoices->onFirstPage())
        <span class="muted">Prev</span>
      @else
        <a href="{{ $invoices->previousPageUrl() }}">Prev</a>
      @endif
      <span class="muted">Page {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }}</span>
      @if ($invoices->hasMorePages())
        <a href="{{ $invoices->nextPageUrl() }}">Next</a>
      @else
        <span class="muted">Next</span>
      @endif
    </div>
  @endif
@endsection
