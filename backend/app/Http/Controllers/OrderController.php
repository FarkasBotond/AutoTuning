<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\TuningProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        $order = DB::transaction(function () use ($data, $request) {
            $productIds = collect($data['items'])->pluck('id')->all();

            $products = TuningProduct::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            $productsTotal = 0;
            $paymentFee = $data['payment_method'] === 'cash_on_delivery' ? 1000 : 0;

            $orderData = [
                'user_id' => optional($request->user())->id,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'delivery_method' => $data['delivery_method'],
                'payment_method' => $data['payment_method'],
                'payment_fee' => $paymentFee,
                'products_total' => 0,
                'total_price' => 0,
                'status' => 'new',
            ];

            if (Schema::hasColumn('orders', 'first_name')) {
                $orderData['first_name'] = $data['first_name'];
            }

            if (Schema::hasColumn('orders', 'last_name')) {
                $orderData['last_name'] = $data['last_name'];
            }

            if (Schema::hasColumn('orders', 'full_name')) {
                $orderData['full_name'] = trim($data['last_name'] . ' ' . $data['first_name']);
            }

            if (Schema::hasColumn('orders', 'shipping_country')) {
                $orderData['shipping_country'] = $data['shipping_country'] ?? null;
            }

            if (Schema::hasColumn('orders', 'shipping_city')) {
                $orderData['shipping_city'] = $data['shipping_city'] ?? null;
            }

            if (Schema::hasColumn('orders', 'shipping_postal_code')) {
                $orderData['shipping_postal_code'] = $data['shipping_postal_code'] ?? null;
            }

            if (Schema::hasColumn('orders', 'shipping_street_name')) {
                $orderData['shipping_street_name'] = $data['shipping_street_name'] ?? null;
            }

            if (Schema::hasColumn('orders', 'shipping_house_number')) {
                $orderData['shipping_house_number'] = $data['shipping_house_number'] ?? null;
            }

            if (Schema::hasColumn('orders', 'billing_country')) {
                $orderData['billing_country'] = $data['billing_country'];
            }

            if (Schema::hasColumn('orders', 'billing_city')) {
                $orderData['billing_city'] = $data['billing_city'];
            }

            if (Schema::hasColumn('orders', 'billing_postal_code')) {
                $orderData['billing_postal_code'] = $data['billing_postal_code'];
            }

            if (Schema::hasColumn('orders', 'billing_street_name')) {
                $orderData['billing_street_name'] = $data['billing_street_name'];
            }

            if (Schema::hasColumn('orders', 'billing_house_number')) {
                $orderData['billing_house_number'] = $data['billing_house_number'];
            }

            if (Schema::hasColumn('orders', 'country')) {
                $orderData['country'] = $data['billing_country'] ?? $data['shipping_country'] ?? 'Magyarország';
            }

            if (Schema::hasColumn('orders', 'city')) {
                $orderData['city'] = $data['billing_city'] ?? $data['shipping_city'] ?? null;
            }

            if (Schema::hasColumn('orders', 'postal_code')) {
                $orderData['postal_code'] = $data['billing_postal_code'] ?? $data['shipping_postal_code'] ?? null;
            }

            if (Schema::hasColumn('orders', 'street_name')) {
                $orderData['street_name'] = $data['billing_street_name'] ?? $data['shipping_street_name'] ?? null;
            }

            if (Schema::hasColumn('orders', 'house_number')) {
                $orderData['house_number'] = $data['billing_house_number'] ?? $data['shipping_house_number'] ?? null;
            }

            if (Schema::hasColumn('orders', 'note')) {
                $orderData['note'] = $data['note'] ?? null;
            }

            $order = Order::create($orderData);

            foreach ($data['items'] as $item) {
                $product = $products->get($item['id']);

                if (!$product) {
                    continue;
                }

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

    public function index(Request $request)
    {
    $orders = Order::query()
        ->with('items')
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

    return OrderResource::collection($orders);
    }
}