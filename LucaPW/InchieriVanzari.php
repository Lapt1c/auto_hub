<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>AutoHub - Catalogul de Masini</title>
  <link rel="stylesheet" href="stil_responsive.css">
</head>

<body>

<header>
  <h1>
    <img src="logo_auto.png" alt="Logo AutoHub" width="100" height="50" title="AutoHub Logo">
    Sistem de Gestiune Parc Auto
  </h1>
</header>

<nav class="meniu-principal">
  <ul>
    <li><a href="InchieriVanzari.php" title="Pagina Principala">Catalog Auto</a></li>
    <li><a href="PanouAdministrare.php" title="Pagina Administrare">Panou Administrare</a></li>
    <li><a href="dashboard.php" title="Widgeturi">Dashboard</a></li>
    <li><a href="sprites.php" title="Sprite-uri">Meniu Sprites</a></li>
  </ul>
</nav>

<main>

  <section id="carusel-oferte" class="carousel-container">
    <button id="btn-prev" class="carousel-btn btn-prev">&#10094;</button>
    <button id="btn-next" class="carousel-btn btn-next">&#10095;</button>
  </section>

  <section id="sectiune-comparatie">
    <h2>⚖️ Comparație Tehnică (Tabel Vertical)</h2>
    <p><i>Apasă pe specificațiile din stânga (Preț, Putere etc.) pentru a sorta modelele!</i></p>
    <div id="container-tabel-vertical">
    </div>
  </section>

  <h2>Categorii si Modele Disponibile</h2>

  <h2>Categorii și Modele Disponibile</h2>
  <ul style="list-style: none; padding-left: 0;">
    <li>
      <span class="titlu-expandabil">Autoturisme de oraș</span>
      <ul class="sublista">
        <li>Dacia Sandero</li>
        <li>Renault Clio</li>
      </ul>
    </li>

    <li>
      <span class="titlu-expandabil">Vehicule de teren (SUV)</span>
      <ul class="sublista">
        <li>Dacia Duster <b>(Cel mai căutat)</b></li>
        <li>Hyundai Tucson</li>
      </ul>
    </li>
  </ul>

  <h2>Servicii Oferite</h2>
  <ul style="list-style: none; padding-left: 0;">
    <li>
      <span class="titlu-expandabil">Vânzări Auto</span>
      <ol class="sublista" type="a">
        <li>Rate fixe</li>
        <li>Leasing operațional</li>
      </ol>
    </li>
    <li style="padding: 5px 5px 5px 25px; color: #666;">
      Închirieri pe termen scurt (Fără sublistă)
    </li>
  </ul>
  <h2>Stoc Curent si Detalii Tehnice</h2>

  <table>
    <tr>
      <th>Imagine</th>
      <th>Model si Marca</th>
      <th>Specificatii Detaliate (Tabel Imbricat)</th>
    </tr>
    <tr>
      <td>
        <img src="masina1.jpg" alt="Poza Masina" width="150" height="100">
      </td>
      <td>
        <p><b>Dacia Duster 2024</b></p>
        <span>Disponibilitate: </span><strong>In Stoc</strong>
      </td>
      <td>
        <table>
          <tr>
            <td>Motorizare</td>
            <td>1.3 TCe Petrol</td>
          </tr>
          <tr>
            <td>Tractiune</td>
            <td>4x4 Permanent</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>

  <table>
    <tr>
      <th colspan="2">Program Locatii</th>
    </tr>
    <tr>
      <td>Luni - Vineri</td>
      <td>08:00 - 18:00</td>
    </tr>
  </table>

  <h2>Servicii Oferite</h2>

  <ol type="I">
    <li>Vanzari Auto
      <ol type="a">
        <li>Rate fixe</li>
        <li>Leasing operational</li>
      </ol>
    </li>
    <li>Inchirieri pe termen scurt</li>
  </ol>
  <section id="sectiune-rezervare">
    <h2>Rezervă un Test Drive</h2>
    <form id="form-rezervare" action="#" method="post">
      <fieldset>
        <legend>Date Programare</legend>
        <p>Nume Complet: <input type="text" name="nume_client" placeholder="Ex: Popescu Ion"></p>
        <p>Telefon: <input type="tel" name="telefon" placeholder="07xx xxx xxx"></p>
        <p>Data și Ora: <input type="datetime-local" name="data_test"></p>
        <p>
          Locație preluare:
          <select name="locatie">
            <option value="">-- Alege locația --</option>
            <option value="sediu">Sediu Central</option>
            <option value="aeroport">Aeroport</option>
          </select>
        </p>
        <p>
          <label><input type="checkbox" name="termeni" value="acceptat"> Accept termenii și condițiile</label>
        </p>
        <button type="submit" class="btn-dashboard">Programează</button>
      </fieldset>
    </form>
  </section>
<?php
// 1. Includem conexiunea la baza de date
require_once 'config_db.php';

try {
    // 2. Extragem toate mașinile din baza de date
    // Facem un JOIN cu tabelul utilizatori ca să știm și cine a adăugat-o (opțional)
    $query = "SELECT m.*, u.username FROM masini m
              JOIN utilizatori u ON m.adaugat_de = u.id
              ORDER BY m.id DESC";
    $stmt = $conn_pdo->query($query);
    $masini = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Eroare la încărcarea mașinilor: " . $e->getMessage();
}
?>

<section class="catalog-containter" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; padding: 20px;">
    <?php if (count($masini) > 0): ?>
        <?php foreach ($masini as $masina): ?>
            <div class="card-auto" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                <?php
                    $cale_imagine = !empty($masina['imagine']) ? $masina['imagine'] : 'logo_auto.png';
                ?>
                <img src="<?php echo htmlspecialchars($cale_imagine); ?>" alt="Imagine Mașină" style="width: 100%; height: 200px; object-fit: cover;">

                <div style="padding: 15px;">
                    <h3 style="margin: 0; color: #2c3e50;">
                        <?php echo htmlspecialchars($masina['marca'] . ' ' . $masina['model']); ?>
                    </h3>
                    <p style="font-size: 1.2rem; color: #e74c3c; font-weight: bold; margin: 10px 0;">
                        Preț: <?php echo number_format($masina['pret'], 0, ',', '.'); ?> €
                    </p>
                    <hr>
                    <p style="font-size: 0.8rem; color: #7f8c8d;">
                        Adăugat de: <strong><?php echo htmlspecialchars($masina['username']); ?></strong>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; grid-column: 1 / -1;">Nu există mașini în baza de date.</p>
    <?php endif; ?>
</section>
</main>
<script src="jquery-3.7.1.min.js"></script>
<script src="validare.js"></script>
<script src="carousel.js"></script>
<script src="date_tabele.js"></script>
<script src="tabele.js"></script>
<script src="liste.js"></script>
</body>
</html>