<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartas Rookie - CromoGol</title>
    <link rel="stylesheet" href="CromoGol-css/CromoGol.css">
    <link rel="stylesheet" href="CromoGol-css/cartas-rookie.css">
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
                <li class="carrito">
                    <a href="carrito.php" title="Ver carrito de compras">
                        <img src="CromoGol-imagenes/carrito.avif" alt="Carrito de compras">
                        <span id="contador-carrito">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="category-page">
        <h1>Cartas Rookie</h1>
        <div id="catalogo-cartas" class="catalogo-cartas">
            </div>
    </main>

    <footer>
        <p>&copy; 2025 CromoGol</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('obtener_cartas.php?tipo=Rookie')
                .then(response => response.json())
                .then(data => {
                    const catalogoCartas = document.getElementById('catalogo-cartas');
                    if (data.length > 0) {
                        data.forEach(carta => {
                            const cartaDiv = document.createElement('div');
                            cartaDiv.classList.add('carta');
                            cartaDiv.innerHTML = `
                                <h3>${carta.nombre}</h3>
                                <p>Referencia: ${carta.referencia}</p>
                                <p>Descripción: ${carta.descripcion}</p>
                                <p>Precio: ${carta.precio} €</p>
                                <p>Liga: ${carta.liga}</p>
                                <p>Equipo: ${carta.equipo}</p>
                                <p>Temporada: ${carta.temporada}</p>
                                <p>Tipo: ${carta.tipo_carta}</p>
                                <p>Posición: ${carta.posicion}</p>
                                <button class="add-to-cart-btn"
                                        data-nombre="${carta.nombre}"
                                        data-precio="${carta.precio}"
                                        data-referencia="${carta.referencia}">
                                    Añadir al carrito
                                </button>
                            `;
                            catalogoCartas.appendChild(cartaDiv);
                        });
                    } else {
                        catalogoCartas.innerHTML = '<p>No hay cartas rookie disponibles.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las cartas:', error);
                    catalogoCartas.innerHTML = '<p>Error al cargar las cartas.</p>';
                });
        });
    </script>
    <script src="js/carrito.js"></script>
</body>
</html>