<?php
session_start();
require_once 'config_db.php';

// Preluăm mașinile din baza de date, împreună cu numele utilizatorului care le-a adăugat
$masini = [];
try {
    $query = "SELECT m.*, u.username FROM masini m
              LEFT JOIN utilizatori u ON m.adaugat_de = u.id
              ORDER BY m.id DESC";
    $stmt = $conn_pdo->query($query);
    $masini = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $eroare_db = "Eroare la încărcarea datelor: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>AutoHub - Catalog Auto</title>
  <link rel="stylesheet" href="stil_responsive.css">
  <style>
    .user-bar { background: #2c3e50; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 5px; margin-bottom: 15px; font-family: Arial, sans-serif; }
    .btn-logout { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
    .btn-logout:hover { background: #c0392b; }

    /* Stiluri specifice pentru grila de mașini afișată dinamic */
    .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
    .card-masina { background: white; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; }
    .card-masina img { width: 100%; height: 180px; object-fit: cover; border-bottom: 1px solid #eee; }
    .card-detalii { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .card-detalii h3 { margin: 0 0 10px 0; color: #2c3e50; font-size: 1.3rem; }
    .pret { font-size: 1.2rem; color: #e74c3c; font-weight: bold; margin: 5px 0; }
    .footer-card { font-size: 0.85rem; color: #7f8c8d; margin-top: 10px; border-top: 1px solid #eee; padding-top: 8px; }
  </style>
</head>
<body>

<header>
  <h1 style="text-align: center; padding: 15px 0; color: #2c3e50;">Catalog Auto - Închirieri & Vânzări</h1>
</header>

<?php if (isset($_SESSION['user_id'])): ?>
<div class="user-bar">
    <span>Autentificat ca: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
    <a href="PanouAdministrare.php?action=logout" class="btn-logout">Deconectare</a>
</div>
<?php endif; ?>

<nav class="meniu-principal">
  <ul>
    <li><a href="InchieriVanzari.php" title="Pagina Principala">Catalog Auto</a></li>
    <li><a href="PanouAdministrare.php" title="Pagina Administrare">Panou Administrare</a></li>
    <li><a href="dashboard.php" title="Widgeturi">Dashboard</a></li>
    <li><a href="sprites.php" title="Sprite-uri">Meniu Sprites</a></li>
  </ul>
</nav>

<main>
  <h2 style="border-bottom: 2px solid #3498db; padding-bottom: 5px;">Vehicule Disponibile</h2>

  <?php if (isset($eroare_db)): ?>
      <p style="color: #e74c3c; font-weight: bold;"><?php echo htmlspecialchars($eroare_db); ?></p>
  <?php endif; ?>

  <div class="catalog-grid">
      <?php if (!empty($masini)): ?>
          <?php foreach ($masini as $m): ?>
              <?php
                  // Setăm o imagine default dacă rândul curent nu conține o imagine validă
                  $img = !empty($m['imagine']) ? htmlspecialchars($m['imagine']) : 'logo_auto.png';
                  $user_adaugare = !empty($m['username']) ? htmlspecialchars($m['username']) : 'Necunoscut';
              ?>
              <div class="card-masina">
                  <img src="<?php echo $img; ?>" alt="Imagine Vehicul">
                  <div class="card-detalii">
                      <div>
                          <h3><?php echo htmlspecialchars($m['marca'] . ' ' . $m['model']); ?></h3>
                          <p class="pret"><?php echo number_format($m['pret'], 0, ',', '.'); ?> €</p>
                      </div>
                      <div class="footer-card">
                          Adăugat de: <strong><?php echo $user_adaugare; ?></strong>
                      </div>
                  </div>
              </div>
          <?php endforeach; ?>
      <?php else: ?>
          <p style="grid-column: 1 / -1; text-align: center; font-size: 1.1rem; color: #7f8c8d; padding: 40px 0;">
              Nu s-au găsit vehicule înregistrate în baza de date. Adaugă unul nou din secțiunea de Administrare.
          </p>
      <?php endif; ?>
  </div>
</main>

<script src="jquery-3.7.1.min.js"></script>
<script src="index.js"></script>
</body>
</html>