@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 msg">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados para pagamento</h2>
                    <hr>
                </div>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Nome no cartão</label>
                        <input type="text" name="card_name" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Número do cartão <span class="brand"></span></label>
                        <input type="text" name="card_number" class="form-control">
                        <input type="hidden" name="card_brand">
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

                <button class="btn btn-success processCheckout">Efetuar pagamento</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
    <script src="{{ asset('assets/js/jquery.ajax.js') }}"></script>
    <script>
        const sessionId = '{{ session()->get('pagseguro_session_code') }}';
        const urlThanks = '{{ route('checkout.thanks') }}';
        const urlProccess = '{{ route('checkout.proccess') }}';
        const amountTransaction = '{{ $total }}';
        const csrf = '{{ csrf_token() }}';

        PagSeguroDirectPayment.setSessionId(sessionId);
    </script>
    <script src="{{ asset('js/pagseguro_functions.js') }}"></script>
    <script src="{{ asset('js/pagseguro_events.js') }}"></script>
@endsection
