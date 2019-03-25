<?php

namespace Application\helpers;

use finfo;
class Miscellaneous
{
  public static function getBase64FromFile($file = null)
  {
    if (! empty($file)) {
      $content = base64_encode(file_get_contents($file));
      $fileinfo = new finfo(FILEINFO_MIME_TYPE);
      $mimetype = $fileinfo->file($file);
      $base64 = "data:". $mimetype . ";base64,". $content;
      return $base64;
    }
    return false;
  }

  public static function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen( $output_file, 'wb' ); 
    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp ); 
    return $output_file; 
  }

  public static function word_in_string($text, $word)
  {
    if (strpos($text, $word) !== false) {
      return true;
    }
    return false;
  }
}