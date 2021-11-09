@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados para pagamento</h2>
                    <hr>
                </div>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Número do cartão <span class="brand"></span></label>
                        <input type="text" name="card_number" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Mês de expiração</label>
                        <input type="text" name="card_month" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Ano de expiração</label>
                        <input type="text" name="card_year" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Código de segurança</label>
                        <input type="text" name="card_cvv" class="form-control">
                    </div>

                    <div class="col-md-7 form-group installments">
                        
                    </div>
                </div>

                <button class="btn btn-success">Efetuar pagamento</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <script>
        const sessionId = '{{ session()->get('pagseguro_session_code') }}';
        PagSeguroDirectPayment.setSessionId(sessionId);
    </script>
    <script>
        let cardNumber = document.querySelector('input[name=card_number]');
        let spanBrand = document.querySelector('span.brand');
        cardNumber.addEventListener('keyup', function () {
            if (cardNumber.value.length >= 6) {
                PagSeguroDirectPayment.getBrand({
                    cardBin: cardNumber.value.substr(0, 6),
                    success: function(res) {
                        spanBrand.innerHTML = '<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/' + res.brand.name + '.png">';

                        getInstallments(40, res.brand.name);
                    },
                    error: function(err) {
                        console.log('Error', err);
                    },
                    complete: function(res) {
                        //console.log('Complete', res);
                    }
                });
            }
        });

        function getInstallments(amount, brand) {
            PagSeguroDirectPayment.getInstallments({
                amount: amount,
                brand: brand,
                maxInstallmentNoInterest: 0,
                success: function(res) {
                    //console.log(res);
                    let selectInstallments = drawSelectInstallments(res.installments[brand]);
                    document.querySelector('div.installments').innerHTML = selectInstallments;
                },
                error: function(err) {
                    console.log(err);
                },
                complete: function(res) {

                },
            });
        }

        function drawSelectInstallments(installments) {
		    let select = '<label>Opções de Parcelamento:</label>';
		    select += '<select class="form-control">';

		    for (let l of installments) {
		        select += `<option value="${l.quantity}|${l.installmentAmount}">${l.quantity}x de ${l.installmentAmount} = ${l.totalAmount}</option>`;
		    }

		    select += '</select>';

		    return select;
	    }
    </script>
@endsection
