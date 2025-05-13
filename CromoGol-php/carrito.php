<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - CromoGol</title>
    <link rel="stylesheet" href="CromoGol-css/CromoGol.css">
    <link rel="stylesheet" href="CromoGol-css/carrito.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><h1>CromoGol</h1></a>
        </div>
        <nav class="main-navigation">
            <ul class="main-menu open" id="main-menu">
                <li><a href="cartas_base.php">Base</a></li>
                <li><a href="cartas_special.php">Special</a></li>
                <li><a href="cartas_rookie.php">Rookie</a></li>
                <li><a href="cartas_rare.php">Rare</a></li>
                <li><a href="cartas_autographed.php">Autographed</a></li>
                <?php
                session_start();
                if (isset($_SESSION['usuario_id'])) {
                    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
                } else {
                    echo '<li><a href="login.php">Iniciar Sesión</a></li>';
                    echo '<li><a href="registro.php">Registrarse</a></li>';
                }
                ?>
                <li class="carrito">
                    <a href="carrito.php" title="Ver carrito de compras">
                        <img src="CromoGol-imagenes/carrito.avif" alt="Carrito de compras">
                        <?php
                        $totalItems = 0;
                        if (isset($_SESSION['usuario_id'])) {
                            include 'conexion.php';
                            $usuarioId = $_SESSION['usuario_id'];
                            $sql_contador = "SELECT SUM(cantidad) AS total_items FROM carrito WHERE usuario_id = ?";
                            $stmt_contador = $conn->prepare($sql_contador);
                            $stmt_contador->bind_param("i", $usuarioId);
                            $stmt_contador->execute();
                            $result_contador = $stmt_contador->get_result();
                            if ($row_contador = $result_contador->fetch_assoc()) {
                                $totalItems = $row_contador['total_items'] ?: 0;
                            }
                            $stmt_contador->close();
                            $conn->close();
                        }
                        echo '<span id="contador-carrito">' . $totalItems . '</span>';
                        ?>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="carrito-page">
        <h2>Tu Carrito de Compras</h2>
        <ul id="lista-carrito">
            </ul>
        <p>Total: <span id="total-carrito">0.00</span> €</p>
        </main>

    <footer>
        <p>&copy; 2025 CromoGol tu tienda de cartas </p>
    </footer>

    <script src="CromoGol-js/carrito.js"></script>
</body>
</html>