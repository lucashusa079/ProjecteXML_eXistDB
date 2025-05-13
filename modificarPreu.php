<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Modificar preu d’un CD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Modificar preu d’un CD</h2>
        <form method="post" class="mb-3">
            <div class="mb-3">
                <label for="titol" class="form-label">Títol del CD:</label>
                <input type="text" name="titol" id="titol" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nou_preu" class="form-label">Nou preu (€):</label>
                <input type="number" step="0.01" min="0" name="nou_preu" id="nou_preu" class="form-control" required>
            </div>
            <button type="submit" name="modificarPreu" class="btn btn-warning">Modificar preu</button>
        </form>

        <?php
        if (isset($_POST['modificarPreu'])) {
            $titol = trim(htmlspecialchars($_POST['titol']));
            $nou_preu = floatval(trim($_POST['nou_preu']));
            $ruta = __DIR__ . '/cataleg.xml';

            // Validar el preu
            if ($nou_preu < 0) {
                echo "<div class='alert alert-danger'>El preu no pot ser negatiu.</div>";
            } elseif (!file_exists($ruta)) {
                echo "<div class='alert alert-danger'>El fitxer cataleg.xml no existeix. Crea’l primer.</div>";
            } else {
                $xml = new DOMDocument();
                $xml->preserveWhiteSpace = false;
                $xml->formatOutput = true;
                $xml->load($ruta);
                $cds = $xml->getElementsByTagName('cd');
                $trobada = false;

                foreach ($cds as $cd) {
                    $titol_actual = $cd->getElementsByTagName('titol')->item(0)->nodeValue;
                    if (strcasecmp($titol_actual, $titol) == 0) {
                        $cd->getElementsByTagName('preu')->item(0)->nodeValue = number_format($nou_preu, 2, '.', '');
                        $trobada = true;
                        break;
                    }
                }

                if ($trobada) {
                    $xml->save($ruta);
                    echo "<div class='alert alert-success'>Preu modificat correctament.</div>";
                } else {
                    echo "<div class='alert alert-warning'>No s'ha trobat cap CD amb el títol '$titol'.</div>";
                }
            }
        }
        ?>
    </div>
</body>

</html>