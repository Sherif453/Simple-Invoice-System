@extends('layouts.app')
@section('content')
  <div class="row mb">
    <h3 style="flex:1">Products</h3>
    <div><a href="{{ route('products.create') }}" class="btn primary">+ New Product</a></div>
  </div>

  <table class="table">
    <thead><tr><th>#</th><th>Name</th><th>SKU</th><th>Price</th><th>Stock</th><th class="right">Actions</th></tr></thead>
    <tbody>
      @foreach($products as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->name }}</td>
          <td>{{ $p->sku }}</td>
          <td>{{ number_format($p->price,2) }}</td>
          <td>{{ $p->stock }}</td>
          <td class="right">
            <a class="btn small" href="{{ route('products.edit',$p) }}">Edit</a>
            <form action="{{ route('products.destroy',$p) }}" method="POST" style="display:inline">
              @csrf @method('DELETE')
              <button class="btn small danger" onclick="return confirm('Delete this product?')">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if ($products->hasPages())
    <div class="pager mt">
      @if ($products->onFirstPage())
        <span class="muted">Prev</span>
      @else
        <a href="{{ $products->previousPageUrl() }}">Prev</a>
      @endif
      <span class="muted">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
      @if ($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}">Next</a>
      @else
        <span class="muted">Next</span>
      @endif
    </div>
  @endif
@endsection
