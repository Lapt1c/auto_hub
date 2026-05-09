<?php
// captcha.php
session_start(); // Esențial: pornim sesiunea pentru a salva codul

// Generăm un cod aleatoriu din 5 caractere
$cod_captcha = substr(md5(rand()), 0, 5);

// Salvăm codul generat în sesiune pentru validarea ulterioară din formular
$_SESSION['captcha_code'] = $cod_captcha;

// Creăm o imagine de 120px lățime și 40px înălțime
$imagine = imagecreate(120, 40);

// Alocăm culorile (prima culoare alocată devine automat fundalul)
$culoare_fundal = imagecolorallocate($imagine, 52, 73, 94);  // Gri-albastru închis
$culoare_text   = imagecolorallocate($imagine, 236, 240, 241); // Alb-gri deschis

// Desenăm 5 linii aleatorii pentru a crea "zgomot" (bruiaj)
for ($i = 0; $i < 5; $i++) {
    $culoare_linie = imagecolorallocate($imagine, rand(100, 255), rand(100, 255), rand(100, 255));
    imageline($imagine, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $culoare_linie);
}

// Desenăm șirul de text pe imagine
imagestring($imagine, 5, 35, 12, $cod_captcha, $culoare_text);

// Setăm header-ul HTTP pentru a informa browserul că trimitem o imagine PNG, nu text/html
header('Content-type: image/png');
imagepng($imagine);

// Eliberăm memoria alocată imaginii
imagedestroy($imagine);
?>