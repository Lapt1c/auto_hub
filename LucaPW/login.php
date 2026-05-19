<?php
session_start();

require_once 'config_db.php';

$mesaj_eroare = '';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {//daca nu sunt logat dar am bifat anterior remember me
    $token = $_COOKIE['remember_token'];
    $stmt = $conn_pdo->prepare("SELECT * FROM utilizatori WHERE remember_token = :token");
    $stmt->execute(['token' => $token]);//Prepared Statement evitam sql injection
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol'] = $user['rol'];

        // Verificam rolul
        if ($_SESSION['rol'] === 'admin') {
            header("Location: PanouAdministrare.php"); // Adminii merg la panou
        } else {
            header("Location: InchieriVanzari.php");   // Clienții merg la catalog
        }
        exit();
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {///se activaza doar la apasarea butonului
    $username = trim($_POST['username'] ?? '');//trim elimina spatiile de la fial sau inceput
    $password = $_POST['password'] ?? '';/// ??  previne erorile daca campul e gol
    $captcha_input = trim($_POST['captcha'] ?? '');
    $remember = isset($_POST['remember']); // true daca s a bifat casuta

///se verifica captcha
    if (empty($_SESSION['captcha_code']) || strtolower($captcha_input) !== strtolower($_SESSION['captcha_code'])) {
        $mesaj_eroare = "Codul CAPTCHA introdus este incorect!";
    } else {
        $stmt = $conn_pdo->prepare("SELECT * FROM utilizatori WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {///verificarea parolei
        ///password_verify lucreaza cu algoritmul BCRYPT
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];
            if ($remember) {
                $nou_token = bin2hex(random_bytes(32));
                $update_stmt = $conn_pdo->prepare("UPDATE utilizatori SET remember_token = :token WHERE id = :id");
                $update_stmt->execute(['token' => $nou_token, 'id' => $user['id']]);

                setcookie('remember_token', $nou_token, time() + (86400 * 30), "/", "", false, true);
            }

            unset($_SESSION['captcha_code']);//sterge codul captcha vechi
            // Verificam rolul\
            if ($_SESSION['rol'] === 'admin') {
                header("Location: PanouAdministrare.php"); // Adminii merg la panou
            } else {
                header("Location: InchieriVanzari.php");   // Clienții merg la catalog
            }
            exit();
            exit();
        } else {
            $mesaj_eroare = "Nume de utilizator sau parolă incorecte!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Autentificare - AutoHub</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .captcha-container { display: flex; align-items: center; gap: 10px; margin-top: 5px; }
        .btn { width: 100%; padding: 10px; background: #e74c3c; border: none; color: white; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn:hover { background: #c0392b; }
        .eroare { color: #e74c3c; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold; }
        .remember-group { display: flex; align-items: center; gap: 5px; cursor: pointer; }
        .remember-group input { cursor: pointer; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Autentificare Admin</h2>

    <?php if (!empty($mesaj_eroare)): ?>
        <div class="eroare"><?= htmlspecialchars($mesaj_eroare) ?></div>
    <?php endif; ?>

<form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Nume utilizator:</label>
            <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Parolă:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="captcha">Introduceți codul din imagine:</label>
            <div class="captcha-container">
                <img src="captcha.php" alt="CAPTCHA" style="border: 1px solid #ddd; border-radius: 4px;">
                <input type="text" id="captcha" name="captcha" required maxlength="5" style="width: 120px;" autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label class="remember-group">
                <input type="checkbox" name="remember" value="1">
                <span>Ține-mă minte (Remember me)</span>
            </label>
        </div>

        <button type="submit" class="btn">Intră în cont</button>
    </form>
</div>

</body>
</html>