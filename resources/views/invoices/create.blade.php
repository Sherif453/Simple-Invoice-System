@extends('layouts.app')
@section('content')
  <h3 class="mb">New Invoice</h3>

  <form method="POST" action="{{ route('invoices.store') }}">
    @csrf
    <div class="row">
      <div class="col">
        <label>Customer<br>
          <select name="customer_id" class="select" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </label>
      </div>
      <div class="col">
        <label>Invoice Number<br>
          <input class="input" name="invoice_number" value="{{ $nextNumber }}" required>
        </label>
      </div>
      <div class="col">
        <label>Invoice Date<br>
          <input class="input" type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required>
        </label>
      </div>
    </div>

    <div class="row mt">
      <div class="col" style="max-width:280px">
        <label>Status<br>
          <select name="status" class="select">
            <option value="unpaid" selected>Unpaid</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
          </select>
        </label>
      </div>
    </div>

    <hr class="mt">

    <h4 class="mb">Items</h4>
    <table class="table" id="itemsTable">
      <thead>
        <tr>
          <th style="width:22%">Product</th>
          <th>Description</th>
          <th style="width:90px">Qty</th>
          <th style="width:140px">Unit Price</th>
          <th style="width:140px">Line Total</th>
          <th style="width:70px"></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <button type="button" class="btn" onclick="addRow()">+ Add Item</button>

    <div class="row mt">
      <div class="col">
        <label>Discount<br><input id="discount" name="discount" class="input" type="number" step="0.01" min="0" value="0" oninput="recalc()"></label>
      </div>
      <div class="col">
        <label>Tax<br><input id="tax" name="tax" class="input" type="number" step="0.01" min="0" value="0" oninput="recalc()"></label>
      </div>
      <div class="col">
        <label>Total<br><input id="grandTotal" class="input" readonly></label>
      </div>
    </div>

    <div class="mt">
      <button class="btn primary">Save Invoice</button>
    </div>
  </form>

<script>
const PRODUCTS = {!! json_encode($productList, JSON_UNESCAPED_UNICODE) !!};

function productOptions(selectedId='') {
  return `<option value="">--</option>` + PRODUCTS.map(p =>
    `<option value="${p.id}" ${String(selectedId)===String(p.id)?'selected':''}>${p.name} (${p.sku})</option>`
  ).join('');
}

function addRow(){
  const tbody = document.querySelector('#itemsTable tbody');
  const idx = tbody.children.length;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <select class="select" name="items[${idx}][product_id]" onchange="fillProduct(${idx}, this.value)">
        ${productOptions()}
      </select>
    </td>
    <td><input class="input" name="items[${idx}][description]" required></td>
    <td><input class="input" type="number" min="1" value="1" name="items[${idx}][quantity]" oninput="recalcRow(${idx})" required></td>
    <td><input class="input" type="number" step="0.01" min="0" value="0" name="items[${idx}][unit_price]" oninput="recalcRow(${idx})" required></td>
    <td><input class="input" type="number" step="0.01" name="items[${idx}][line_total]" readonly></td>
    <td><button type="button" class="btn small danger" onclick="removeRow(this)">X</button></td>
  `;
  tbody.appendChild(tr);
  recalcRow(idx);
}

function removeRow(btn){
  btn.closest('tr').remove();
  renumber();
  recalc();
}

function fillProduct(idx, id){
  const p = PRODUCTS.find(x=> String(x.id)===String(id));
  if(!p) return;
  document.querySelector(`[name="items[${idx}][description]"]`).value = p.name;
  document.querySelector(`[name="items[${idx}][unit_price]"]`).value = Number(p.price).toFixed(2);
  recalcRow(idx);
}

function recalcRow(idx){
  const q = parseFloat(document.querySelector(`[name="items[${idx}][quantity]"]`)?.value||0);
  const u = parseFloat(document.querySelector(`[name="items[${idx}][unit_price]"]`)?.value||0);
  document.querySelector(`[name="items[${idx}][line_total]"]`).value = (q*u).toFixed(2);
  recalc();
}
function recalc(){
  let subtotal = 0;
  document.querySelectorAll('[name$="[line_total]"]').forEach(i=> subtotal += parseFloat(i.value||0));
  const discount = parseFloat(document.getElementById('discount').value||0);
  const tax = parseFloat(document.getElementById('tax').value||0);
  const total = Math.max(0, subtotal - discount + tax);
  document.getElementById('grandTotal').value = total.toFixed(2);
}
function renumber(){
  const rows = [...document.querySelectorAll('#itemsTable tbody tr')];
  rows.forEach((tr, i)=>{
    tr.querySelectorAll('input,select').forEach(el=>{
      el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
      if (el.oninput?.toString().includes('recalcRow(')) el.setAttribute('oninput', `recalcRow(${i})`);
      if (el.onchange?.toString().includes('fillProduct(')) el.setAttribute('onchange', `fillProduct(${i}, this.value)`);
    });
  });
}
addRow();
</script>
@endsection
