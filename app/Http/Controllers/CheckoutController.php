<?php

namespace App\Http\Controllers;

use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification as PagSeguroNotification;
use App\Store;
use App\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            if (!session()->has('cart')) {
                return redirect()->route('home');
            }

            //session()->forget('pagseguro_session_code');
            $this->makePagSeguroSession();

            $cartItens = array_map(function ($line) {
                return $line['number'] * $line['price'];
            }, session()->get('cart'));

            $total = array_sum($cartItens);

            return view('checkout', compact('total'));
        } catch (\Exception $e) {
            session()->forget('pagseguro_session_code');
            redirect()->route('checkout.index');
        }
    }

    public function proccess(Request $request)
    {
        try {
            $cartItems = session()->get('cart');
            $user = auth()->user();
            $dataPost = $request->all();
            $reference = uniqid();
            $stores = array_unique(array_column($cartItems, 'store_id'));

            $creditCardPayment = new CreditCard($cartItems, $user, $dataPost, $reference);

            $result = $creditCardPayment->doPayment();

            $userOrder = [
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'items' => serialize($cartItems),
            ];

            $userOrder = $user->orders()->create($userOrder);
            $userOrder->stores()->sync($stores);

            // Notifica loja de novo pedido
            $store = (new Store())->notifyStoreOwners($stores);

            session()->forget('cart');
            session()->forget('pagseguro_session_code');

            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Pedido criado com sucesso.',
                    'order' => $reference,
                ]
            ]);
        } catch (\Exception $e) {
            $message = env('APP_DEBUG') ? simplexml_load_string($e->getMessage()) : 'Erro ao processar pedido.';

            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message,
                ]
            ], 401);
        }
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function notification()
    {
        try {
            $notification = (new PagSeguroNotification())->getTransaction();

            $reference = base64_decode($notification->getReference());
            $userOrder = UserOrder::whereReference($reference);
            $userOrder->update([
                'pagseguro_status' => $notification->getStatus(),
            ]);

            if ($notification->getStatus() == 3) {
                // Pode liberar o pedido do usuário ou atualizar o status para "Em separação"
                // Pode notificar o usuário que o pedido foi pago
                // Pode notificar a loja da confirmação do pedido
            }

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
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
