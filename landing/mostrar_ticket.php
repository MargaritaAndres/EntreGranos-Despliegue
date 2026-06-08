<?php
session_start();
if (!isset($_SESSION['archivo_pdf'])) {
    die("No se generó ningún ticket.");
}
$rutaPDF = $_SESSION['archivo_pdf'];

// Aseguramos un total válido
$total = isset($_SESSION['total']) && $_SESSION['total'] > 0 ? $_SESSION['total'] : 150.00;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista previa del ticket</title>
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: rgb(48, 11, 97);
        }

        button {
            padding: 10px 20px;
            background-color: rgb(48, 12, 116);
            color: white;
            border: none;
            border-radius: 6px;
            margin: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-regresar {
            background-color: rgb(12, 65, 45);
        }

        button:hover {
            background-color: rgb(190, 150, 63);
        }

        iframe {
            border: 1px solid #aaa;
            margin-top: 20px;
        }

        /* Contenedor centrado para PayPal */
        .paypal-container {
            display: flex;
            justify-content: center;
            margin-top: 25px;
            flex-direction: column;
            align-items: center;
        }

        #paypal-button-container {
            width: 100%;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <h2>Tu ticket ha sido generado</h2>
    <p>Puedes verlo aquí antes de enviarlo por correo o pagar:</p>

    <!-- Vista previa del PDF -->
    <iframe src="<?= $rutaPDF ?>" width="50%" height="600px"></iframe>

    <!-- Botones -->
    <br><br>
    <form action="carrito.php" method="get" style="display:inline;">
        <button type="submit" class="btn-regresar">
            <i class="fas fa-arrow-left"></i> Regresar
        </button>
    </form>
    <form action="enviar_ticket.php" method="post" style="display:inline;">
        <button type="submit">Enviar ticket al correo</button>
    </form>
    <form action="productos_usuario.php" method="get" style="display:inline;">
        <button type="submit">Agregar más productos</button>
    </form>

    <!-- Botón de PayPal -->
    <div class="paypal-container">
        <div id="paypal-button-container"></div>
    </div>

    <!-- SDK de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=Ab_GbJsJdCLUzBryT6upRqcWUXpkPmDS79-SuzJWAtAWd_DC2JlfsdEp02r8AvIA-G8H_FjCp0eUomRz&currency=MXN"></script>

    <!-- SDK de PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=Ab_GbJsJdCLUzBryT6upRqcWUXpkPmDS79-SuzJWAtAWd_DC2JlfsdEp02r8AvIA-G8H_FjCp0eUomRz&currency=MXN"></script>

<script>
paypal.Buttons({
    style: {
        layout: 'vertical',
        color: 'gold',
        shape: 'rect',
        label: 'paypal'
    },

    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?= number_format($total, 2, ".", "") ?>'
                }
            }]
        });
    },

    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            //  Mostrar modal primero
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '50%';
            modal.style.left = '50%';
            modal.style.transform = 'translate(-50%, -50%)';
            modal.style.padding = '25px';
            modal.style.backgroundColor = '#fff';
            modal.style.border = '2px solid #333';
            modal.style.borderRadius = '12px';
            modal.style.boxShadow = '0 0 15px rgba(0,0,0,0.3)';
            modal.innerHTML = `
                <h3>¡Pago completado!</h3>
                <p>Gracias por tu compra, ${details.payer.name.given_name}.<br>
                Tu ticket fue enviado a tu correo.</p>
                <button style="margin-top:10px;padding:8px 15px;background:#308;color:#fff;border:none;border-radius:6px;cursor:pointer;" onclick="this.parentNode.remove()">Cerrar</button>
            `;
            document.body.appendChild(modal);

            // Luego: vaciar carrito y enviar ticket
            fetch('vaciar_carrito_ajax.php', { method: 'POST' })
            .then(() => fetch('enviar_ticket.php', { method: 'POST' }))
            .then(() => {
                setTimeout(() => {
                    window.location.href = 'carrito.php';
                }, 700);
            });
        });
    }
}).render('#paypal-button-container');
</script>
