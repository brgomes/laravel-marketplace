@props([
    'url'
])

<div class="mt-4">
    <h4>Calcule o frete</h4>
    <form action="" class="form-inline formShipping">
        <input placeholder="99999-999" type="text" class="zipcode form-control col-md-6 mr-3">
        <button class="btn btn-outline-success buttonShipping">Calcular</button>
    </form>

    <div class="shipping-result"></div>

    @push('component-scripts')
        <script>
            let formShipping = document.querySelector('form.formShipping');

            formShipping.addEventListener('submit', e => {
                e.preventDefault();

                let shippingResult = document.querySelector('div.shipping-result');
                shippingResult.innerText = '';

                let url = '{{ $url }}';
                let body = {
                    'zipcode': document.querySelector('input.zipcode').value,
                    '_token': '{{ csrf_token() }}',
                    'productId': document.querySelector('input[name="product[slug]"]').value
                };

                // mdn.io/fetch
                fetch(url, {
                    'method': 'POST',
                    'headers': {
                        'Content-Type': 'application/json'
                    },
                    'body': JSON.stringify(body)
                })
                .then(response => response.json())
                .then(responseBody => {
                    for (let shipping of responseBody.data.shipping) {
                        let elDiv = document.createElement('div');

                        elDiv.className = 'mt-3';
                        elDiv.innerHTML = `<div class="mb-3 d-flex justify-content-between">
                            <span>${shipping.name}:</span>
                            <span>${shipping.price}</span>
                        </div>`;
                        shippingResult.appendChild(elDiv);
                    }
                });
            });
        </script>
    @endpush
</div>
