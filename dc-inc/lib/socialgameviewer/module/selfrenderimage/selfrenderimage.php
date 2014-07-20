<?php
function render($player, $settings) {
    $my_img = imagecreate( 280, 70 );
    $background = imagecolorallocate( $my_img, 0, 0, 255 );
    $text_color = imagecolorallocate( $my_img, 255, 255, 0 );
    $line_color = imagecolorallocate( $my_img, 128, 255, 0 );
    imagestring( $my_img, 4, 30, 25, $player['nickname'],
        $text_color );
    imagesetthickness ( $my_img, 5 );
    imageline( $my_img, 30, 45, 165, 45, $line_color );

    header( "Content-type: image/png" );
    imagepng( $my_img );
    imagecolordeallocate( $line_color,3);
    imagecolordeallocate( $text_color,4);
    imagecolordeallocate( $background,5);
    imagedestroy( $my_img );
}