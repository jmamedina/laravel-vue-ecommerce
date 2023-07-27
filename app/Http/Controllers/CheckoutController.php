<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product)
        {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * 100;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                      'name' => $product->title,
                      'images' => [$product->image],
                    ],
                    'unit_amount_decimal' => $product->price * 100,
                  ],
                  'quantity' => $cartItems[$product->id]['quantity'],
                ];
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price
                ];
        }
        // dd($lineItems);

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true)."?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
          ]);

          //Create order
          $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
          ];

          $order = Order::create($orderData);

         // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

          //Create payment
          $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $checkout_session->id,
          ];

          Payment::create($paymentData);
          CartItem::where(['user_id' => $user->id])->delete();

        //   dd($checkout_session);
          return redirect($checkout_session->url);
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session = $stripe->checkout->sessions->retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }
            $customer = $stripe->customers->retrieve($session->customer);
            return view('checkout.success', compact('customer'));
            
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
        
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => ""]);
    }


    public function checkoutOrder(Order $order, Request $request)
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }


       $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true)."?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
          ]);

        $order->payment->session_id = $checkout_session->id;
        $order->payment->save();


        return redirect($checkout_session->url);
    }

    public function webhook()
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        $endpoint_secret = getenv('ENDPOINT_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;
        
        try {
          $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
          );
        } catch(\UnexpectedValueException $e) {
          // Invalid payload
          return response('', 401);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
          // Invalid signature
          return response('', 402);
        }
        
        // Handle the event
        switch ($event->type) {
          case 'checkout.session.completed':
            $paymentIntent = $event->data->object;
            $sessionId = $paymentIntent['id'];

            $payment = Payment::query()
                ->where(['session_id' => $sessionId, 'status' => PaymentStatus::Pending])
                ->first();
            if($payment){
                $this->updateOrderAndSession($payment);
            }
          default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        return response('', 200);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        $order = $payment->order;
        $order->status = OrderStatus::Paid->value;
        $order->update();
        $adminUsers = User::where('is_admin', 1)->get();

        // foreach([...$adminUsers, $order->user] as $user) {
        //     Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        // }
    }
}
