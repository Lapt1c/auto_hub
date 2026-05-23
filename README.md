# AutoHub - Platformă de Închirieri și Vânzări Auto 🚗

AutoHub este o aplicație web dinamică dezvoltată pentru gestionarea unui catalog de autovehicule. Proiectul include un sistem complet de autentificare, control al accesului bazat pe roluri (RBAC) și un panou de administrare securizat pentru adăugarea și gestionarea inventarului auto.

## 🛠️ Tehnologii Utilizate

* **Frontend:** HTML5, CSS3, JavaScript (inclusiv jQuery pentru manipularea DOM-ului).
* **Backend:** PHP (arhitectură bazată pe sesiuni).
* **Baze de Date:** * **MySQL** - Baza de date principală (gestiunea utilizatorilor și a catalogului auto).
  * **SQLite** - Bază de date auxiliară (utilizată pentru înregistrarea logurilor și a evenimentelor de sistem).

## ✨ Funcționalități Principale

* **Sistem de Autentificare și Sesiuni:** * Logare securizată cu funcționalitate de "Remember Me" (bazată pe token-uri unice stocate în cookie-uri).
  * Implementare CAPTCHA personalizat pentru prevenirea atacurilor automate tip brute-force.
* **Controlul Accesului pe Roluri (RBAC):**
  * **Administrator (`admin`):** Are acces la Panoul de Administrare, poate adăuga mașini noi în catalog și poate încărca imagini pe server.
  * **Client (`client`):** Este redirecționat automat către vizualizarea catalogului, având accesul restricționat la funcțiile de modificare a bazei de date.
* **Catalog Auto Dinamic:** Preluarea și afișarea în timp real a vehiculelor din baza de date MySQL, utilizând interogări de tip `JOIN` pentru a asocia mașina cu utilizatorul care a adăugat-o.
* **Gestiunea Fișierelor (Upload):** Formular de administrare care permite încărcarea și salvarea securizată a imaginilor pe server (în directorul `/uploads`).
* **Precompletare Inteligentă a Formularelor:** Extragerea informațiilor din baza de date principală (MySQL) și cea secundară (SQLite) pentru a precompleta dinamic setările de profil ale utilizatorului conectat.

## 🔒 Securitate și Bune Practici

Aplicația a fost dezvoltată punând accent pe securitatea datelor, implementând următoarele mecanisme de apărare:

* **Protecție SQL Injection:** Toate interogările către baza de date utilizează **Prepared Statements** (prin extensia `PDO`), separând strict logica SQL de datele introduse de utilizatori.
* **Securitatea Parolelor:** Parolele nu sunt stocate în clar. Se utilizează algoritmul de hashing **BCRYPT** (`password_hash` și `password_verify`).
* **Protecție Cross-Site Scripting (XSS):** Toate datele provenite de la utilizatori sunt igienizate la afișare folosind funcția `htmlspecialchars()`, prevenind execuția scripturilor malițioase în browser.

## 🚀 Instalare și Configurare (Local via XAMPP)

1. **Copierea fișierelor:** Plasați folderul proiectului în directorul rădăcină al serverului local (ex: `C:\xampp\htdocs\AutoHub`).
2. **Pornirea serverului:** Deschideți XAMPP Control Panel și porniți modulele **Apache** și **MySQL**.
3. **Configurarea bazei de date:**
   * Accesați `http://localhost/phpmyadmin`.
   * Creați o bază de date numită `autohub_db`.
   * Importați structura tabelelor (`utilizatori` și `masini`) utilizând scriptul SQL aferent proiectului. Asigurați-vă că tabelul `masini` are constrângerea de cheie străină (Foreign Key) setată cu `ON DELETE CASCADE` către tabelul `utilizatori`.
4. **Rularea aplicației:** Deschideți browserul și accesați `http://localhost/AutoHub/login.php`.

## 📂 Structura Bazei de Date (Relație 1:M)

Baza de date relațională este construită eficient, integrând o relație de tip **One-to-Many**:
* Tabelul `utilizatori` stochează credențialele, hash-urile parolelor și rolurile din sistem.
* Tabelul `masini` stochează detaliile vehiculelor și include coloana `adaugat_de` (Foreign Key legată de ID-ul utilizatorului corespondent).

---
*Proiect realizat în cadrul laboratorului de Programare Web.*
