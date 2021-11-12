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

let submitButton = document.querySelector('button.processCheckout');

submitButton.addEventListener('click', function(event) {
    event.preventDefault();
    let buttonTarget = event.target;

    buttonTarget.disabled = true;
    buttonTarget.innerHTML = 'Carregando...';

    PagSeguroDirectPayment.createCardToken({
        cardNumber: document.querySelector('input[name=card_number]').value,
        //cardName: document.querySelector('input[name=card_name]').value,
        brand: document.querySelector('input[name=card_brand]').value,
        cvv: document.querySelector('input[name=card_cvv]').value,
        expirationMonth: document.querySelector('input[name=card_month]').value,
        expirationYear: document.querySelector('input[name=card_year]').value,
        success: function(res) {
            //console.log('card token = ' + res.card.token);
            processPayment(res.card.token, buttonTarget);
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
});
