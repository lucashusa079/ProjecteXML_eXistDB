<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Comptar CDs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mb-4">Nombre total de CDs</h1>

        <?php
        // Funció per comptar el nombre total de CDs
        function comptarCDs()
        {
            $xmlFile = "cataleg.xml";
            if (!file_exists($xmlFile)) {
                return "<NumTotalCDs>0</NumTotalCDs>";
            }

            // Carregar el document XML
            $xml = simplexml_load_file($xmlFile);
            if ($xml === false) {
                return "<NumTotalCDs>Error: No es pot carregar el fitxer XML.</NumTotalCDs>";
            }

            // Comptar el nombre de CDs
            $totalCDs = count($xml->cd);
            return "<NumTotalCDs>$totalCDs</NumTotalCDs>";
        }

        // Obtenir el nombre total de CDs
        $resultat = comptarCDs();
        // Extraure el nombre de l'etiqueta per mostrar-lo
        preg_match('/<NumTotalCDs>(.*?)<\/NumTotalCDs>/', $resultat, $matches);
        $nombreCDs = isset($matches[1]) ? $matches[1] : "Error";

        // Mostrar el resultat en gran
        echo "<div class='alert alert-info mt-3'>";
        echo "<h2>El nombre de CDs que hi ha es: $nombreCDs</h2>";
        echo "</div>";
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>