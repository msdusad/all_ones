<?php
//imagecopymerge($image1, $image2, 0, 0, 75, 75, 150, 150, 50);


function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){ 
    $cut = imagecreatetruecolor($src_w, $src_h);
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct); 
}
$image1 = imagecreatefrompng('sec.png'); //300 x 300
$image2 = imagecreatefrompng('mala.png'); //150 x 150
$merged_image = imagecreatetruecolor(300, 300);
imagealphablending($merged_image, false);
imagesavealpha($merged_image, true);

imagecopy($merged_image, $image1, 0, 0, 0, 0, 300, 300);
imagecopymerge_alpha($merged_image, $image2, 0, 0, -100, -150, 500, 500, 50);

header('Content-Type: image/png');
imagepng($merged_image);
?>

