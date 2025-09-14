@extends('layouts.app')
@section('content')
  <div class="row mb">
    <h3 style="flex:1">Customers</h3>
    <div><a href="{{ route('customers.create') }}" class="btn primary">+ New Customer</a></div>
  </div>

  <table class="table">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th class="right">Actions</th></tr></thead>
    <tbody>
      @foreach($customers as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->name }}</td>
          <td>{{ $c->email }}</td>
          <td>{{ $c->phone }}</td>
          <td>{{ $c->address }}</td>
          <td class="right">
            <a class="btn small" href="{{ route('customers.edit',$c) }}">Edit</a>
            <form action="{{ route('customers.destroy',$c) }}" method="POST" style="display:inline">
              @csrf @method('DELETE')
              <button class="btn small danger" onclick="return confirm('Delete this customer?')">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if ($customers->hasPages())
    <div class="pager mt">
      @if ($customers->onFirstPage())
        <span class="muted">Prev</span>
      @else
        <a href="{{ $customers->previousPageUrl() }}">Prev</a>
      @endif
      <span class="muted">Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}</span>
      @if ($customers->hasMorePages())
        <a href="{{ $customers->nextPageUrl() }}">Next</a>
      @else
        <span class="muted">Next</span>
      @endif
    </div>
  @endif
@endsection
