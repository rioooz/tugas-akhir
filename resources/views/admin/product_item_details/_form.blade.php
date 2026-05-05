<div class="form-group">
    <label>Nama</label>
    <input type="text" name="name" value="{{ old('name', $detail->name ?? '') }}" class="form-control" required>
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Ukuran</label>
    <input type="text" name="size" value="{{ old('size', $detail->size ?? '') }}" class="form-control">
    @error('size') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Harga</label>
    <input type="number" step="0.01" name="price" value="{{ old('price', $detail->price ?? 0) }}" class="form-control" required>
    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Stok</label>
    <input type="number" name="stock" value="{{ old('stock', $detail->stock ?? 0) }}" class="form-control" required>
    @error('stock') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label>Deskripsi</label>
    <textarea name="description" class="form-control">{{ old('description', $detail->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div style="margin-top:12px">
    <button class="btn-add" type="submit">Simpan</button>
    <a href="{{ route('admin.barang.details.index', $barang->id) }}" class="action-btn">Batal</a>
</div>
