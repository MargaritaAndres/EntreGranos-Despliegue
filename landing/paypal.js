/* paypal.js  – botón PayPal reutilizable */
function cargarBotonPayPal(total) {
    if (typeof paypal === "undefined") {
        console.error("PayPal SDK no cargado");
        return;
    }

    paypal.Buttons({
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: { value: total }
                }]
            });
        },
        onApprove: (data, actions) => {
            return actions.order.capture().then(details => {
                alert('Gracias ' + details.payer.name.given_name +
                    ', tu pago fue exitoso.');
                /* aquí puedes redirigir o hacer fetch a tu backend */
                window.location.href = 'confirmacion_pago.php';
            });
        }
    }).render('#paypal-button-container');
}

