<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "CromoGol"; // Asegúrate de que coincida con el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Determinar el tipo de carta a filtrar
$tipo_carta = isset($_GET['tipo']) ? $_GET['tipo'] : null;
$sql = "SELECT referencia, nombre, descripcion, precio, liga, equipo, temporada, tipo_carta, posicion FROM productos";

if ($tipo_carta) {
    $sql .= " WHERE tipo_carta = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) { // Verificar si la preparación fue exitosa
        $stmt->bind_param("s", $tipo_carta);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Error al preparar la consulta: " . $conn->error); // Manejar error de preparación
    }
} else {
    $result = $conn->query($sql);
    if (!$result) {
        die("Error al ejecutar la consulta: " . $conn->error); // Manejar error de query
    }
}

$cartas = array();

if ($result && $result->num_rows > 0) { // Verificar que $result sea válido
    while ($row = $result->fetch_assoc()) {
        $cartas[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($cartas);
?>