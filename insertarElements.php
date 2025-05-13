<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8">
  <title>Afegir CD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">

  <?php include 'header.php'; ?>

  <h1 class="mb-4">Afegir nou CD al catàleg</h1>

  <form method="post" class="mb-4">
    <input type="text" name="titol" class="form-control mb-2" placeholder="Títol" required>
    <input type="text" name="artista" class="form-control mb-2" placeholder="Artista" required>
    <input type="text" name="pais" class="form-control mb-2" placeholder="País" required>
    <input type="number" step="0.01" name="preu" class="form-control mb-2" placeholder="Preu" required>
    <input type="number" name="any" class="form-control mb-2" placeholder="Any" required>
    <button type="submit" name="afegirCD" class="btn btn-success">Afegir CD</button>
  </form>

  <?php
  if (isset($_POST['afegirCD'])) {
    $ruta = __DIR__ . '/cataleg.xml';
    if (!file_exists($ruta)) {
      echo "<div class='alert alert-danger'>Error: El fitxer cataleg.xml no existeix. Primer crea’l.</div>";
    } else {
      $xml = new DOMDocument();
      $xml->load($ruta);

      // Últim ID
      $cds = $xml->getElementsByTagName('cd');
      $ultim_id = 0;
      foreach ($cds as $cd) {
        $id_actual = (int)$cd->getAttribute('id');
        if ($id_actual > $ultim_id) {
          $ultim_id = $id_actual;
        }
      }
      $nou_id = $ultim_id + 1;

      // Crear nou CD
      $nou_cd = $xml->createElement('cd');
      $nou_cd->setAttribute('id', $nou_id);
      $nou_cd->appendChild($xml->createElement('titol', $_POST['titol']));
      $nou_cd->appendChild($xml->createElement('artista', $_POST['artista']));
      $nou_cd->appendChild($xml->createElement('pais', $_POST['pais']));
      $nou_cd->appendChild($xml->createElement('preu', $_POST['preu']));
      $nou_cd->appendChild($xml->createElement('any', $_POST['any']));

      // Afegir-lo
      $cataleg = $xml->getElementsByTagName('cataleg')->item(0);
      $cataleg->appendChild($nou_cd);

      // Desa
      if ($xml->save($ruta)) {
        echo "<div class='alert alert-success'>CD afegit correctament amb ID $nou_id.</div>";
      } else {
        echo "<div class='alert alert-danger'>Error en desar el CD.</div>";
      }
    }
  }
  ?>

</body>

</html>
