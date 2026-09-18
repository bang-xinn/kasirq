<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();
        $products = Product::where('is_active', true)->with('category')->get();
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        return view('pos.index', compact('categories', 'products', 'settings'));
    }

    public function getProducts(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true)->where('stock', '>', 0);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        return response()->json($query->get());
    }

    public function processPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'payment_amount' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(422, "Stok {$product->name} tidak mencukupi. Tersisa: {$product->stock}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $discountAmount = $validated['discount_amount'] ?? 0;
            $total = $subtotal - $discountAmount;
            $changeAmount = $validated['payment_amount'] - $total;

            $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.str_pad(
                Transaction::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT,
            );

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => 0,
                'total' => $total,
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $changeAmount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $itemData) {
                TransactionItem::create(array_merge($itemData, ['transaction_id' => $transaction->id]));
            }

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'total' => $transaction->total,
                'change_amount' => $transaction->change_amount,
                'message' => 'Transaksi berhasil!',
            ]);
        });
    }
}
