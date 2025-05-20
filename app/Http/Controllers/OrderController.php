<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $orders = Order::with('user')->latest()->paginate(10);
        } elseif ($user->isSeller()) {
            $sellerProductIds = Product::where('seller_id', $user->id)->pluck('id');
            
            $orderIds = OrderItem::whereIn('product_id', $sellerProductIds)
                ->pluck('order_id')
                ->unique();
                
            $orders = Order::with('user')
                ->whereIn('id', $orderIds)
                ->latest()
                ->paginate(10);
        } else {
            $orders = Order::where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product.seller', 'user');

        $this->authorize('view', $order);
        
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Twój koszyk jest pusty.');
        }
        
        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            $totalAmount = 0;
            $orderItems = [];
            
            foreach ($cart as $productId => $details) {
                $product = Product::findOrFail($productId);
                
                if ($product->stock < $details['quantity']) {
                    throw new \Exception("Niewystarczająca ilość w magazynie produktu {$product->name}");
                }
                
                $product->stock -= $details['quantity'];
                $product->save();
                
                $totalAmount += $product->price * $details['quantity'];
                
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $product->price
                ];
            }
            
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);
            
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            
            Session::forget('cart');
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Zamówienie złożone prawidłowo.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Błąd przy składaniu zamówienia: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);
        
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()->with('success', 'Status zamówienia zaktualizowany prawidłowo.');
    }
}
