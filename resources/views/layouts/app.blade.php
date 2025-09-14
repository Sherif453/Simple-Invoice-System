<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<title>{{ config('app.name','SimpleInvoice') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    :root { --gap: 12px; --border:#ddd; --bg:#f8f8f8; }
    body { font-family: system-ui, Arial, sans-serif; margin:0; background:#fff; color:#222; }
    .nav { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:var(--bg); border-bottom:1px solid var(--border); }
    .container { max-width: 980px; margin: 24px auto; padding: 0 16px; }
    h1,h2,h3 { margin: 0 0 12px; }
    .row { display:flex; gap: var(--gap); flex-wrap:wrap; }
    .col { flex:1 1 260px; }
    .btn { display:inline-block; padding:8px 12px; border:1px solid #333; background:#fff; cursor:pointer; text-decoration:none; color:#111; border-radius:4px; }
    .btn.primary { background:#111; color:#fff; border-color:#111; }
    .btn.small { padding:6px 10px; font-size: 14px; }
    .btn.danger { border-color:#b00; color:#b00; }
    .btn.link { border:none; padding:0; background:none; color:#06c; }
    .table { width:100%; border-collapse:collapse; }
    .table th, .table td { border:1px solid var(--border); padding:8px; text-align:left; }
    .table th { background:var(--bg); }
    .input, .select, .textarea { width:100%; padding:8px; border:1px solid var(--border); border-radius:4px; }
    .alert { padding:10px 12px; border:1px solid #cfc; background:#efe; margin-bottom:12px; }
    .alert.error { border-color:#fcc; background:#fee; }
    .right { text-align:right; }
    .mt { margin-top:16px; }
    .mb { margin-bottom:16px; }
    .pager a, .pager span { margin-right:6px; }
    .muted { color:#666; font-size: 14px; }
</style>
</head>
<body>
<div class="nav">
    <div><a href="{{ route('invoices.index') }}" class="btn link"><strong>SimpleInvoice</strong></a></div>
    <div>
    <a href="{{ route('customers.index') }}" class="btn link">Customers</a>
    <a href="{{ route('products.index') }}" class="btn link">Products</a>
    <a href="{{ route('invoices.create') }}" class="btn primary">+ New Invoice</a>
    </div>
</div>

<div class="container">
    @if(session('ok')) <div class="alert">{{ session('ok') }}</div> @endif
    @if ($errors->any())
    <div class="alert error">
        <div><strong>Please fix the following:</strong></div>
        <ul>
    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif
    @yield('content')
</div>
</body>
</html>
