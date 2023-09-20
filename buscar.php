<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <title>Resultados de la Búsqueda</title>
</head>
<body>
    <h1>Resultados de la Búsqueda</h1>
    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Establecer una conexión a la base de datos PostgreSQL
        $dbhost = "bnk6mxyzwg04dorrxsyk-postgresql.services.clever-cloud.com"; // Cambia esto al host de tu base de datos PostgreSQL
        $dbname = "bnk6mxyzwg04dorrxsyk";
        $dbuser = "ughuuywkcfgjimu2goew";
        $dbpass = "yyXzddi5XIINcOBBpVCQxOCz2Wlvc0";

        try {
            $conn = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        // Obtener el Código de Población proporcionado por el usuario
        $cod_pob = $_POST["cod_pob"];

        // Preparar la consulta SQL para buscar clientes por Código de Población
        $sql = "SELECT * FROM client WHERE cod_pob = :cod_pob";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cod_pob', $cod_pob, PDO::PARAM_STR);
            $stmt->execute();

            // Verificar si se encontraron resultados
            if ($stmt->rowCount() > 0) {
                // Mostrar los resultados en una tabla
                echo "<table border='1'>";
                echo "<tr><th>Código del cliente</th><th>Nombre</th><th>Dirección</th><th>Código postal</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr><td>" . $row["cod_cli"] . "</td><td>" . $row["nom"] . "</td><td>" . $row["adreca"] . "</td><td>" . $row["cp"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<h1>No se encontraron clientes en este Código de población</h1>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Cerrar la conexión a la base de datos
        $conn = null;
    } else {
        // Si el formulario no se ha enviado, inicializa $cod_pob
        $cod_pob = "";
    }
    ?>

    <!-- Agrega un formulario para ingresar el Código de Población -->
    <form method="POST" action="buscar.php">
        <label for="cod_pob">Código de la población:</label>
        <input type="text" name="cod_pob" id="cod_pob" required>
        <input type="submit" value="Buscar">
    </form>
</body>
</html>
