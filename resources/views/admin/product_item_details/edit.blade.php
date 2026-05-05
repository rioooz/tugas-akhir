@extends('layouts.admin')

@section('page_title', 'Edit Detail Produk')
@section('breadcrumb', 'Barang / Detail / Edit')

@section('content')
    <div class="data-card">
        <div class="data-header">
            <h3 class="data-title">Edit Detail untuk: {{ $barang->name }}</h3>
        </div>

        <form action="{{ route('admin.barang.details.update', ['barang' => $barang->id, 'detail' => $detail->id]) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.product_item_details._form')
        </form>
    </div>
@endsection
