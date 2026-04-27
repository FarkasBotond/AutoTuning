<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\TuningProduct;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        $order = DB::transaction(function () use ($data, $request) {
            $productIds = collect($data['items'])->pluck('id')->all();
            $products = TuningProduct::whereIn('id', $productIds)->get()->keyBy('id');
            $productsTotal = 0;
            $paymentFee = $data['payment_method'] === 'cash_on_delivery' ? 1000 : 0;

            $order = Order::create([
                'user_id' => optional($request->user())->id,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'delivery_method' => $data['delivery_method'],
                'country' => $data['country'],
                'city' => $data['city'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'street_name' => $data['street_name'] ?? null,
                'house_number' => $data['house_number'] ?? null,
                'note' => $data['note'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_fee' => $paymentFee,
                'products_total' => 0,
                'total_price' => 0,
                'status' => 'new',
            ]);

            foreach ($data['items'] as $item) {
                $product = $products->get($item['id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $product->price * $quantity;
                $productsTotal += $subtotal;

                $order->items()->create([
                    'tuning_product_id' => $product->id,
                    'product_name' => $product->name,
                    'tuning_company' => $product->tuning_company,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update([
                'products_total' => $productsTotal,
                'total_price' => $productsTotal + $paymentFee,
            ]);

            return $order->load('items');
        });

        return new OrderResource($order);
    }
}
