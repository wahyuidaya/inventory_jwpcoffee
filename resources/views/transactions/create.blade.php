@extends('layouts.app')
@section('page-title', $type === 'masuk' ? 'Bahan Masuk' : 'Bahan Keluar')

@section('content')
<div class="breadcrumb">
  Persediaan Bahan <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ $type === 'masuk' ? route('persediaan.masuk') : route('persediaan.keluar') }}" style="color:#9A8070">
    {{ $type === 'masuk' ? 'Bahan Masuk' : 'Bahan Keluar' }}
  </a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <span>Tambah</span>
</div>
<div class="page-header">
  <h2>Catat {{ $type === 'masuk' ? 'Bahan Masuk' : 'Bahan Keluar' }}</h2>
  <p>Catat bahan baku yang {{ $type === 'masuk' ? 'diterima/dibeli' : 'digunakan/dikeluarkan' }}</p>
</div>

<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">
      @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('persediaan.store') }}">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="form-group">
      <label>Bahan Baku <span style="color:#E53935">*</span></label>
      <select class="form-control" name="product_id" id="tfProd" onchange="showStkInfo(this)" required>
        <option value="">Pilih bahan baku...</option>
        @foreach($products as $p)
        <option value="{{ $p->id }}"
                data-stok="{{ $p->stock }}"
                data-min="{{ $p->minimum_stock }}"
                data-unit="{{ $p->unit }}"
                {{ old('product_id') == $p->id ? 'selected' : '' }}>
          {{ $p->code }} — {{ $p->name }}
        </option>
        @endforeach
      </select>
    </div>

    <div id="stkInfoBox" style="display:none" class="stok-info">
      <div class="stok-info-item"><label>Stok Saat Ini</label><span id="stkVal">—</span></div>
      <div class="stok-info-item"><label>Min. Stok</label><span id="stkMin">—</span></div>
      <div class="stok-info-item"><label>Satuan</label><span id="stkUnit">—</span></div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Jumlah <span style="color:#E53935">*</span></label>
        <input type="number" name="quantity" class="form-control" min="1"
               placeholder="0" value="{{ old('quantity') }}" required>
      </div>
      <div class="form-group">
        <label>Tanggal <span style="color:#E53935">*</span></label>
        <input type="date" name="transaction_date" class="form-control"
               value="{{ old('transaction_date', date('Y-m-d')) }}" required>
      </div>
    </div>

    <div class="form-group">
      <label>Keterangan</label>
      <textarea name="note" class="form-control"
                placeholder="Catatan tambahan (opsional)...">{{ old('note') }}</textarea>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-floppy-disk"></i> Simpan
      </button>
      <a href="{{ $type === 'masuk' ? route('persediaan.masuk') : route('persediaan.keluar') }}"
         class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function showStkInfo(sel) {
  const opt = sel.options[sel.selectedIndex], box = document.getElementById('stkInfoBox');
  if (sel.value) {
    document.getElementById('stkVal').textContent = opt.dataset.stok;
    document.getElementById('stkMin').textContent = opt.dataset.min;
    document.getElementById('stkUnit').textContent = opt.dataset.unit;
    box.style.display = 'flex';
  } else {
    box.style.display = 'none';
  }
}
// Trigger on page load jika old value ada
window.addEventListener('DOMContentLoaded', () => {
  const sel = document.getElementById('tfProd');
  if (sel && sel.value) showStkInfo(sel);
});
</script>
@endpush