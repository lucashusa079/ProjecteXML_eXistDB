<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Insertar elements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="container">
        <h1 class="mb-4">Afegir nou CD</h1>

        <form method="post">
            <input type="text" name="titol" class="form-control mb-2" placeholder="Títol" required>
            <input type="text" name="artista" class="form-control mb-2" placeholder="Artista" required>
            <input type="text" name="pais" class="form-control mb-2" placeholder="País" required>
            <input type="number" step="0.01" name="preu" class="form-control mb-2" placeholder="Preu" required>
            <input type="number" name="any" class="form-control mb-2" placeholder="Any" required>
            <button type="submit" name="afegirCD" class="btn btn-primary">Afegir CD</button>
        </form>

        <hr>

        <?php
        // Codi per afegir CD
        if (isset($_POST['afegirCD'])) {
            // Validació bàsica dels camps
            $titol = filter_input(INPUT_POST, 'titol', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $artista = filter_input(INPUT_POST, 'artista', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pais = filter_input(INPUT_POST, 'pais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $preu = filter_input(INPUT_POST, 'preu', FILTER_VALIDATE_FLOAT);
            $any = filter_input(INPUT_POST, 'any', FILTER_VALIDATE_INT);

            if ($titol && $artista && $pais && $preu !== false && $any !== false) {
                // Obtenim el nou id i afegim el CD
                $nou_id = obtenirNouId();
                afegirElementAlXML($nou_id, $titol, $artista, $pais, $preu, $any);

                echo '<div class="alert alert-success mt-3">CD afegit correctament!</div>';
            } else {
                echo '<div class="alert alert-danger mt-3">Error: Dades invàlides. Comprova els camps.</div>';
            }
        }

        // Funció per obtenir l'ID més gran i generar el següent
        function obtenirNouId()
        {
            $xmlFile = "cataleg.xml";
            if (!file_exists($xmlFile)) {
                return 1; // Si el fitxer no existeix, comencem amb ID 1
            }

            // Carregar el document XML
            $xml = simplexml_load_file($xmlFile);
            if ($xml === false) {
                die("Error: No es pot carregar el fitxer XML.");
            }

            // Buscar l'ID més gran
            $maxId = 0;
            foreach ($xml->cd as $cd) {
                $currentId = (int) $cd['id'];
                if ($currentId > $maxId) {
                    $maxId = $currentId;
                }
            }

            return $maxId + 1; // Retornem l'ID més gran + 1
        }

        // Funció per afegir un CD al fitxer XML
        function afegirElementAlXML($id, $titol, $artista, $pais, $preu, $any)
        {
            $xmlFile = "cataleg.xml";
            if (!file_exists($xmlFile)) {
                // Si el fitxer no existeix, creem una estructura base
                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cataleg></cataleg>');
            } else {
                $xml = simplexml_load_file($xmlFile);
                if ($xml === false) {
                    die("Error: No es pot carregar el fitxer XML.");
                }
            }

            // Afegim un nou cd amb l'atribut id generat
            $nouCD = $xml->addChild("cd");
            $nouCD->addAttribute("id", $id);
            $nouCD->addChild("titol", htmlspecialchars($titol));
            $nouCD->addChild("artista", htmlspecialchars($artista));
            $nouCD->addChild("pais", htmlspecialchars($pais));
            $nouCD->addChild("preu", number_format($preu, 2, '.', ''));
            $nouCD->addChild("any", $any);

            // Guardem el fitxer XML amb format correcte
            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            $dom->save($xmlFile) or die("Error: No es pot guardar el fitxer XML.");
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>