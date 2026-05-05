@extends('layouts.admin')

@section('page_title', 'Tambah Detail Produk')
@section('breadcrumb', 'Barang / Detail / Tambah')

@section('content')
    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Tambah Detail untuk: {{ $barang->name }}</h3>
        </div>

        <form action="{{ route('admin.barang.details.store', $barang->id) }}" method="POST">
            @csrf
            @include('admin.product_item_details._form')
        </form>
    </div>
@endsection
