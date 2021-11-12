<?php

namespace App\Payment\PagSeguro;

class Boleto
{
    private $items;
    private $user;
    private $reference;
    private $senderHash;

    public function __construct($items, $user, $reference, $senderHash)
    {
        $this->items = $items;
        $this->user = $user;
        $this->reference = $reference;
        $this->senderHash = $senderHash;
    }

    public function doPayment()
    {
        $boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

        $boleto->setMode('DEFAULT');
        $boleto->setReceiverEmail(env('PAGSEGURO_EMAIL'));
        $boleto->setCurrency("BRL");

        foreach ($this->items as $item) {
            $boleto->addItems()->withParameters(
                $this->reference,
                $item['name'],
                $item['number'],
                $item['price'],
            );
        }

        $boleto->setReference(base64_encode($this->reference));
        //$boleto->setExtraAmount(11.5);

        $user = $this->user;
        $email = (env('PAGSEGURO_ENV') == 'sandbox') ? 'test@sandbox.pagseguro.com.br' : $user->email;

        $boleto->setSender()->setName($user->name);
        $boleto->setSender()->setEmail($email);

        $boleto->setSender()->setPhone()->withParameters(
            63,
            984015090
        );

        $boleto->setSender()->setDocument()->withParameters(
            'CPF',
            '16032563006'
        );

        $boleto->setSender()->setHash($this->senderHash);

        $boleto->setSender()->setIp('127.0.0.0');

        // Set shipping information for this payment request
        $boleto->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        $result = $boleto->register(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );

        return $result;
    }
}
