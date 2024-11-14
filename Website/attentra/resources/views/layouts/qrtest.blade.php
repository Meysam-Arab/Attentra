<?php
//include "phpqrcode/qrlib.php";
//include(app_path().'\includes\phpqrcode\qrlib.php');
//
//$tempDir = URL::to('/style');
//
//$codeContents = '123456DEMO';
//
//// generating
//QRcode::png($codeContents, $tempDir.'/007_1.png', QR_ECLEVEL_L, 1);
//QRcode::png($codeContents, $tempDir.'/007_2.png', QR_ECLEVEL_L, 2);
//QRcode::png($codeContents, $tempDir.'/007_3.png', QR_ECLEVEL_L, 3);
//QRcode::png($codeContents, $tempDir.'/007_4.png', QR_ECLEVEL_L, 4);
//
//// displaying
//echo '<img src="'.$tempDir.'/007_1.png" />';
//echo '<img src="'.$tempDir.'/007_2.png" />';
//echo '<img src="'.$tempDir.'/007_3.png" />';
//echo '<img src="'.$tempDir.'/007_4.png" />';


$png =QrCode::format('png')->size(256)->geo(37.822214, -122.481769);
//$png = QrCode::format('png')->size(256)->generate(123454321234567234543455);
$png = base64_encode($png);
echo "<img src='data:image/png;base64," . $png . "'>";

