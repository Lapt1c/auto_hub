<?php
// captcha.php
session_start();

$cod_captcha = substr(md5(rand()), 0, 5);


$_SESSION['captcha_code'] = $cod_captcha;

$imagine = imagecreate(120, 40);


$culoare_fundal = imagecolorallocate($imagine, 52, 73, 94);
$culoare_text   = imagecolorallocate($imagine, 236, 240, 241);

for ($i = 0; $i < 5; $i++) {
    $culoare_linie = imagecolorallocate($imagine, rand(100, 255), rand(100, 255), rand(100, 255));
    imageline($imagine, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $culoare_linie);
}

imagestring($imagine, 5, 35, 12, $cod_captcha, $culoare_text);


header('Content-type: image/png');
imagepng($imagine);

imagedestroy($imagine);
?>