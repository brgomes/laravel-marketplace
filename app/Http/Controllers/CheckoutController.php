<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        //session()->forget('pagseguro_session_code');
        $this->makePagSeguroSession();

        $cartItens = array_map(function ($line) {
            return $line['number'] * $line['price'];
        }, session()->get('cart'));

        $total = array_sum($cartItens);

        return view('checkout', compact('total'));
    }

    public function proccess(Request $request)
    {
        $cartItems = session()->get('cart');
        $user = auth()->user();
        $dataPost = $request->all();
        $reference = 'XPTO';

        $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);

        $result = $creditCardPayment->doPayment();

        $userOrder = [
            'reference' => $reference,
            'pagseguro_code' => $result->getCode(),
            'pagseguro_status' => $result->getStatus(),
            'items' => serialize($cartItems),
            'store_id' => 8,
        ];

        $user->orders()->create($userOrder);

        return response()->json([
            'data' => [
                'status' => true,
                'message' => 'Pedido criado com sucesso.',
            ]
        ]);
    }

    private function makePagSeguroSession()
    {
        if (!session()->has('pagseguro_session_code')) {
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            session()->put('pagseguro_session_code', $sessionCode->getResult());
        }
    }
}
