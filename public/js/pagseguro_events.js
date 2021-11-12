let cardNumber = document.querySelector('input[name=card_number]');
let spanBrand = document.querySelector('span.brand');

cardNumber.addEventListener('keyup', function () {
    if (cardNumber.value.length >= 6) {
        PagSeguroDirectPayment.getBrand({
            cardBin: cardNumber.value.substr(0, 6),
            success: function(res) {
                spanBrand.innerHTML = '<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/' + res.brand.name + '.png">';
                document.querySelector('input[name=card_brand]').value = res.brand.name;

                getInstallments(amountTransaction, res.brand.name);
            },
            error: function(err) {
                console.log('Credit card error', err);
            },
            complete: function(res) {
                //console.log('Complete', res);
            }
        });
    }
});

let submitButtons = document.querySelectorAll('button.processCheckout');

submitButtons.forEach(function (el, k) {
    el.addEventListener('click', function(event) {
        event.preventDefault();

        let buttonTarget = event.target;
        let paymentType = buttonTarget.dataset.paymentType;

        buttonTarget.disabled = true;
        buttonTarget.innerHTML = 'Carregando...';

        if (paymentType == 'CREDITCARD') {
            PagSeguroDirectPayment.createCardToken({
                cardNumber: document.querySelector('input[name=card_number]').value,
                //cardName: document.querySelector('input[name=card_name]').value,
                brand: document.querySelector('input[name=card_brand]').value,
                cvv: document.querySelector('input[name=card_cvv]').value,
                expirationMonth: document.querySelector('input[name=card_month]').value,
                expirationYear: document.querySelector('input[name=card_year]').value,
                success: function(res) {
                    //console.log('card token = ' + res.card.token);
                    processPayment(res.card.token, buttonTarget, paymentType);
                },
                error: function(err) {
                    //console.log(err.errors);

                    buttonTarget.disabled = false;
                    buttonTarget.innerHTML = 'Efetuar pagamento';

                    for (let i in err.errors) {
                        document.querySelector('div.msg').innerHTML = showErrorMessages(errorsMapPagSeguroJS(i));
                    }
                }
            });
        }

        if (paymentType == 'BOLETO') {
            processPayment(null, buttonTarget, paymentType);
        }
    });    
});
