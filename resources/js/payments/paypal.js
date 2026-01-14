import '../bootstrap.js'

const selectors = {
    form: '#checkout-form'
}

function getFields() {
    // return $('#checkout-form').serializeArray().reduce((obj, item) => {
    //     obj[item.name] = item.value
    //     return obj
    // }, {})
    const fields = $('#checkout-form').serializeArray().reduce((obj, item) => {
        obj[item.name] = item.value;
        return obj;
    }, {});

    fields.name = (fields.first_name || '') + ' ' + (fields.last_name || '');
    return fields;
}

function isEmptyFields() {
    let result = false
    const fields = getFields()

    // Object.keys(fields).map((key) => {
    //     if (fields[key].length < 1){
    //         // $(`${selectors.form} input[name="${key}"]`).addClass('is-invalid')
    //         $(`${selectors.form} [name="${key}"]`).addClass('is-invalid')
    //
    //         result = true
    //     }
    // })
    Object.keys(fields).forEach((key) => {
        if (!fields[key] || fields[key].length < 1) {
            $(`${selectors.form} [name="${key}"]`).addClass('is-invalid');
            result = true;
        }
    });
    return result
}
paypal.Buttons({
    style:{
        color: 'blue',
        shape: 'pill',
        label: 'pay',
        height: 40
    },

    onInit: function (data, actions) {

        console.log("PayPal INIT");
        actions.disable();

        $(selectors.form).on('input change', 'input, textarea, select', function () {

            console.log("Form change detected");

            if (!isEmptyFields()) {
                console.log("Fields validated → enabling button");
                actions.enable();
                $(selectors.form).find('.is-invalid').removeClass('is-invalid');
            } else {
                console.log("Fields invalid → disabling button");
                actions.disable();
            }
        });
    },

    onClick: function (data, actions) {

        console.log("PayPal onclick fired!");

        if (isEmptyFields()) {
            iziToast.warning({
                title: 'Please fill an empty field',
                position: 'topCenter'
            });

            return actions.reject();
        }

        $(selectors.form).find('.is-invalid').removeClass('is-invalid');
    },



    // Call your server to set up the transaction
    createOrder: function (data, actions) {
        return axios.post('/ajax/paypal/order', getFields())
            .then(res => {
                console.log('createOrder response:', res.data);

                if (!res.data.id) {
                    throw new Error('PayPal order id not returned');
                }

                return res.data.id;
            })
            .catch(err => {
                console.error('createOrder error:', err);
                throw err;
            });
    },



    // Call your server to finalize the transaction
    onApprove: function(data, actions) {
        return axios.post('/ajax/paypal/order/' + data.orderID + '/capture/', {})
            .then(function(res) {
                const orderData = res.data

                iziToast.success({
                    title: 'Order was created',
                    position: 'topCenter'
                })

                console.log('orderData', orderData)

            // return res.json();
        }).catch(function(orderData) {
            // Three cases to handle:
            //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
            //   (2) Other non-recoverable errors -> Show a failure message
            //   (3) Successful transaction -> Show confirmation or thank you

            // This example reads a v2/checkout/orders capture response, propagated from the server
            // You could use a different API or structure for your 'orderData'
            var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

            if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                return actions.restart(); // Recoverable state, per:
                // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
            }

            if (errorDetail) {
                var msg = 'Sorry, your transaction could not be processed.';
                if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                return alert(msg); // Show a failure message (try to avoid alerts in production environments)
            }

            // Successful capture! For demo purposes:
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
            var transaction = orderData.purchase_units[0].payments.captures[0];
            alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');

            // Replace the above to show a success message within this page, e.g.
            // const element = document.getElementById('paypal-button-container');
            // element.innerHTML = '';
            // element.innerHTML = '<h3>Thank you for your payment!</h3>';
            // Or go to another URL:  actions.redirect('thank_you.html');
        });
    }

}).render('#paypal-button-container');

window.getFields = getFields
window.isEmptyFields = isEmptyFields
$(selectors.form).trigger('input');



