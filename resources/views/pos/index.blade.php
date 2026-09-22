@extends('layouts.app')
@section('title', 'Kasir / POS')
@section('page-title', '🛒 Kasir / POS')

@section('content')
<div class="h-[calc(100vh-theme(spacing.16)-theme(spacing.16))] lg:h-[calc(100vh-theme(spacing.16)-theme(spacing.16))] grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 max-w-[1600px] mx-auto">

    <!-- LEFT: Products Panel -->
    <div class="flex flex-col gap-4 overflow-hidden h-full">
        
        <!-- Controls -->
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-sm placeholder-slate-400 dark:placeholder-slate-500 text-slate-900 dark:text-slate-100" placeholder="Cari produk atau SKU...">
            </div>
            <select id="sortInput" class="sm:w-40 py-2.5 px-4 bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-sm cursor-pointer text-slate-700 dark:text-slate-300">
                <option value="name_asc">Urut A - Z</option>
                <option value="stock_desc">Stok Terbanyak</option>
                <option value="stock_asc">Stok Sedikit</option>
            </select>
        </div>

        <!-- Categories -->
        <div class="flex gap-2 overflow-x-auto overflow-y-hidden pb-2 snap-x [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <button type="button" class="cat-tab active shrink-0 flex items-center justify-center px-4 py-2 min-h-[36px] rounded-xl text-xs font-semibold whitespace-nowrap transition-all snap-start bg-indigo-600 text-white border border-transparent shadow-md shadow-indigo-500/30" data-category="">Semua</button>
            @foreach($categories as $cat)
                <button type="button" class="cat-tab shrink-0 flex items-center justify-center px-4 py-2 min-h-[36px] rounded-xl text-xs font-semibold whitespace-nowrap transition-all snap-start bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200" data-category="{{ $cat->id }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        <!-- Product Grid -->
        <div class="flex-1 min-h-0 overflow-y-auto pr-2 pb-20 lg:pb-0">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="productGrid">
                @foreach($products as $product)
                <div class="product-card group bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-2xl p-3 cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-500/50 flex flex-col relative overflow-hidden {{ $product->stock <= 0 ? 'opacity-50 grayscale cursor-not-allowed pointer-events-none' : '' }}"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->price }}"
                 data-stock="{{ $product->stock }}"
                 data-category="{{ $product->category_id }}"
                 data-sku="{{ $product->sku }}"
                 onclick="addToCart(this)">
                
                @if($product->stock <= 0)
                    <div class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-[1px]">
                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider shadow-lg">Habis</span>
                    </div>
                @endif

                <div class="aspect-square mb-3 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center relative">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <span class="text-4xl group-hover:scale-125 transition-transform duration-500">{{ $product->category->name === 'Makanan' ? '🍱' : ($product->category->name === 'Minuman' ? '🥤' : ($product->category->name === 'Snack & Camilan' ? '🍿' : ($product->category->name === 'Kebersihan' ? '🧴' : '📦'))) }}</span>
                    @endif
                </div>
                
                <h3 class="font-semibold text-sm leading-snug mb-1 text-slate-800 dark:text-slate-200 line-clamp-2 flex-1">{{ $product->name }}</h3>
                <div class="flex items-end justify-between mt-2">
                    <div class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <div class="text-[10px] font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-md">Stok: {{ $product->stock }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RIGHT: Cart Panel -->
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl flex flex-col h-full shadow-lg lg:shadow-none overflow-hidden fixed lg:relative bottom-0 left-0 right-0 z-40 lg:z-auto transition-transform duration-300 translate-y-full lg:translate-y-0" id="cartPanel">
        
        <!-- Mobile Cart Handle -->
        <div class="h-1.5 w-12 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mt-3 mb-2 lg:hidden cursor-pointer" onclick="toggleMobileCart()"></div>

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between bg-white/50 dark:bg-[#1a1d27]/50 backdrop-blur-md">
            <div class="flex items-center gap-2">
                <span class="text-xl">🛒</span>
                <h2 class="font-bold text-slate-900 dark:text-slate-100">Keranjang</h2>
                <span class="bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold px-2 py-0.5 rounded-full" id="cartBadge">0</span>
            </div>
            <button class="text-xs font-semibold text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 px-3 py-1.5 rounded-lg transition-colors" onclick="clearCart()">Kosongkan</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 relative scrollbar-thin" id="cartItems">
            <!-- Empty State -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500" id="cartEmpty">
                <span class="text-5xl mb-3 opacity-50 grayscale">🛍️</span>
                <p class="font-medium text-sm">Keranjang masih kosong</p>
                <p class="text-xs mt-1 opacity-70">Klik produk untuk menambahkan</p>
            </div>
        </div>

        <div class="p-5 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-800/20">
            
            <div class="mb-4">
                <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Diskon</label>
                <div class="grid grid-cols-6 gap-1.5">
                    @foreach([0, 3, 5, 7, 10, 15] as $pct)
                        <button class="discount-btn py-1.5 rounded-lg text-xs font-bold transition-all border {{ $pct === 0 ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/20 active' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-500/50 hover:text-indigo-600 dark:hover:text-indigo-400' }}" data-pct="{{ $pct }}" onclick="setDiscount(this)">{{ $pct }}%</button>
                    @endforeach
                </div>
                <input type="hidden" id="discountInput" value="0">
            </div>

            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span id="summarySubtotal" class="font-medium text-slate-700 dark:text-slate-300">Rp 0</span>
                </div>
                <div class="flex justify-between text-amber-500 dark:text-amber-400">
                    <span>Diskon</span>
                    <span id="summaryDiscount" class="font-medium">- Rp 0</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-200 dark:border-slate-700/50 mt-3">
                    <span class="font-bold text-slate-900 dark:text-slate-100">Total</span>
                    <span id="summaryTotal" class="text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">Rp 0</span>
                </div>
            </div>

            <button class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2" id="payBtn" disabled onclick="openPaymentModal()">
                <span>Proses Pembayaran</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile FAB to open cart -->
    <button class="lg:hidden fixed bottom-6 right-6 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-2xl flex items-center justify-center z-30 transition-transform active:scale-90" onclick="toggleMobileCart()" id="fabCart">
        <div class="relative">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-indigo-600 hidden" id="fabBadge">0</span>
        </div>
    </button>
    <div id="mobileCartOverlay" class="fixed inset-0 bg-slate-900/40 dark:bg-black/60 z-30 hidden backdrop-blur-sm transition-opacity opacity-0" onclick="toggleMobileCart()"></div>

</div>

<!-- Payment Modal -->
<div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity opacity-0 duration-300" id="paymentModal">
    <div class="bg-white dark:bg-[#1a1d27] rounded-3xl shadow-2xl w-full max-w-[420px] flex flex-col md:flex-row overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="paymentModalBox">
        
        <div class="p-6 md:p-8 flex-1 w-full max-w-[420px] shrink-0">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2"><span class="text-2xl">💳</span> Pembayaran</h3>
                <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 p-2 rounded-full transition-colors" onclick="closePaymentModal()">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Metode Pembayaran</label>
                <div class="flex gap-2">
                    <div class="pay-method selected flex-1 py-2.5 px-2 rounded-xl text-center border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 font-bold text-sm cursor-pointer transition-all shadow-sm" data-method="cash" onclick="selectMethod(this)">💵 Tunai</div>
                    <div class="pay-method flex-1 py-2.5 px-2 rounded-xl text-center border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-sm cursor-pointer transition-all hover:border-indigo-300 dark:hover:border-indigo-500/50" data-method="transfer" onclick="selectMethod(this)">🏦 Transfer</div>
                    <div class="pay-method flex-1 py-2.5 px-2 rounded-xl text-center border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-sm cursor-pointer transition-all hover:border-indigo-300 dark:hover:border-indigo-500/50" data-method="qris" onclick="selectMethod(this)">📱 QRIS</div>
                </div>
            </div>

            <div id="cashGroup" class="transition-all duration-300 ease-in-out overflow-hidden max-h-[300px] opacity-100">
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jumlah Bayar (Rp)</label>
                    <input type="text" id="paymentAmount" class="w-full text-right font-bold text-xl py-3 px-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-slate-900 dark:text-slate-100" placeholder="0" oninput="formatPaymentInput(this); calcChange()">
                    
                    <div class="mt-3 p-4 rounded-xl bg-slate-100 dark:bg-slate-800/80 flex items-center justify-between border border-slate-200 dark:border-slate-700/50">
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Kembalian</span>
                        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="changeDisplay">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Catatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" id="notesInput" class="w-full py-2.5 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-slate-900 dark:text-slate-100" placeholder="Tambahkan catatan transaksi...">
            </div>

            <button class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2" onclick="submitPayment()" id="submitPayBtn">
                <span>Konfirmasi Pembayaran</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>

        <!-- Right Pane: QRIS -->
        <div class="w-full md:w-0 h-0 md:h-auto opacity-0 overflow-hidden transition-all duration-300 ease-in-out shrink-0 bg-slate-50 dark:bg-slate-900 border-t md:border-t-0 border-slate-200 dark:border-slate-800" id="qrisPane">
            <div class="w-full md:w-[380px] p-6 md:p-8 flex flex-col items-center justify-center h-full">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-slate-100 mb-2">Scan QRIS</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Silakan pembeli scan barcode ini melalui aplikasi E-Wallet atau M-Banking.</p>
                </div>
                
                <div class="p-4 bg-white rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none mb-6 border border-slate-100 dark:border-slate-700 relative overflow-hidden group w-full max-w-[240px] flex justify-center">
                    <div class="absolute inset-0 bg-indigo-500/5 animate-pulse rounded-2xl"></div>
                    <img src="{{ asset($settings['qris_image'] ?? 'images/qris.jpg') }}" alt="QRIS" class="w-full aspect-square object-contain relative z-10">
                </div>

                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-4 py-2 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-500/20">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pastikan pembayaran berhasil!
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Receipt Modal -->
<div class="fixed inset-0 bg-slate-900/80 dark:bg-black/90 z-[200] hidden items-center justify-center p-4 backdrop-blur-md transition-opacity opacity-0 duration-300" id="receiptOverlay">
    <div class="bg-white text-slate-900 rounded-2xl p-6 w-full max-w-[340px] font-mono text-xs shadow-2xl transform scale-95 opacity-0 transition-all duration-300 relative" id="receiptBox">
        
        <!-- Zigzag bottom edge effect -->
        <div class="absolute bottom-[-10px] left-0 right-0 h-[10px]" style="background: linear-gradient(-45deg, transparent 33.33%, #fff 33.33%, #fff 66.66%, transparent 66.66%), linear-gradient(45deg, transparent 33.33%, #fff 33.33%, #fff 66.66%, transparent 66.66%); background-size: 20px 20px;"></div>

        <div class="text-center mb-4">
            <h2 class="text-xl font-black tracking-widest uppercase mb-1">{{ $settings['receipt_header'] ?? 'KASIRQ' }}</h2>
            <div class="text-slate-500 text-[10px]">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
        
        <div class="border-t-2 border-dashed border-slate-300 my-3"></div>
        
        <div id="receiptItems" class="space-y-1 my-3"></div>
        
        <div class="border-t-2 border-dashed border-slate-300 my-3"></div>
        
        <div class="space-y-1 mb-3">
            <div class="flex justify-between text-slate-600"><span>Subtotal</span><span id="receiptSubtotal"></span></div>
            <div class="flex justify-between text-slate-600"><span>Diskon</span><span id="receiptDiscount"></span></div>
            <div class="flex justify-between text-base font-black mt-2 pt-2 border-t border-slate-200"><span>TOTAL</span><span id="receiptTotal"></span></div>
        </div>
        
        <div class="space-y-1 mb-4 text-slate-600">
            <div class="flex justify-between"><span>Bayar</span><span id="receiptPayment"></span></div>
            <div class="flex justify-between"><span>Kembalian</span><span id="receiptChange"></span></div>
        </div>
        
        <div class="border-t-2 border-dashed border-slate-300 my-3"></div>
        
        <div id="receiptInvoice" class="text-center text-[10px] text-slate-400 font-bold mb-1"></div>
        <div class="text-center text-[10px] text-slate-500">{{ $settings['receipt_footer'] ?? 'Terima Kasih!' }}</div>
        
        <div class="flex gap-2 mt-6 relative z-10">
            <button class="flex-1 bg-slate-900 hover:bg-black text-white font-bold py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors" onclick="window.print()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </button>
            <button class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold py-2.5 rounded-lg transition-colors" onclick="closeReceipt()">
                Selesai
            </button>
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

/* ─── Mobile Cart Logic ─── */
const cartPanel = document.getElementById('cartPanel');
const mobileCartOverlay = document.getElementById('mobileCartOverlay');
let mobileCartOpen = false;

function toggleMobileCart() {
    if(window.innerWidth >= 1024) return; // Only apply on < lg
    
    mobileCartOpen = !mobileCartOpen;
    if(mobileCartOpen) {
        cartPanel.classList.remove('translate-y-full');
        mobileCartOverlay.classList.remove('hidden');
        setTimeout(() => mobileCartOverlay.classList.remove('opacity-0'), 10);
        document.body.classList.add('overflow-hidden');
    } else {
        cartPanel.classList.add('translate-y-full');
        mobileCartOverlay.classList.add('opacity-0');
        setTimeout(() => mobileCartOverlay.classList.add('hidden'), 300);
        document.body.classList.remove('overflow-hidden');
    }
}

/* ─── Barcode Scanner Logic ─── */
let barcodeBuffer = '';
let barcodeTimeout = null;

document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' && e.target.id !== 'searchInput') return;

    if (e.key === 'Enter') {
        e.preventDefault();
        
        let codeToProcess = barcodeBuffer.trim();
        if (!codeToProcess && document.activeElement.id === 'searchInput') {
            codeToProcess = document.getElementById('searchInput').value.trim();
        }

        if (codeToProcess) {
            processBarcode(codeToProcess);
            barcodeBuffer = '';
            document.getElementById('searchInput').value = '';
            filterProducts();
        }
    } else {
        if (e.key.length === 1) {
            barcodeBuffer += e.key;
            clearTimeout(barcodeTimeout);
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
        const searchInput = document.getElementById('searchInput');
        searchInput.classList.add('ring-4', 'ring-indigo-500/30', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        setTimeout(() => searchInput.classList.remove('ring-4', 'ring-indigo-500/30', 'bg-indigo-50', 'dark:bg-indigo-900/20'), 200);
    } else {
        alert('Produk dengan SKU/Barcode "' + sku + '" tidak ditemukan!');
    }
}

/* ─── Product filter & sort ─── */
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('sortInput').addEventListener('change', sortProducts);

document.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-tab').forEach(b => {
            b.classList.remove('active', 'bg-indigo-600', 'text-white', 'border-transparent', 'shadow-md', 'shadow-indigo-500/30');
            b.classList.add('bg-white', 'dark:bg-[#1a1d27]', 'border-slate-200', 'dark:border-slate-800/60', 'text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-50');
        });
        btn.classList.remove('bg-white', 'dark:bg-[#1a1d27]', 'border-slate-200', 'dark:border-slate-800/60', 'text-slate-600', 'dark:text-slate-400', 'hover:bg-slate-50');
        btn.classList.add('active', 'bg-indigo-600', 'text-white', 'border-transparent', 'shadow-md', 'shadow-indigo-500/30');
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

sortProducts();

/* ─── Cart ─── */
function addToCart(card) {
    if (card.classList.contains('opacity-50')) return;
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

    // Bouncy animation
    card.classList.add('scale-95');
    setTimeout(() => card.classList.remove('scale-95'), 150);

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
    
    // Update Badges
    const totalItems = Object.values(cart).reduce((sum, item) => sum + item.qty, 0);
    document.getElementById('cartBadge').textContent = totalItems;
    
    const fabBadge = document.getElementById('fabBadge');
    if(totalItems > 0) {
        fabBadge.textContent = totalItems;
        fabBadge.classList.remove('hidden');
    } else {
        fabBadge.classList.add('hidden');
    }

    if (ids.length === 0) {
        container.innerHTML = '';
        container.appendChild(empty);
        empty.classList.remove('hidden');
        document.getElementById('payBtn').disabled = true;
        updateSummary();
        return;
    }

    empty.classList.add('hidden');
    container.innerHTML = '';

    ids.forEach(id => {
        const item = cart[id];
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl shadow-sm hover:shadow-md transition-shadow animate-fade-in-down';
        div.innerHTML = `
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-slate-800 dark:text-slate-200 truncate">${item.name}</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">${fmt(item.price)} / pcs</div>
            </div>
            <div class="flex items-center gap-1.5 bg-slate-50 dark:bg-slate-900 p-1 rounded-xl border border-slate-200 dark:border-slate-700">
                <button class="w-7 h-7 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 font-bold transition-colors" onclick="changeQty('${id}', -1)">−</button>
                <span class="w-6 text-center text-sm font-bold text-slate-900 dark:text-slate-100">${item.qty}</span>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 font-bold transition-colors" onclick="changeQty('${id}', 1)">+</button>
            </div>
            <div class="w-20 text-right text-sm font-bold text-emerald-600 dark:text-emerald-400">
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
    document.querySelectorAll('.discount-btn').forEach(b => {
        b.classList.remove('active', 'bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'shadow-indigo-500/20');
        b.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400', 'border-slate-200', 'dark:border-slate-700');
    });
    
    el.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400', 'border-slate-200', 'dark:border-slate-700');
    el.classList.add('active', 'bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'shadow-indigo-500/20');
    
    currentDiscountPct = parseFloat(el.dataset.pct);
    updateSummary();
}

function updateSummary() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = Math.floor(subtotal * (currentDiscountPct / 100));
    const total = Math.max(0, subtotal - discount);

    document.getElementById('discountInput').value = discount;

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
    
    selectMethod(document.querySelector('.pay-method[data-method="cash"]'));
    
    const modal = document.getElementById('paymentModal');
    const box = document.getElementById('paymentModalBox');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    const box = document.getElementById('paymentModalBox');
    
    modal.classList.add('opacity-0');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function selectMethod(el) {
    document.querySelectorAll('.pay-method').forEach(m => {
        m.classList.remove('selected', 'border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-500/10', 'text-indigo-700', 'dark:text-indigo-400');
        m.classList.add('border-slate-200', 'dark:border-slate-700', 'text-slate-600', 'dark:text-slate-400');
    });
    
    el.classList.remove('border-slate-200', 'dark:border-slate-700', 'text-slate-600', 'dark:text-slate-400');
    el.classList.add('selected', 'border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-500/10', 'text-indigo-700', 'dark:text-indigo-400');
    
    selectedMethod = el.dataset.method;
    
    const cashGroup = document.getElementById('cashGroup');
    if(selectedMethod === 'cash') {
        cashGroup.classList.remove('max-h-0', 'opacity-0');
        cashGroup.classList.add('max-h-[300px]', 'opacity-100');
    } else {
        cashGroup.classList.remove('max-h-[300px]', 'opacity-100');
        cashGroup.classList.add('max-h-0', 'opacity-0');
    }
    
    const modalBox = document.getElementById('paymentModalBox');
    const qrisPane = document.getElementById('qrisPane');
    
    if (selectedMethod === 'qris') {
        modalBox.classList.remove('max-w-[420px]');
        modalBox.classList.add('max-w-[800px]');
        
        qrisPane.classList.remove('md:w-0', 'h-0', 'opacity-0', 'border-t-0', 'md:border-l-0');
        qrisPane.classList.add('md:w-[380px]', 'h-[460px]', 'md:h-auto', 'opacity-100', 'border-t', 'md:border-l');
    } else {
        modalBox.classList.add('max-w-[420px]');
        modalBox.classList.remove('max-w-[800px]');
        
        qrisPane.classList.remove('md:w-[380px]', 'h-[460px]', 'md:h-auto', 'opacity-100', 'border-t', 'md:border-l');
        qrisPane.classList.add('md:w-0', 'h-0', 'opacity-0', 'border-t-0', 'md:border-l-0');
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
    
    const display = document.getElementById('changeDisplay');
    display.textContent = fmt(change);
    
    if(change >= 0) {
        display.classList.remove('text-red-500', 'dark:text-red-400');
        display.classList.add('text-emerald-600', 'dark:text-emerald-400');
    } else {
        display.classList.add('text-red-500', 'dark:text-red-400');
        display.classList.remove('text-emerald-600', 'dark:text-emerald-400');
    }
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
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

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
        btn.innerHTML = `<span>Konfirmasi Pembayaran</span><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
    }
}

function showReceipt(data, total, paid) {
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const subtotal = total + discount;
    const change = paid - total;

    let itemsHtml = '';
    Object.values(cart).forEach(item => {
        itemsHtml += `<div class="flex justify-between"><span>${item.name} <span class="text-[10px]">x${item.qty}</span></span><span>${fmt(item.price * item.qty)}</span></div>`;
    });

    document.getElementById('receiptItems').innerHTML = itemsHtml;
    document.getElementById('receiptSubtotal').textContent = fmt(subtotal);
    document.getElementById('receiptDiscount').textContent = '- ' + fmt(discount);
    document.getElementById('receiptTotal').textContent = fmt(total);
    document.getElementById('receiptPayment').textContent = fmt(paid);
    document.getElementById('receiptChange').textContent = fmt(change);
    document.getElementById('receiptInvoice').textContent = data.invoice_number;

    const modal = document.getElementById('receiptOverlay');
    const box = document.getElementById('receiptBox');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeReceipt() {
    const modal = document.getElementById('receiptOverlay');
    const box = document.getElementById('receiptBox');
    
    modal.classList.add('opacity-0');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        cart = {};
        document.getElementById('discountInput').value = 0;
        renderCart();
        location.reload();
    }, 300);
}
</script>
@endpush
