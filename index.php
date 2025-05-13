<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Projecte XML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">

        <h1 class="mb-4">Projecte XML</h1>

        <form method="post" class="mb-3">
            <button type="submit" name="crearXML" class="btn btn-primary">Crear catàleg XML</button>
        </form>

        <h2>Crear nova col·lecció a eXistDB</h2>
        <form method="post" class="mb-3">
            <input type="text" name="nom_colleccio" class="form-control mb-2" placeholder="Nom de la col·lecció"
                required>
            <button type="submit" name="crearColleccio" class="btn btn-success">Crear col·lecció</button>
        </form>

        <h2>Pujar document XML a eXistDB</h2>
        <form method="post">
            <input type="text" name="nom_document" class="form-control mb-2"
                placeholder="Nom del document (ex: cataleg.xml)" required>
            <input type="text" name="destinacio" class="form-control mb-2"
                placeholder="Nom de la col·lecció (ex: Projecte)" required>
            <button type="submit" name="pujarDocument" class="btn btn-warning">Pujar document</button>
        </form>

        <hr>

        <?php
        if (isset($_POST['crearXML'])) {
            $ruta = __DIR__ . '/cataleg.xml';
            if (file_exists($ruta)) {
                echo "<div class='alert alert-warning'>El fitxer cataleg.xml ja existeix.</div>";
            } else {
                $xml = new DOMDocument("1.0", "UTF-8");
                $xml->formatOutput = true;
                $arrel = $xml->createElement("cataleg");
                $xml->appendChild($arrel);
                if ($xml->save($ruta)) {
                    echo "<div class='alert alert-success'>Fitxer cataleg.xml creat correctament!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error en crear el fitxer.</div>";
                }
            }
        }

        // Crear col·lecció
        if (isset($_POST['crearColleccio'])) {
            $nom = $_POST['nom_colleccio'];
            $url = "http://localhost:8080/exist/rest/db/$nom";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "admin:");
            $resposta = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 201) {
                echo "<div class='alert alert-success'>Col·lecció '$nom' creada correctament.</div>";
            } elseif ($httpCode == 405) {
                echo "<div class='alert alert-warning'>La col·lecció '$nom' ja existeix.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error en crear la col·lecció (Codi HTTP: $httpCode)</div>";
            }
        }

        // Pujar document
        if (isset($_POST['pujarDocument'])) {
            $document = $_POST['nom_document'];
            $destinacio = $_POST['destinacio'];
            $fitxer_path = __DIR__ . "/$document";

            if (!file_exists($fitxer_path)) {
                echo "<div class='alert alert-danger'>El fitxer '$document' no existeix.</div>";
            } else {
                $url = "http://localhost:8080/exist/rest/db/$destinacio/$document";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_PUT, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, "admin:");
                $fitxer = fopen($fitxer_path, 'r');
                curl_setopt($ch, CURLOPT_INFILE, $fitxer);
                curl_setopt($ch, CURLOPT_INFILESIZE, filesize($fitxer_path));

                $resposta = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                fclose($fitxer);

                if ($httpCode == 201 || $httpCode == 200) {
                    echo "<div class='alert alert-success'>Fitxer '$document' pujat correctament a la col·lecció '$destinacio'.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error en pujar el fitxer (Codi HTTP: $httpCode)</div>";
                }
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>