<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Esborrar CD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mb-4">Esborrar un CD</h1>

        <form method="post" class="mb-3">
            <div class="mb-3">
                <label for="titol" class="form-label">Títol del CD:</label>
                <input type="text" name="titol" id="titol" class="form-control" placeholder="Introdueix el títol del CD"
                    required>
            </div>
            <button type="submit" name="esborrarCD" class="btn btn-danger">Esborrar CD</button>
        </form>

        <?php
        // Funció per esborrar un CD del fitxer XML donat el títol
        function esborrarCD($titol)
        {
            $xmlFile = "cataleg.xml";
            if (!file_exists($xmlFile)) {
                return "<div class='alert alert-danger'>El fitxer cataleg.xml no existeix.</div>";
            }

            // Carregar el document XML amb DOMDocument
            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            if (!$xml->load($xmlFile)) {
                return "<div class='alert alert-danger'>Error: No es pot carregar el fitxer XML.</div>";
            }

            $cds = $xml->getElementsByTagName('cd');
            $trobada = false;

            foreach ($cds as $cd) {
                $titol_actual = $cd->getElementsByTagName('titol')->item(0)->nodeValue;
                if (strcasecmp($titol_actual, $titol) == 0) {
                    $cd->parentNode->removeChild($cd);
                    $trobada = true;
                    break;
                }
            }

            if ($trobada) {
                $xml->save($xmlFile);
                return "<div class='alert alert-success'>CD amb títol '$titol' esborrat correctament.</div>";
            } else {
                return "<div class='alert alert-warning'>No s'ha trobat cap CD amb el títol '$titol'.</div>";
            }
        }

        // Processar l'esborrat del CD
        if (isset($_POST['esborrarCD'])) {
            $titol = trim(htmlspecialchars($_POST['titol']));
            if (empty($titol)) {
                echo "<div class='alert alert-danger'>Error: El títol no pot estar buit.</div>";
            } else {
                echo esborrarCD($titol);
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>