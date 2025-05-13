<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Llistar CDs per Any</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mb-4">Llistar CDs per Any</h1>

        <form method="post" class="mb-3">
            <div class="mb-3">
                <label for="any" class="form-label">Any:</label>
                <input type="number" name="any" id="any" class="form-control" placeholder="Introdueix un any" required>
            </div>
            <button type="submit" name="llistarCDs" class="btn btn-primary">Llistar CDs</button>
        </form>

        <?php
        // Funció per llistar CDs amb any més petit que el donat
        function llistarCDsPerAny($anyBuscat)
        {
            $xmlFile = "cataleg.xml";
            if (!file_exists($xmlFile)) {
                return "<div class='alert alert-danger'>El fitxer cataleg.xml no existeix.</div>";
            }

            // Carregar el document XML
            $xml = simplexml_load_file($xmlFile);
            if ($xml === false) {
                return "<div class='alert alert-danger'>Error: No es pot carregar el fitxer XML.</div>";
            }

            $resultats = [];
            foreach ($xml->cd as $cd) {
                $anyCD = (int) $cd->any;
                if ($anyCD < $anyBuscat) {
                    $resultats[] = [
                        'titol' => (string) $cd->titol,
                        'artista' => (string) $cd->artista
                    ];
                }
            }

            if (empty($resultats)) {
                return "<div class='alert alert-warning'>No s'han trobat CDs amb any menor a $anyBuscat.</div>";
            }

            // Crear la taula HTML amb els resultats
            $output = "<table class='table table-striped mt-3'>";
            $output .= "<thead><tr><th>Títol</th><th>Artista</th></tr></thead>";
            $output .= "<tbody>";
            foreach ($resultats as $cd) {
                $output .= "<tr><td>" . htmlspecialchars($cd['titol']) . "</td><td>" . htmlspecialchars($cd['artista']) . "</td></tr>";
            }
            $output .= "</tbody></table>";

            return $output;
        }

        // Processar la cerca de CDs
        if (isset($_POST['llistarCDs'])) {
            $any = filter_input(INPUT_POST, 'any', FILTER_VALIDATE_INT);
            if ($any === false || $any <= 0) {
                echo "<div class='alert alert-danger'>Error: Introdueix un any vàlid.</div>";
            } else {
                echo llistarCDsPerAny($any);
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>