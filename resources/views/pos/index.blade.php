@extends('layouts.app')
@section('title', 'Kasir / POS')
@section('page-title', '🛒 Kasir / POS')

@push('styles')
<style>
    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 20px;
        height: calc(100vh - var(--topbar-h) - 56px);
    }

    /* Left: Product panel */
    .product-panel { display: flex; flex-direction: column; gap: 16px; overflow: hidden; }

    .search-bar { display: flex; gap: 10px; }

    .category-tabs {
        display: flex; gap: 8px; flex-wrap: wrap;
    }

    .cat-tab {
        padding: 6px 14px; border-radius: 20px; cursor: pointer;
        font-size: 12px; font-weight: 500;
        background: var(--bg-hover); border: 1px solid var(--border);
        color: var(--text-secondary); transition: all .2s;
        white-space: nowrap;
    }

    .cat-tab:hover, .cat-tab.active {
        background: var(--accent); color: #fff; border-color: var(--accent);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        overflow-y: auto;
        padding-right: 4px;
        flex: 1;
        align-content: start;
    }

    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px; padding: 16px;
        cursor: pointer; transition: all .2s;
        display: flex; flex-direction: column;
    }

    .product-card:hover {
        border-color: var(--accent);
        box-shadow: 0 4px 20px var(--accent-glow);
        transform: translateY(-2px);
    }

    .product-card.out-of-stock {
        opacity: .4; cursor: not-allowed;
    }

    .product-card.out-of-stock:hover {
        transform: none; box-shadow: none; border-color: var(--border);
    }

    .product-emoji { font-size: 28px; margin-bottom: 8px; }
    .product-name { font-size: 13px; font-weight: 600; line-height: 1.3; margin-bottom: 4px; flex: 1; }
    .product-price { font-size: 13px; color: var(--success); font-weight: 700; }
    .product-stock { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

    /* Right: Cart panel */
    .cart-panel {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        display: flex; flex-direction: column;
        overflow: hidden;
    }

    .cart-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }

    .cart-title { font-size: 15px; font-weight: 700; }

    .cart-items { flex: 1; overflow-y: auto; padding: 12px; }

    .cart-empty {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; height: 100%;
        color: var(--text-muted); text-align: center;
        gap: 8px; font-size: 13px;
    }

    .cart-empty .icon { font-size: 40px; opacity: .4; }

    .cart-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 8px; border-radius: 10px;
        transition: background .15s;
        margin-bottom: 4px;
    }

    .cart-item:hover { background: var(--bg-hover); }

    .cart-item-name { flex: 1; font-size: 13px; font-weight: 500; }
    .cart-item-price { font-size: 12px; color: var(--text-muted); }

    .qty-controls {
        display: flex; align-items: center; gap: 6px;
    }

    .qty-btn {
        width: 26px; height: 26px; border-radius: 6px;
        background: var(--bg-hover); border: 1px solid var(--border);
        color: var(--text-primary); font-size: 14px; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .15s;
    }

    .qty-btn:hover { background: var(--accent); border-color: var(--accent); }
    .qty-btn.remove { color: var(--danger); }
    .qty-btn.remove:hover { background: rgba(239,68,68,.15); border-color: rgba(239,68,68,.3); color: var(--danger); }

    .qty-value { font-size: 13px; font-weight: 700; min-width: 20px; text-align: center; }

    .cart-footer { padding: 16px; border-top: 1px solid var(--border); }

    .summary-row {
        display: flex; justify-content: space-between;
        font-size: 13px; padding: 4px 0; color: var(--text-secondary);
    }

    .summary-row.total {
        font-size: 16px; font-weight: 700; color: var(--text-primary);
        padding: 10px 0; border-top: 1px solid var(--border); margin-top: 6px;
    }

    .discount-input {
        display: flex; flex-direction: column; gap: 8px; margin: 10px 0;
    }

    .discount-btn {
        flex: 1; padding: 6px 0; border-radius: 6px; border: 1px solid var(--border);
        background: var(--bg-hover); color: var(--text-secondary); font-size: 11px;
        font-weight: 700; cursor: pointer; transition: all .15s;
    }
    .discount-btn:hover { border-color: var(--accent); color: var(--accent); }
    .discount-btn.active { background: var(--accent); color: #fff; border-color: var(--accent); }

    /* Payment Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.7); z-index: 200;
        align-items: center; justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.open { display: flex; animation: fadeIn .2s ease; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px; 
        width: 100%; max-width: 420px;
        animation: slideUp .25s ease;
        display: flex;
        overflow: hidden;
        transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-box.show-qris { max-width: 800px; }

    .modal-left {
        padding: 28px;
        flex: 1;
        min-width: 400px;
    }

    .modal-right {
        background: #ffffff;
        width: 380px;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        border-left: 1px solid var(--border);
    }

    .modal-box.show-qris .modal-right {
        display: flex;
        animation: fadeIn 0.3s ease 0.1s both;
    }

    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; }

    .payment-methods { display: flex; gap: 8px; margin-bottom: 20px; }
    .pay-method {
        flex: 1; padding: 10px; border-radius: 10px; text-align: center;
        border: 2px solid var(--border); cursor: pointer; font-size: 13px;
        font-weight: 600; color: var(--text-secondary); transition: all .15s;
    }
    .pay-method:hover { border-color: var(--accent); color: var(--accent); }
    .pay-method.selected { border-color: var(--accent); background: rgba(99,102,241,.1); color: var(--accent); }

    .change-display {
        background: var(--bg-hover); border-radius: 10px; padding: 14px;
        text-align: center; margin: 12px 0;
    }

    .change-label { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; }
    .change-value { font-size: 24px; font-weight: 800; color: var(--success); }

    /* Receipt overlay */
    .receipt-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.8); z-index: 300;
        align-items: center; justify-content: center;
        backdrop-filter: blur(6px);
    }

    .receipt-overlay.open { display: flex; }

    .receipt-box {
        background: #fff; color: #111;
        border-radius: 12px; padding: 24px;
        width: 100%; max-width: 320px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }

    .receipt-brand { text-align: center; margin-bottom: 12px; }
    .receipt-brand h2 { font-size: 18px; font-weight: 900; }
    .receipt-divider { border-top: 1px dashed #ccc; margin: 10px 0; }
    .receipt-row { display: flex; justify-content: space-between; padding: 2px 0; }
    .receipt-total { font-size: 16px; font-weight: 900; }
    .receipt-actions { display: flex; gap: 8px; margin-top: 16px; }
    .receipt-actions button {
        flex: 1; padding: 10px; border-radius: 8px; font-size: 13px;
        font-weight: 600; cursor: pointer; border: none;
    }
    .btn-print { background: #111; color: #fff; }
    .btn-close-receipt { background: #f0f0f0; color: #111; }
</style>
@endpush

@section('content')
<div class="pos-layout">

    <!-- LEFT: Products -->
    <div class="product-panel">
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari produk atau SKU...">
            <select id="sortInput" class="form-control" style="max-width: 140px; font-size: 12px; cursor: pointer;">
                <option value="name_asc">Urut A - Z</option>
                <option value="stock_desc">Stok Terbanyak</option>
                <option value="stock_asc">Stok Sedikit</option>
            </select>
        </div>

        <div class="category-tabs">
            <button class="cat-tab active" data-category="">Semua</button>
            @foreach($categories as $cat)
                <button class="cat-tab" data-category="{{ $cat->id }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        <div class="product-grid" id="productGrid">
            @foreach($products as $product)
            <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->price }}"
                 data-stock="{{ $product->stock }}"
                 data-category="{{ $product->category_id }}"
                 data-sku="{{ $product->sku }}"
                 onclick="addToCart(this)">
                <div class="product-emoji">
                    {{ $product->category->name === 'Makanan' ? '🍱' : ($product->category->name === 'Minuman' ? '🥤' : ($product->category->name === 'Snack & Camilan' ? '🍿' : ($product->category->name === 'Kebersihan' ? '🧴' : '📦'))) }}
                </div>
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="product-stock">Stok: {{ $product->stock }} {{ $product->unit }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- RIGHT: Cart -->
    <div class="cart-panel">
        <div class="cart-header">
            <span class="cart-title">🛒 Keranjang</span>
            <button class="btn btn-danger btn-sm" onclick="clearCart()">Kosongkan</button>
        </div>

        <div class="cart-items" id="cartItems">
            <div class="cart-empty" id="cartEmpty">
                <div class="icon">🛒</div>
                <span>Keranjang masih kosong</span>
                <span class="text-xs">Klik produk untuk menambahkan</span>
            </div>
        </div>

        <div class="cart-footer">
            <div class="discount-input">
                <label class="form-label" style="margin-bottom:0; font-size:12px">Diskon:</label>
                <div style="display:flex; gap:6px;">
                    <button class="discount-btn active" data-pct="0" onclick="setDiscount(this)">0%</button>
                    <button class="discount-btn" data-pct="3" onclick="setDiscount(this)">3%</button>
                    <button class="discount-btn" data-pct="5" onclick="setDiscount(this)">5%</button>
                    <button class="discount-btn" data-pct="7" onclick="setDiscount(this)">7%</button>
                    <button class="discount-btn" data-pct="10" onclick="setDiscount(this)">10%</button>
                    <button class="discount-btn" data-pct="15" onclick="setDiscount(this)">15%</button>
                </div>
                <input type="hidden" id="discountInput" value="0">
            </div>
            <div class="summary-row"><span>Subtotal</span><span id="summarySubtotal">Rp 0</span></div>
            <div class="summary-row"><span>Diskon</span><span id="summaryDiscount" style="color:var(--warning)">- Rp 0</span></div>
            <div class="summary-row total"><span>Total</span><span id="summaryTotal">Rp 0</span></div>
            <button class="btn btn-primary w-full" style="margin-top:12px; padding:13px; font-size:15px; justify-content:center"
                    onclick="openPaymentModal()" id="payBtn" disabled>
                Proses Pembayaran →
            </button>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal-overlay" id="paymentModal">
    <div class="modal-box" id="paymentModalBox">
        <div class="modal-left">
            <div class="modal-title">💳 Pembayaran</div>

            <div style="margin-bottom:14px">
                <div class="form-label">Metode Pembayaran</div>
                <div class="payment-methods">
                    <div class="pay-method selected" data-method="cash" onclick="selectMethod(this)">💵 Tunai</div>
                    <div class="pay-method" data-method="transfer" onclick="selectMethod(this)">🏦 Transfer</div>
                    <div class="pay-method" data-method="qris" onclick="selectMethod(this)">📱 QRIS</div>
                </div>
            </div>

            <div class="form-group" id="cashGroup">
                <label class="form-label">Jumlah Bayar (Rp)</label>
                <input type="text" id="paymentAmount" class="form-control" placeholder="0" oninput="formatPaymentInput(this); calcChange()">
                <div class="change-display">
                    <div class="change-label">Kembalian</div>
                    <div class="change-value" id="changeDisplay">Rp 0</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan (opsional)</label>
                <input type="text" id="notesInput" class="form-control" placeholder="Catatan transaksi...">
            </div>

            <div class="flex gap-2">
                <button class="btn btn-secondary" style="flex:1; justify-content:center" onclick="closePaymentModal()">Batal</button>
                <button class="btn btn-primary" style="flex:1; justify-content:center" onclick="submitPayment()" id="submitPayBtn">Bayar ✓</button>
            </div>
        </div>

        <!-- Right Pane: QRIS -->
        <div class="modal-right">
            <h3 style="color:#111; font-weight:800; font-size:18px; margin-bottom:8px">Scan QRIS</h3>
            <p style="color:#666; font-size:13px; text-align:center; margin-bottom:20px;">Silakan pembeli scan barcode ini melalui aplikasi E-Wallet atau M-Banking.</p>
            <img src="{{ asset($settings['qris_image'] ?? 'images/qris.jpg') }}" alt="QRIS" style="width:100%; max-width:300px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div style="margin-top:20px; font-size:12px; font-weight:700; color:#ef4444; background:#fef2f2; padding:8px 16px; border-radius:20px;">
                Pastikan pembayaran berhasil!
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="receipt-overlay" id="receiptOverlay">
    <div class="receipt-box">
        <div class="receipt-brand">
            <h2>{{ $settings['receipt_header'] ?? '★ KasirQ ★' }}</h2>
            <div style="font-size:11px; color:#555">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div class="receipt-divider"></div>
        <div id="receiptItems"></div>
        <div class="receipt-divider"></div>
        <div class="receipt-row"><span>Subtotal</span><span id="receiptSubtotal"></span></div>
        <div class="receipt-row"><span>Diskon</span><span id="receiptDiscount"></span></div>
        <div class="receipt-row receipt-total"><span>TOTAL</span><span id="receiptTotal"></span></div>
        <div class="receipt-row"><span>Bayar</span><span id="receiptPayment"></span></div>
        <div class="receipt-row"><span>Kembalian</span><span id="receiptChange"></span></div>
        <div class="receipt-divider"></div>
        <div id="receiptInvoice" style="text-align:center; font-size:11px; color:#777"></div>
        <div style="text-align:center; margin-top:6px; font-size:11px; color:#777">{{ $settings['receipt_footer'] ?? 'Terima kasih! 🙏' }}</div>
        <div class="receipt-actions">
            <button class="btn-print" onclick="window.print()">🖨️ Cetak</button>
            <button class="btn-close-receipt" onclick="closeReceipt()">✕ Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = {};
let selectedMethod = 'cash';
let currentDiscountPct = 0;

const fmt = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');

/* ─── Barcode Scanner Logic ─── */
let barcodeBuffer = '';
let barcodeTimeout = null;

document.addEventListener('keydown', function(e) {
    // Abaikan jika user sedang mengetik di input form selain search (seperti input diskon/catatan)
    if (e.target.tagName === 'INPUT' && e.target.id !== 'searchInput') return;

    // Jika Enter ditekan
    if (e.key === 'Enter') {
        e.preventDefault();
        
        // Coba pakai buffer dari scanner, kalau kosong coba ambil value dari searchInput
        let codeToProcess = barcodeBuffer.trim();
        if (!codeToProcess && document.activeElement.id === 'searchInput') {
            codeToProcess = document.getElementById('searchInput').value.trim();
        }

        if (codeToProcess) {
            processBarcode(codeToProcess);
            barcodeBuffer = '';
            document.getElementById('searchInput').value = ''; // Kosongkan search
            filterProducts(); // Reset filter
        }
    } else {
        // Tambahkan karakter ke buffer jika berupa angka atau huruf
        if (e.key.length === 1) {
            barcodeBuffer += e.key;
            clearTimeout(barcodeTimeout);
            // Scanner biasa sangat cepat, jika lebih dari 100ms tidak ada ketikan = bukan scanner
            barcodeTimeout = setTimeout(() => {
                barcodeBuffer = '';
            }, 100); 
        }
    }
});

function processBarcode(sku) {
    const cards = Array.from(document.querySelectorAll('.product-card'));
    const matchedCard = cards.find(card => card.dataset.sku.toLowerCase() === sku.toLowerCase());
    
    if (matchedCard) {
        addToCart(matchedCard);
        // Visual feedback kecil di search bar
        const searchInput = document.getElementById('searchInput');
        searchInput.style.background = 'rgba(99,102,241,0.2)';
        setTimeout(() => searchInput.style.background = '', 200);
    } else {
        alert('Produk dengan SKU/Barcode "' + sku + '" tidak ditemukan!');
    }
}

/* ─── Product filter & sort ─── */
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('sortInput').addEventListener('change', sortProducts);

document.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        filterProducts();
    });
});

function filterProducts() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const category = document.querySelector('.cat-tab.active').dataset.category;
    document.querySelectorAll('.product-card').forEach(card => {
        const matchSearch = card.dataset.name.toLowerCase().includes(search) || card.dataset.sku.toLowerCase().includes(search);
        const matchCat = !category || card.dataset.category === category;
        card.style.display = matchSearch && matchCat ? '' : 'none';
    });
}

function sortProducts() {
    const sortType = document.getElementById('sortInput').value;
    const grid = document.getElementById('productGrid');
    const cards = Array.from(grid.querySelectorAll('.product-card'));

    cards.sort((a, b) => {
        if (sortType === 'name_asc') {
            return a.dataset.name.localeCompare(b.dataset.name);
        } else if (sortType === 'stock_desc') {
            return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
        } else if (sortType === 'stock_asc') {
            return parseInt(a.dataset.stock) - parseInt(b.dataset.stock);
        }
    });

    cards.forEach(card => grid.appendChild(card));
}

// Initial Sort
sortProducts();

/* ─── Cart ─── */
function addToCart(card) {
    if (card.classList.contains('out-of-stock')) return;
    const id = card.dataset.id;
    const maxStock = parseInt(card.dataset.stock);

    if (!cart[id]) {
        cart[id] = {
            id, name: card.dataset.name,
            price: parseFloat(card.dataset.price),
            qty: 0, maxStock
        };
    }

    if (cart[id].qty >= maxStock) {
        alert('Stok tidak mencukupi!');
        return;
    }

    cart[id].qty++;

    // Pulse animation
    card.style.transform = 'scale(0.95)';
    setTimeout(() => card.style.transform = '', 150);

    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
        delete cart[id];
    }
    renderCart();
}

function clearCart() {
    cart = {};
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty = document.getElementById('cartEmpty');
    const ids = Object.keys(cart);

    if (ids.length === 0) {
        container.innerHTML = '';
        container.appendChild(empty);
        empty.style.display = 'flex';
        document.getElementById('payBtn').disabled = true;
        updateSummary();
        return;
    }

    empty.style.display = 'none';
    container.innerHTML = '';

    ids.forEach(id => {
        const item = cart[id];
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div style="flex:1">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${fmt(item.price)} / pcs</div>
            </div>
            <div class="qty-controls">
                <button class="qty-btn remove" onclick="changeQty('${id}', -1)">−</button>
                <span class="qty-value">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty('${id}', 1)">+</button>
            </div>
            <div style="min-width:80px; text-align:right; font-size:13px; font-weight:700; color:var(--success)">
                ${fmt(item.price * item.qty)}
            </div>
        `;
        container.appendChild(div);
    });

    container.appendChild(empty);
    document.getElementById('payBtn').disabled = false;
    updateSummary();
}

function setDiscount(el) {
    document.querySelectorAll('.discount-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    currentDiscountPct = parseFloat(el.dataset.pct);
    updateSummary();
}

function updateSummary() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = Math.floor(subtotal * (currentDiscountPct / 100));
    const total = Math.max(0, subtotal - discount);

    document.getElementById('discountInput').value = discount; // Store nominal discount for other functions

    document.getElementById('summarySubtotal').textContent = fmt(subtotal);
    document.getElementById('summaryDiscount').textContent = '- ' + fmt(discount);
    document.getElementById('summaryTotal').textContent = fmt(total);
}

/* ─── Payment Modal ─── */
function openPaymentModal() {
    if (Object.keys(cart).length === 0) return;
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = Math.max(0, subtotal - discount);
    document.getElementById('paymentAmount').value = total.toLocaleString('id-ID');
    calcChange();
    
    // Reset modal state
    selectMethod(document.querySelector('.pay-method[data-method="cash"]'));
    
    document.getElementById('paymentModal').classList.add('open');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('open');
}

function selectMethod(el) {
    document.querySelectorAll('.pay-method').forEach(m => m.classList.remove('selected'));
    el.classList.add('selected');
    selectedMethod = el.dataset.method;
    
    document.getElementById('cashGroup').style.display = selectedMethod === 'cash' ? '' : 'none';
    
    const modalBox = document.getElementById('paymentModalBox');
    if (selectedMethod === 'qris') {
        modalBox.classList.add('show-qris');
    } else {
        modalBox.classList.remove('show-qris');
    }

    if (selectedMethod !== 'cash') {
        document.getElementById('changeDisplay').textContent = 'Rp 0';
    }
}

function formatPaymentInput(input) {
    let value = input.value.replace(/\D/g, '');
    if (!value) {
        input.value = '';
    } else {
        input.value = parseInt(value, 10).toLocaleString('id-ID');
    }
}

function calcChange() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = Math.max(0, subtotal - discount);
    const paidText = document.getElementById('paymentAmount').value.replace(/\D/g, '');
    const paid = parseFloat(paidText) || 0;
    const change = Math.max(0, paid - total);
    document.getElementById('changeDisplay').textContent = fmt(change);
    document.getElementById('changeDisplay').style.color = change >= 0 ? 'var(--success)' : 'var(--danger)';
}

async function submitPayment() {
    const items = Object.values(cart).map(i => ({ product_id: i.id, quantity: i.qty }));
    const subtotal = items.reduce((s, i) => s + (cart[i.product_id]?.price || 0) * i.quantity, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = Math.max(0, subtotal - discount);
    const paidText = document.getElementById('paymentAmount').value.replace(/\D/g, '');
    const paid = selectedMethod === 'cash' ? parseFloat(paidText) || 0 : total;

    if (selectedMethod === 'cash' && paid < total) {
        alert('Jumlah bayar kurang dari total!');
        return;
    }

    const btn = document.getElementById('submitPayBtn');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    try {
        const res = await fetch('{{ route("pos.payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                items: Object.values(cart).map(i => ({ product_id: i.id, quantity: i.qty })),
                payment_method: selectedMethod,
                payment_amount: paid,
                discount_amount: discount,
                notes: document.getElementById('notesInput').value || null,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || 'Terjadi kesalahan!');
            return;
        }

        closePaymentModal();
        showReceipt(data, total, paid);
    } catch (e) {
        alert('Gagal menghubungi server. Coba lagi.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Bayar ✓';
    }
}

function showReceipt(data, total, paid) {
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const subtotal = total + discount;
    const change = paid - total;

    let itemsHtml = '';
    Object.values(cart).forEach(item => {
        itemsHtml += `<div class="receipt-row"><span>${item.name} x${item.qty}</span><span>${fmt(item.price * item.qty)}</span></div>`;
    });

    document.getElementById('receiptItems').innerHTML = itemsHtml;
    document.getElementById('receiptSubtotal').textContent = fmt(subtotal);
    document.getElementById('receiptDiscount').textContent = '- ' + fmt(discount);
    document.getElementById('receiptTotal').textContent = fmt(total);
    document.getElementById('receiptPayment').textContent = fmt(paid);
    document.getElementById('receiptChange').textContent = fmt(change);
    document.getElementById('receiptInvoice').textContent = data.invoice_number;

    document.getElementById('receiptOverlay').classList.add('open');
}

function closeReceipt() {
    document.getElementById('receiptOverlay').classList.remove('open');
    cart = {};
    document.getElementById('discountInput').value = 0;
    renderCart();
    // Reload page to refresh stock numbers
    location.reload();
}
</script>
@endpush
