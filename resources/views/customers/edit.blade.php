@extends('layouts.app')
@section('content')
  <h3 class="mb">Edit Customer</h3>
  <form method="POST" action="{{ route('customers.update',$customer) }}">
    @csrf @method('PUT')
    <div class="row">
      <div class="col"><label>Name<br><input class="input" name="name" value="{{ $customer->name }}" required></label></div>
      <div class="col"><label>Email<br><input class="input" type="email" name="email" value="{{ $customer->email }}"></label></div>
    </div>
    <div class="row mt">
      <div class="col"><label>Phone<br><input class="input" name="phone" value="{{ $customer->phone }}"></label></div>
      <div class="col"><label>Address<br><input class="input" name="address" value="{{ $customer->address }}"></label></div>
    </div>
    <div class="mt"><button class="btn primary">Update</button></div>
  </form>
@endsection
