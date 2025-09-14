@extends('layouts.app')
@section('content')
  <h3 class="mb">New Customer</h3>
  <form method="POST" action="{{ route('customers.store') }}">
    @csrf
    <div class="row">
      <div class="col"><label>Name<br><input class="input" name="name" required></label></div>
      <div class="col"><label>Email<br><input class="input" type="email" name="email"></label></div>
    </div>
    <div class="row mt">
      <div class="col"><label>Phone<br><input class="input" name="phone"></label></div>
      <div class="col"><label>Address<br><input class="input" name="address"></label></div>
    </div>
    <div class="mt"><button class="btn primary">Save</button></div>
  </form>
@endsection
