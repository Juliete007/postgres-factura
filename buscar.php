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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger los datos del formulario
        $tabla = $_POST["tabla"];
        $variable = $_POST["variable"];
        $valor = $_POST["valor"];
    
        // Realizar la consulta con los datos ingresados
        $query = "SELECT * FROM $tabla WHERE $variable = '$valor'";
    
        $result = pg_query($conn, $query);
    
        if (!$result) {
            echo "<h1>Error en la consulta</h1>";
            echo "<form action='buscar.php' method='post'>";
            echo "<label for='tabla'>Selecciona una tabla:</label>";
            echo "<select id='tabla' name='tabla'>";
            echo "<option value='article'>Artículo</option>";
            echo "<option value='categoria'>Categoría</option>";
            echo "<option value='client'>Cliente</option>";
            echo "<option value='factura'>Factura</option>";
            echo "<option value='linia_fac'>Línea de la factura</option>";
            echo "<option value='poble'>Pueblo</option>";
            echo "<option value='provincia'>Provincia</option>";
            echo "<option value='venedor'>Vendedor</option>";
            echo "</select>";
        
            echo "<label for='variable'>Selecciona una variable:</label>";
            echo "<select id='variable' name='variable' disabled></select>";
        
            echo "<label for='valor'>Valor:</label>";
            echo "<input type='text' id='valor' name='valor'>";
        
            echo "<input type='submit' value='Buscar'>";
            echo "</form>";
            exit;
        }        

        // Comienza la tabla HTML
        echo "<div id='table-container'>";
        echo "<table border='1'>";
        echo "<tr>";

        // Obtén el nombre de las columnas y crea las cabeceras de la tabla
        $numFields = pg_num_fields($result);
        for ($i = 0; $i < $numFields; $i++) {
            echo "<th>" . pg_field_name($result, $i) . "</th>";
        }

        echo "</tr>";

        // Muestra los resultados de la consulta en la tabla
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }

        // Cierra la tabla HTML
        echo "</table>";
        echo "</div>";
        echo "<button id='export-button'>Exportar a Excel</button>";
        // Liberar recursos y cerrar la conexión
        pg_free_result($result);
        pg_close($conn);
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
    <script>
        document.getElementById("export-button").addEventListener("click", function () {
        // Obtiene la tabla
        const table = document.querySelector("table");

        // Crea un objeto Blob con los datos de la tabla
        const blob = new Blob([table.outerHTML], {
        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8",
        });

        // Crea un objeto URL a partir del Blob
        const url = URL.createObjectURL(blob);

        // Crea un enlace de descarga
        const a = document.createElement("a");
        a.href = url;
        a.download = "tabla_excel.xlsx"; // Nombre del archivo Excel
        document.body.appendChild(a);

        // Simula un clic en el enlace para descargar el archivo
        a.click();

        // Limpia el objeto URL y elimina el enlace de descarga
        URL.revokeObjectURL(url);
        document.body.removeChild(a);
        });
    </script>
</body>
</html>
