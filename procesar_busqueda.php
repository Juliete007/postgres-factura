<?php
// Conexión a la base de datos PostgreSQL (reemplaza con tus datos)
$conn = pg_connect("host=bnk6mxyzwg04dorrxsyk-postgresql.services.clever-cloud.com dbname=bnk6mxyzwg04dorrxsyk user=ughuuywkcfgjimu2goew password=yyXzddi5XIINcOBBpVCQxOCz2Wlvc0");

if (!$conn) {
    echo "Error: No se pudo conectar a la base de datos";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $tabla = $_POST["tabla"];
    $variable = $_POST["variable"];
    $valor = $_POST["valor"];

    // Realizar la consulta con los datos ingresados
    $query = "SELECT * FROM $tabla WHERE $variable = '$valor'";

    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Error en la consulta";
        exit;
    }

    // Procesa los resultados y muestra los datos
    while ($row = pg_fetch_assoc($result)) {
        // Aquí puedes mostrar los datos de cada fila como desees
        echo "ID: " . $row["id"] . "<br>";
        echo "Nombre: " . $row["nombre"] . "<br>";
        // ... continúa con otras columnas ...
    }

    // Liberar recursos y cerrar la conexión
    pg_free_result($result);
    pg_close($conn);
}
?>
