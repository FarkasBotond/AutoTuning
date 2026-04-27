<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\TuningProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return OrderResource::collection(
            $request->user()
                ->orders()
                ->with('items')
                ->latest()
                ->get()
        );
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $data['email'] = $user->email;
        }

        $order = DB::transaction(function () use ($data, $user) {
            $productIds = collect($data['items'])->pluck('id')->all();
            $products = TuningProduct::whereIn('id', $productIds)->get()->keyBy('id');
            $productsTotal = 0;
            $paymentFee = $data['payment_method'] === 'cash_on_delivery' ? 1000 : 0;
            $isHomeDelivery = $data['delivery_method'] === 'home_delivery';

            $order = Order::create([
                'user_id' => $user?->id,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'delivery_method' => $data['delivery_method'],
                'country' => $data['country'],
                'city' => $isHomeDelivery ? $data['city'] : 'Fővárosi üzleti átvétel',
                'postal_code' => $isHomeDelivery ? $data['postal_code'] : 'Nincs megadva',
                'street_name' => $isHomeDelivery ? $data['street_name'] : 'Partnerüzlet',
                'house_number' => $isHomeDelivery ? $data['house_number'] : 'Nincs megadva',
                'note' => $data['note'] ?? '',
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
