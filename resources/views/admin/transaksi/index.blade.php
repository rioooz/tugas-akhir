@extends('layouts.admin')

@section('title', 'Transaksi')

@section('extra_css')
<style>
    .transaction-tabs {
        display: flex;
        border-bottom: 2px solid var(--muted);
        margin-bottom: 20px;
    }
    .tab-link {
        padding: 10px 20px;
        font-weight: 600;
        color: var(--text-light);
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    .tab-link.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="transaction-container">
    <div class="transaction-tabs">
        <a href="#penjualan" class="tab-link active" data-tab="penjualan">Penjualan</a>
        <a href="#pembelian" class="tab-link" data-tab="pembelian">Pembelian</a>
    </div>

    <div id="penjualan" class="tab-content active">
        <h3>Data Transaksi Penjualan</h3>
        <!-- Tabel data penjualan akan dimuat di sini -->
    </div>

    <div id="pembelian" class="tab-content">
        <h3>Data Transaksi Pembelian</h3>
        <!-- Tabel data pembelian akan dimuat di sini -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                tabLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const tabId = this.getAttribute('data-tab');
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === tabId) {
                        content.classList.add('active');
                    }
                });
            });
        });
    });
</script>
@endsection
