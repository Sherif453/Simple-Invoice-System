@extends('layouts.app')
@section('content')
  <h3 class="mb">Edit Product</h3>
  <form method="POST" action="{{ route('products.update',$product) }}">
    @csrf @method('PUT')
    <div class="row">
      <div class="col"><label>Name<br><input class="input" name="name" value="{{ $product->name }}" required></label></div>
      <div class="col"><label>SKU<br><input class="input" name="sku" value="{{ $product->sku }}" required></label></div>
    </div>
    <div class="row mt">
      <div class="col"><label>Price<br><input class="input" name="price" type="number" step="0.01" min="0" value="{{ $product->price }}" required></label></div>
      <div class="col"><label>Stock<br><input class="input" name="stock" type="number" min="0" value="{{ $product->stock }}" required></label></div>
    </div>
    <div class="mt"><label>Description<br><textarea class="textarea" name="description">{{ $product->description }}</textarea></label></div>
    <div class="mt"><button class="btn primary">Update</button></div>
  </form>
@endsection
