
<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: Login.php");
    exit;
}
$nombre     = $_SESSION['nombre'] ?? 'Usuario';
$id_usuario = $_SESSION['id_usuario'];

// Trae productos del carrito
$sql = mysqli_query($conexion, "
    SELECT c.id_carrito, c.cantidad_productos, c.precio, c.tamaño,
        p.nombre, p.imagen
    FROM carrito c
    JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = $id_usuario
");
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="carritt.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<!--  NAVBAR -->
<nav class="barra">
    <div class="logo">Entre Granos</div>

    <div class="menu">
        <!-- Saludo dinámico (lo rellenará saludo.js) -->
        <span id="saludo-usuario"
            class="saludo-navbar"
            data-nombre="<?= htmlspecialchars($nombre) ?>">
        </span>
        <a href="#" title="Contacto"><i class="fas fa-phone"></i></a>
        <a href="Login.html" title="usuario"><i class="fas fa-user"></i></a>
        <a href="cerrar_sesion.php" class="info-button">Cerrar sesión</a>
    </div>
</nav>

<!-- BOTÓN REGRESAR  -->
<div class="regresar">
    <a href="productos_usuario.php" class="btn-regresar">
        <i class="fas fa-arrow-left"></i> Regresar a productos
    </a>
</div>

<!-- CONTENIDO DEL CARRITO  -->
<div class="carrito-container">
<?php if (mysqli_num_rows($sql) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($sql)):
        $subtotal = $row['precio'] * $row['cantidad_productos'];
        $total   += $subtotal;
    ?>
    <div class="producto">
        <img src="<?= $row['imagen'] ?>" alt="Producto">
        <h3><?= $row['nombre'] ?></h3>

        <p><strong>Tamaño:</strong> <?= $row['tamaño'] ?></p>
        <p><strong>Precio:</strong> $<?= number_format($row['precio'], 2) ?></p>

        <label><strong>Cantidad:</strong></label>
        <input type="number"
            class="cantidad-input"
            data-precio="<?= $row['precio'] ?>"
            data-idcarrito="<?= $row['id_carrito'] ?>"
            value="<?= $row['cantidad_productos'] ?>"
            min="1"
            style="width:60px;text-align:center;">

        <p><strong>Subtotal:</strong> $
            <span class="subtotal"><?= number_format($subtotal, 2) ?></span>
        </p>

        <form action="eliminar_carrito.php" method="POST">
            <input type="hidden" name="id_carrito" value="<?= $row['id_carrito'] ?>">
            <button type="submit" class="btn_eliminar">Eliminar</button>
        </form>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No hay productos en tu carrito aún.</p>
<?php endif; ?>
</div>

<?php if ($total > 0): ?>
<?php
        /*  Guarda el total para el paso de pago */
    $_SESSION['total'] = $total;
?>

    <div class="total">
        <h2>Total a pagar: $<?= number_format($total, 2) ?></h2>
    </div>

    <div class="acciones-carrito">
        <form action="mostrar_ticket.php" method="POST" style="display:inline;">
            <button type="submit" class="btn-confirmar">Confirmar compra</button>
        </form>
        <form action="vaciar_carrito.php" method="POST" style="display:inline;">
            <button type="submit" class="btn-vaciar">Vaciar carrito</button>
        </form>
        <form action="geneerar_ticket.php" method="POST" target="_blank" style="display:inline;">
            <button type="submit">Imprimir Ticket</button>
        </form>
    </div>
<?php endif; ?>

<!--  SCRIPTS -->
<script src="saludo.js"></script> <!-- ⬅Saludo dinámico -->
<script>
/* Actualizar cantidades sin recargar */
document.querySelectorAll('.cantidad-input').forEach(input => {
    input.addEventListener('change', () => {
        const idCarrito = input.dataset.idcarrito;
        const nuevaCant = input.value;
        const precio    = parseFloat(input.dataset.precio);

        fetch('actualizar_carrito.php', {
            method : 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body   : `id_carrito=${idCarrito}&nueva_cantidad=${nuevaCant}`
        })
        .then(r => r.text())
        .then(resp => {
            if (resp.trim() === "ok") {
                /* Actualiza subtotal de la tarjeta */
                const sub = precio * nuevaCant;
                input.closest('.producto')
                    .querySelector('.subtotal').textContent = sub.toFixed(2);

                /* Recalcula total general */
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(s=>{
                    total += parseFloat(s.textContent);
                });
                document.querySelector('.total h2')
                        .textContent = 'Total a pagar: $' + total.toFixed(2);
            } else {
                alert("Error al actualizar la cantidad");
            }
        });
    });
});
</script>
</body>
</html>
