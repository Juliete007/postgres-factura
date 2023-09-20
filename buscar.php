<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <title>Consulta de Tablas y Variables</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Cuando cambie la selección de la tabla
            $('#tabla').change(function () {
                var tablaSeleccionada = $(this).val();

                // Hacer una solicitud AJAX para obtener las variables de la tabla
                $.ajax({
                    url: 'get_variables.php',
                    type: 'POST',
                    data: { tabla: tablaSeleccionada },
                    success: function (response) {
                        // Llenar el desplegable de variables con la respuesta
                        $('#variable').html(response);
                        $('#variable').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h1>Consulta de Tablas y Variables</h1>
    <?php
    // Conexión a la base de datos PostgreSQL
    $conn = pg_connect("host=bnk6mxyzwg04dorrxsyk-postgresql.services.clever-cloud.com dbname=bnk6mxyzwg04dorrxsyk user=ughuuywkcfgjimu2goew password=yyXzddi5XIINcOBBpVCQxOCz2Wlvc0");

    if (!$conn) {
        echo "<h1>Error: No se pudo conectar a la base de datos</h1>";
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
        echo "<h1>Error en la consulta</h1>";
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $tabla = $_POST["tabla"];
        $variable = $_POST["variable"];
        $valor = $_POST["valor"];
        }
    ?>


    <!-- Agrega un formulario para ingresar el Código de Población -->
    <form action="buscar.php" method="post">
        <label for="tabla">Selecciona una tabla:</label>
        <select id="tabla" name="tabla">
            <option value="article">Artículo</option>
            <option value="categoria">Categoría</option>
            <option value="client">Cliente</option>
            <option value="factura">Factura</option>
            <option value="linia_fac">Línea de la factura</option>
            <option value="poble">Pueblo</option>
            <option value="provincia">Provincia</option>
            <option value="venedor">Vendedor</option>
        </select>

        <label for="variable">Selecciona una variable:</label>
        <select id="variable" name="variable" disabled></select>

        <label for="valor">Valor:</label>
        <input type="text" id="valor" name="valor">

        <input type="submit" value="Buscar">
    </form>
</body>
</html>
