<?php
// cambiar_cantidad_carrito.php
session_start();
include 'conexion.php';

if (isset($_POST['referencia']) && isset($_POST['cambio']) && isset($_SESSION['usuario_id'])) {
    $referencia = $_POST['referencia'];
    $cambio = intval($_POST['cambio']);
    $usuarioId = $_SESSION['usuario_id'];

    error_log("cambiar_cantidad_carrito.php: referencia=$referencia, cambio=$cambio, usuarioId=$usuarioId");

    // Obtener la cantidad actual del producto
    $sql_select = "SELECT cantidad FROM carrito WHERE usuario_id = ? AND referencia = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("is", $usuarioId, $referencia);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($row_select = $result_select->fetch_assoc()) {
        $cantidad_actual = $row_select['cantidad'];
        $nueva_cantidad = $cantidad_actual + $cambio;

        error_log("cambiar_cantidad_carrito.php: cantidad_actual=$cantidad_actual, nueva_cantidad=$nueva_cantidad");

        if ($nueva_cantidad < 1) {
            // Eliminar el producto si la cantidad es menor que 1
            $sql_delete = "DELETE FROM carrito WHERE usuario_id = ? AND referencia = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("is", $usuarioId, $referencia);
            if ($stmt_delete->execute()) {
                echo json_encode(['success' => true, 'mensaje' => 'Producto eliminado']);
                error_log("cambiar_cantidad_carrito.php: Producto eliminado.");
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto: ' . $stmt_delete->error]);
                error_log("cambiar_cantidad_carrito.php: Error al eliminar: " . $stmt_delete->error);
            }
            $stmt_delete->close();
        } else {
            // Actualizar la cantidad
            $sql_update = "UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND referencia = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iis", $nueva_cantidad, $usuarioId, $referencia);
            if ($stmt_update->execute()) {
                echo json_encode(['success' => true, 'mensaje' => 'Cantidad actualizada']);
                error_log("cambiar_cantidad_carrito.php: Cantidad actualizada a $nueva_cantidad.");
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la cantidad: ' . $stmt_update->error]);
                error_log("cambiar_cantidad_carrito.php: Error al actualizar: " . $stmt_update->error);
            }
            $stmt_update->close();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Producto no encontrado en el carrito']);
        error_log("cambiar_cantidad_carrito.php: Producto no encontrado.");
    }
    $stmt_select->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Parámetros incorrectos']);
    error_log("cambiar_cantidad_carrito.php: Parámetros incorrectos.");
}
$conn->close();
?>