    <?php
    session_start();
    include 'conexion.php';

    if (isset($_POST['referencia']) && isset($_SESSION['usuario_id'])) {
        $referencia = $_POST['referencia'];
        $usuarioId = $_SESSION['usuario_id'];
        $cantidad = 1; // Cantidad por defecto

        // Verificar si el producto existe en la tabla productos (opcional pero recomendado)
        $sql_producto = "SELECT * FROM productos WHERE referencia = ?";
        $stmt_producto = $conn->prepare($sql_producto);
        $stmt_producto->bind_param("s", $referencia);
        $stmt_producto->execute();
        $result_producto = $stmt_producto->get_result();

        if ($result_producto->num_rows == 0) {
            echo json_encode(['success' => false, 'error' => 'El producto no existe']);
            $conn->close();
            exit;
        }

        // Verificar si el producto ya está en el carrito del usuario
        $sql_carrito = "SELECT cantidad FROM carrito WHERE usuario_id = ? AND referencia = ?";
        $stmt_carrito = $conn->prepare($sql_carrito);
        $stmt_carrito->bind_param("is", $usuarioId, $referencia);
        $stmt_carrito->execute();
        $result_carrito = $stmt_carrito->get_result();

        if ($result_carrito->num_rows > 0) {
            // Actualizar la cantidad si el producto ya está en el carrito
            $row_carrito = $result_carrito->fetch_assoc();
            $cantidad_actual = $row_carrito['cantidad'];
            $nueva_cantidad = $cantidad_actual + $cantidad;

            $sql_update = "UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND referencia = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iis", $nueva_cantidad, $usuarioId, $referencia);
            if ($stmt_update->execute()) {
                echo json_encode(['success' => true, 'mensaje' => 'Cantidad actualizada']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la cantidad: ' . $stmt_update->error]);
            }
            $stmt_update->close();
        } else {
            // Insertar el producto en el carrito
            $sql_insert = "INSERT INTO carrito (usuario_id, referencia, cantidad) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("isi", $usuarioId, $referencia, $cantidad);
            if ($stmt_insert->execute()) {
                echo json_encode(['success' => true, 'mensaje' => 'Producto añadido al carrito']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al añadir al carrito: ' . $stmt_insert->error]);
            }
            $stmt_insert->close();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Parámetros incorrectos']);
    }
    $conn->close();
    ?>
    