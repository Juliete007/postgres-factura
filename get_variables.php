<?php
// Conexión a la base de datos PostgreSQL
$conn = pg_connect("host=bnk6mxyzwg04dorrxsyk-postgresql.services.clever-cloud.com dbname=bnk6mxyzwg04dorrxsyk user=ughuuywkcfgjimu2goew password=yyXzddi5XIINcOBBpVCQxOCz2Wlvc0");

if (!$conn) {
    echo "<option value=''>Error: No se pudo conectar a la base de datos</option>";
    exit;
}

// Obtener la tabla seleccionada del POST
$tablaSeleccionada = $_POST['tabla'];

// Consulta para obtener las columnas de la tabla
$query = "SELECT column_name, data_type 
        FROM information_schema.columns 
        WHERE table_name = '$tablaSeleccionada'";

$result = pg_query($conn, $query);

if (!$result) {
    echo "<option value=''>Error en la consulta</option>";
    exit;
}

// Crear las opciones para el desplegable de variables
$options = '';
while ($row = pg_fetch_assoc($result)) {
    $column_name = $row['column_name'];
    $data_type = $row['data_type'];
    $options .= "<option value='$column_name'>$column_name ($data_type)</option>";
}

// Liberar recursos y cerrar la conexión
pg_free_result($result);
pg_close($conn);

// Devolver las opciones como respuesta AJAX
echo $options;
?>