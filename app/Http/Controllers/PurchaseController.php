<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PurchaseController extends Controller
{
    private $provider;
    function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $token = $this->provider->getAccessToken();
        $this->provider->setAccessToken($token);
    }
    public function sendOrderConfirmationEmail($order, $user)
    {
        Mail::to($user->email)->send(new OrderMail($order, $user));
    }
    public function createPayment(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $books = User::find($data['userId'])->booksInCart;
        $total = 0;
        foreach ($books as $book) {
            $total += $book->price * $book->pivot->number_of_copies;
        }
        $order = $this->provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $total,
                    ],
                    'description' => 'Order Description'
                ]
            ]
        ]);
        return response()->json($order);
    }

    public function executePayment(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->provider->capturePaymentOrder($data['orderId']);
        if ($result['status'] === 'COMPLETED') {
            $user = User::find($data['userId']);
            $books = $user->booksInCart;
            $this->sendOrderConfirmationEmail($books, auth()->user());
            foreach ($books as $book) {
                $bookPrice = $book->price;
                $purchaseTime = Carbon::now();
                $user->booksInCart()->updateExistingPivot($book->id, ['bought' => TRUE, 'price' => $bookPrice, 'created_at' => $purchaseTime]);
                $book->save();
            }
        }
        // ✅ Clear books from the cart (Detach from pivot table)
        $user->booksInCart()->detach();
        return response()->json($result);
    }
    public function creditCheckout(Request $request)
    {
        $intent = auth()->user()->createSetupIntent();
        $userId = auth()->user()->id;
        $books = User::find($userId)->booksInCart;
        $total = 0;
        foreach ($books as $book) {
            $total += $book->price * $book->pivot->number_of_copies;
        }
        return view('credit.checkout', compact('total', 'intent'));
    }
    public function purchase(Request $request)
    {
        $user = $request->user();
        $paymentMethod = $request->input('payment_method');
        $userId = auth()->user()->id;
        $books = User::find($userId)->booksInCart;
        $total = 0;
        foreach ($books as $book) {
            $total += $book->price * $book->pivot->number_of_copies;
        }
        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($total * 100, $paymentMethod); //Stripe use cenit because of this we mulply 100
        } catch (\Exception $exception) {
            return back()->with('حصل خطأ اثناء شراء المنتج الرجاء التأكد من معلومات البطاقة', $exception->getMessage());
        }
        $this->sendOrderConfirmationEmail($books, auth()->user());

        foreach ($books as $book) {
            $bookPrice = $book->price;
            $purchaseTime = Carbon::now();
            $user->booksInCart()->updateExistingPivot($book->id, ['bought' => TRUE, 'price' => $bookPrice, 'created_at' => $purchaseTime]);
            $book->save();
        }
        return redirect('/cart')->with('message', 'تم شراء المنتج بنجاح');
    }
    public function myProduct()
    {
        $userId = auth()->user()->id;
        $myBooks = User::find($userId)->purchedProduct;
        return view('books.myProduct', compact('myBooks'));
    }
}
