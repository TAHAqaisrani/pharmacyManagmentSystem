<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        $discountAmount = 0;
        if (session()->has('discount_token')) {
            $token = session('discount_token');
            $discountAmount = $total * $token['rate'];
        }

        $netTotal = $total - $discountAmount;

        return view('cart.index', compact('cart', 'total', 'discountAmount', 'netTotal'));
    }

    public function add(Request $request, $id)
    {
        $medicine = Medicine::with('batches')->findOrFail($id);
        
        $validBatches = $medicine->batches->filter(function($batch) {
            return $batch->quantity > 0 && 
                   ($batch->expiry_date === null || $batch->expiry_date->gt(now()));
        });

        $stock = $validBatches->sum('quantity');

        if ($stock < 1) {
            return redirect()->back()->with('error', 'Item is out of stock.');
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Find best price (first valid batch)
            $price = $validBatches->first()->selling_price ?? 0;

            $cart[$id] = [
                "name" => $medicine->name,
                "quantity" => 1,
                "price" => $price,
                "photo" => null // Add photo if available
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Medicine added to cart!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }
}
