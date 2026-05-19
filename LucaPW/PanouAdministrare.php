<?php
// pornim sesiunea
session_start();

// daca nu exista un user_id in sesiune mergem la login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
//daca e user normal
if ($_SESSION['rol'] !== 'admin') {
    // Îl trimitem cu forța pe pagina de vizionare a catalogului
    header("Location: InchieriVanzari.php");
    exit();
}

// includem configurarea bazei de date
require_once 'config_db.php';

// logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
    header("Location: login.php");
    exit();
}

$mesaj_succes = '';
$mesaj_eroare = '';

// CERINȚĂ: PROCESARE FORMULAR ȘI UPLOAD DE FIȘIERE PE SERVER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca = trim($_POST['marca'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $pret  = intval($_POST['pret'] ?? 0);
    $cale_fisier = null;

    // Validare simplă
    if (empty($marca) || empty($model) || $pret <= 0) {
        $mesaj_eroare = "Vă rugăm să completați marca, modelul și un preț valid!";
    } else {
        if (isset($_FILES['gar']) && $_FILES['gar']['error'] === UPLOAD_ERR_OK) {
            $nume_fisier = basename($_FILES['gar']['name']);
            $nume_unic = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $nume_fisier);
            $folder_upload = __DIR__ . '/uploads';
            if (!is_dir($folder_upload)) {
                mkdir($folder_upload, 0777, true);
            }

            $destinatie = $folder_upload . '/' . $nume_unic;
            if (move_uploaded_file($_FILES['gar']['tmp_name'], $destinatie)) {
                $cale_fisier = 'uploads/' . $nume_unic;
            } else {
                $mesaj_eroare = "Eroare la încărcarea fișierului pe server.";
            }
        }
        if (empty($mesaj_eroare)) {
            try {
                $stmt = $conn_pdo->prepare("INSERT INTO masini (marca, model, pret, imagine, adaugat_de) VALUES (:marca, :model, :pret, :imagine, :user_id)");
                $stmt->execute([
                    'marca'   => $marca,
                    'model'   => $model,
                    'pret'    => $pret,
                    'imagine' => $cale_fisier,
                    'user_id' => $_SESSION['user_id'] // Relație cu userul logat
                ]);
                $mesaj_succes = "Vehiculul a fost adăugat cu succes în baza de date!";
            } catch (PDOException $e) {
                $mesaj_eroare = "Eroare la salvarea în baza de date: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>AutoHub - Administrare</title>
  <link rel="stylesheet" href="stil_responsive.css">

  <style>
    header {
      overflow-x: hidden;
      padding: 10px 0;
    }
    header h1 {
      display: inline-block;
      background: linear-gradient(90deg, #2c3e50 0%, #3498db 30%, #ffffff 50%, #3498db 70%, #2c3e50 100%);
      background-size: 200% auto;
      color: transparent;
      -webkit-background-clip: text;
      background-clip: text;

      animation: brakeAndSettle 1.2s cubic-bezier(0.25, 1, 0.5, 1) forwards,
      headlightShine 3s linear infinite;
      padding-right: 20px;
    }

    @keyframes brakeAndSettle {
      0% { transform: translateX(-100vw) skewX(-40deg); opacity: 0; }
      60% { transform: translateX(30px) skewX(15deg); opacity: 1; }
      80% { transform: translateX(-10px) skewX(-5deg); }
      100% { transform: translateX(0) skewX(0); }
    }

    @keyframes headlightShine {
      to { background-position: 200% center; }
    }

    .user-bar { background: #2c3e50; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 5px; margin-bottom: 15px; font-family: Arial, sans-serif; }
    .btn-logout { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
    .btn-logout:hover { background: #c0392b; }
    .alert-succes { background: #2ecc71; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
    .alert-eroare { background: #e74c3c; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }

    /* Stiluri simple pentru tabelul de sub formular */
    .table-admin { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .table-admin th, .table-admin td { padding: 10px; border: 1px solid #ddd; text-align: center; }
    .table-admin th { background-color: #2c3e50; color: white; }
  </style>
</head>

<body>

<header>
  <h1>Panou Administrare - Adaugare Vehicul Nou</h1>
</header>

<div class="user-bar">
    <span>Autentificat ca: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (Rol: <span style="color:#3498db;"><?php echo htmlspecialchars($_SESSION['rol']); ?></span>)</span>
    <a href="PanouAdministrare.php?action=logout" class="btn-logout">Deconectare</a>
</div>


<section style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px dashed #2c3e50;">
    <h2 style="margin-top: 0; font-size: 1.2rem; color: #2c3e50;">Setări Profil (Date precompletate din DB)</h2>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <p>
            <label>Nume Utilizator:</label><br>
            <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="padding: 8px; background: #eee; border: 1px solid #ccc; border-radius: 4px;">
        </p>

        <p>
            <label>Rol în Sistem:</label><br>
            <select disabled style="padding: 8px; background: #eee; border: 1px solid #ccc; border-radius: 4px;">
                <option value="admin" <?php echo ($_SESSION['rol'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                <option value="client" <?php echo ($_SESSION['rol'] == 'client') ? 'selected' : ''; ?>>Client</option>
            </select>
        </p>

        <p>
            <label>Notă stare cont:</label><br>
            <textarea readonly rows="1" style="padding: 8px; background: #eee; border: 1px solid #ccc; border-radius: 4px;">Utilizator logat cu drepturi de <?php echo htmlspecialchars($_SESSION['rol']); ?></textarea>
        </p>
    </div>
</section>
<nav class="meniu-principal">
  <ul>
    <li><a href="InchieriVanzari.php" title="Pagina Principala">Catalog Auto</a></li>
    <li><a href="PanouAdministrare.php" title="Pagina Administrare">Panou Administrare</a></li>
    <li><a href="dashboard.php" title="Widgeturi">Dashboard</a></li>
    <li><a href="sprites.php" title="Sprite-uri">Meniu Sprites</a></li>
  </ul>
</nav>

<main>
  <?php if (!empty($mesaj_succes)): ?>
      <div class="alert-succes"><?php echo htmlspecialchars($mesaj_succes); ?></div>
  <?php endif; ?>

  <?php if (!empty($mesaj_eroare)): ?>
      <div class="alert-eroare"><?php echo htmlspecialchars($mesaj_eroare); ?></div>
  <?php endif; ?>

  <form id="form-admin" action="PanouAdministrare.php" method="post" enctype="multipart/form-data" novalidate>
    <fieldset>
      <legend>Date Identificare si Configurare</legend>

      <p>
        Cod Unic (VIN):
        <input type="text" name="vin" value="RO-AUTO-2026" readonly maxlength="17" size="20">
      </p>

      <p>
        Marca Vehicul:
        <select name="marca" id="select-marca">
          <option value="">-- Selectează Marca --</option>
        </select>
        &nbsp;&nbsp;Model:
        <select name="model" id="select-model" disabled>
          <option value="">-- Alegeți întâi marca --</option>
        </select>
      </p>

      <p>
        Categorie Vehicul:<br>
        <select name="cat" multiple size="3">
          <option value="sedan" selected>Sedan</option>
          <option value="suv">SUV</option>
          <option value="utilitara">Utilitara</option>
        </select>
      </p>

      <p>
        Tip Combustibil:
        <label><input type="radio" name="comb" value="ben" checked> Benzina</label>
        <label><input type="radio" name="comb" value="die"> Diesel</label>
        <label><input type="radio" name="comb" value="ele" disabled> Electric (Indisponibil)</label>
      </p>

      <p>
        Dotari Siguranta:
        <label><input type="checkbox" name="abs" value="da" checked> ABS</label>
        <label><input type="checkbox" name="esp" value="da"> ESP</label>
        <label><input type="checkbox" name="airbag" value="da"> Airbag Cortina</label>
      </p>

      <p>
        An Fabricatie (Maxim 2026):
        <input type="number" name="an" id="an-fabricatie" value="2024" max="2026">
        &nbsp;&nbsp;Pret (Euro):
        <input type="number" name="pret" step="500" value="15000">
      </p>

      <p>
        Descriere Tehnica: <br>
        <textarea name="desc" cols="50" rows="4">Masina este dotata cu scaune incalzite si tapiterie de piele.</textarea>
      </p>

      <p>
        Culoare Exterior: <input type="color" name="culoare"> &nbsp;
        Data intrarii in stoc: <input type="date" name="data" id="data-intrare"> &nbsp;
        Kilometraj: <input type="range" name="km" value="0" max="300000">
      </p>

      <p>
        Email Furnizor: <input type="email" name="mail"> &nbsp;
        Parola Sistem: <input type="password" name="pass" size="10"> &nbsp;
        Fisier Garantie (Poza sau PDF): <input type="file" name="gar">
      </p>

      <p>
        <input type="submit" value="Trimite Datele">
        <input type="reset" value="Reseteaza Formularul">
      </p>

    </fieldset>
  </form>

  <h2 style="margin-top: 40px; border-bottom: 2px solid #2c3e50; padding-bottom: 5px;">Vehicule adăugate recent</h2>
  <table class="table-admin">
      <thead>
          <tr>
              <th>ID</th>
              <th>Marcă</th>
              <th>Model</th>
              <th>Preț</th>
              <th>Imagine</th>
          </tr>
      </thead>
      <tbody>
          <?php
          try {
              $stmt_lista = $conn_pdo->query("SELECT * FROM masini ORDER BY id DESC LIMIT 5");
              while ($rand = $stmt_lista->fetch(PDO::FETCH_ASSOC)) {
                  $img_src = !empty($rand['imagine']) ? htmlspecialchars($rand['imagine']) : 'logo_auto.png';
                  echo "<tr>";
                  echo "<td>{$rand['id']}</td>";
                  echo "<td>" . htmlspecialchars($rand['marca']) . "</td>";
                  echo "<td>" . htmlspecialchars($rand['model']) . "</td>";
                  echo "<td>" . number_format($rand['pret'], 0, ',', '.') . " €</td>";
                  echo "<td><img src='{$img_src}' width='60' style='object-fit:cover; border-radius:4px;'></td>";
                  echo "</tr>";
              }
          } catch (PDOException $e) {
              echo "<tr><td colspan='5'>Eroare la citirea bazei de date.</td></tr>";
          }
          ?>
      </tbody>
  </table>
</main>

<script src="jquery-3.7.1.min.js"></script>
<script src="date_masini.js"></script>
<script src="dependente.js"></script>
<script src="validare.js"></script>
</body>
</html>