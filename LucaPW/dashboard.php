<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - AutoHub</title>
    <link rel="stylesheet" href="stil_responsive.css">
    <link rel="stylesheet" href="stil_dashboard.css">
</head>
<body class="body-dashboard">

<nav class="meniu-principal">
  <ul>
    <li><a href="InchieriVanzari.php" title="Pagina Principala">Catalog Auto</a></li>
    <li><a href="PanouAdministrare.php" title="Pagina Administrare">Panou Administrare</a></li>
    <li><a href="dashboard.php" title="Widgeturi">Dashboard</a></li>
    <li><a href="sprites.php" title="Sprite-uri">Meniu Sprites</a></li>
  </ul>
</nav>

<main class="dashboard-container">
    <header class="dashboard-header">
        <h1 id="titlu-dashboard">📊 Panou de Comandă Central - AutoHub</h1>
        <p>Monitorizare în timp real a flotei și a vânzărilor</p>
    </header>

    <section class="kpi-grid">
        <article class="kpi-card card-info">
            <div class="kpi-valoare">124</div>
            <div class="kpi-titlu">Vehicule în Flotă</div>
            <div class="kpi-trend trend-pozitiv">↑ +3 adăugate azi</div>
        </article>

        <article class="kpi-card card-succes">
            <div class="kpi-valoare">45</div>
            <div class="kpi-titlu">Închirieri Active</div>
            <div class="kpi-trend">Rată ocupare 78%</div>
        </article>

        <article class="kpi-card card-alerta">
            <div class="kpi-valoare">4</div>
            <div class="kpi-titlu">Vehicule în Service</div>
            <div class="kpi-trend trend-negativ">⚠ Revizii depășite</div>
        </article>

        <article class="kpi-card card-personal">
            <div class="kpi-valoare">€ 12K</div>
            <div class="kpi-titlu">Încasări Azi</div>
            <div class="kpi-trend">Vânzări & Închirieri</div>
        </article>
    </section>

    <section class="dashboard-rapoarte">

        <div class="panou-principal">
            <h2>📈 Activitate Flotă (Ultimele 24h)</h2>
            <p>Fluxul mașinilor returnate și predate către clienți.</p>
            <table class="tabel-dashboard" id="tabel-flota">
                <thead>
                <tr>
                    <th class="sortabil" data-prop="ora">Ora</th>
                    <th class="sortabil" data-prop="model">Model Auto</th>
                    <th class="sortabil" data-prop="status">Status Flotă</th>
                    <th class="sortabil" data-prop="client">Client / Agent</th>
                    <th class="sortabil" data-prop="actiune">Acțiune</th>
                </tr>
                </thead>
                <tbody id="corp-tabel-flota">
                </tbody>
            </table>
            <hr style="margin: 30px 0; border: 1px solid #eee;">
            <h2>🗺️ Management Parcare Flotă (Interactiv)</h2>
            <p><i>Funcționalitate Drag & Drop: Trageți mașinile între zone pentru a actualiza statusul.</i></p>

            <div class="statistici-live">
                <span>🟢 Disponibile: <b id="contor-disponibil">3</b></span>
                <span>🔴 În Service: <b id="contor-service">1</b></span>
            </div>

            <div class="container-drag-drop">
                <div class="zona-drop" id="zona-disponibil">
                    <h3>✅ Vehicule în Parcare</h3>
                    <div class="masina-drag" draggable="true" id="auto-1">🚗 Dacia Duster (B-123-ABC)</div>
                    <div class="masina-drag" draggable="true" id="auto-2">🚙 Renault Clio (CJ-99-XYZ)</div>
                    <div class="masina-drag" draggable="true" id="auto-3">🚘 Toyota Corolla (TM-01-TES)</div>
                </div>

                <div class="zona-drop" id="zona-service">
                    <h3>🔧 Trimise în Service</h3>
                    <div class="masina-drag" draggable="true" id="auto-4">🚐 VW Transporter (IS-55-VAN)</div>
                </div>
            </div>
        </div>

        <aside class="panou-secundar">
            <h2>🔔 Notificări Sistem</h2>
            <ul class="lista-notificari">
                <li><strong>08:00</strong> - Contractul de leasing RO-892 a fost semnat cu succes.</li>
                <li><strong>09:15</strong> - RCA expiră mâine pentru 3 autoturisme Dacia Sandero.</li>
                <li><strong>10:30</strong> - Client VIP a solicitat prelungirea închirierii cu 48h.</li>
            </ul>
            <button class="btn-dashboard">Generează Raport Complet</button>
        </aside>
        <aside class="panou-secundar" style="margin-top: 20px;">
            <h2>🔍 Filtrare Detaliată Rapoarte</h2>
            <form id="form-filtrare" action="#" method="get" novalidate>
                <p>Cuvânt cheie: <input type="search" name="cautare" placeholder="Ex: Duster"></p>
                <p>
                    Status contract:
                    <br>
                    <label><input type="radio" name="status" value="activ"> Activ</label>
                    <label><input type="radio" name="status" value="inchis"> Închis</label>
                </p>
                <p>Prioritate afișare: <input type="range" name="prioritate" min="1" max="5" value="3"></p>
                <button type="submit" class="btn-dashboard">Aplică Filtre</button>
            </form>
        </aside>
    </section>

</main>
<script src="jquery-3.7.1.min.js"></script>
<script src="validare.js"></script>
<script src="date_tabele.js"></script>
<script src="tabele.js"></script>
<script src="drag_drop.js"></script>
</body>
</html>