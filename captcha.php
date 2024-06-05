<?php 
//loome sessiooni ja lisame sinna suvalise arvu 
session_start(); 
$_SESSION['captchatekst'] = rand(1000,9999); 
//muudab dokumendi pildiks 
header('Content-type: image/jpeg'); 
//omistame sessiooni väärtuse muutujasse 
$tekst = $_SESSION['captchatekst']; 
//teksti parameetrid 
$teksti_suurus = 20; 
$laius = 80; 
$korgus = 40; 
//loome pildi 
$pilt = imagecreate($laius, $korgus); 
//tausta värv 
imagecolorallocate($pilt, 255, 255, 255); 
//teksti värv 
$teksti_varv = imagecolorallocate($pilt, 0, 0, 0); 
//joonistame suvalised jooned 
for ($i=1; $i<=40 ; $i++) {  
	$x1 = rand(1,100); 
	$y1 = rand(1,100); 
	$x2 = rand(1,100); 
	$y2 = rand(1,100); 
	imagesetthickness($pilt, 2); 
	imagedashedline($pilt, $x1, $y1, $x2, $y2, $teksti_varv); 
} 
//lisame pildile soovitud parameetrud
//imagettftext(pilt, teksti_suurus, kalddenurk, x, y, teksti_värv, fondifail, tekst)
imagettftext($pilt, $teksti_suurus, 0, 10, 25, $teksti_varv, 'font.ttf', $tekst);
imagejpeg($pilt);
?>