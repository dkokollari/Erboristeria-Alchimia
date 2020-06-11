<?php
class Image {
  public function isValid($name) {
    $ext_ok = array('jpg', 'jpeg', 'png', 'gif');
    $temp = explode('.',$name);
    $ext = array_pop($temp);
    if(in_array($ext, $ext_ok)) return "";
    else return '<small class="err_msg">inserisci immagine (.jpg,.jpeg,.png,.gif)</small>';
  }

  public function uploadImageTeInfusi($name , $temp, $id) {
   if($id!="errore") {
      $dir = "img/te_e_infusi/";
      if(file_exists($dir.$id."jpg")) {
          deleteImage($dir.$id."jpg");
      }
      move_uploaded_file($temp, $dir.$name);
      $split = explode('.',$name);
      $ext = array_pop($split);
      if(rename($dir.$name,$dir.$id.".".$ext)) return true;
      else {
        deleteImage($dir.$name);
        return false;
      }
    } // end if $id!="errore"
    return false;
  }

  public function deleteImage($file) {
    if(file_exists($file)) unlink($file);
  }
  /* ritorna, se esiste, il path completo dell'immagine con estensione jpg/jpeg/png/gif */
  public function getImage($path, $id) {
    $result = glob($path.$id.".{jpg,jpeg,png,gif}", GLOB_BRACE);
    return ($result[0] ? $result[0] : $path."0.jpg");
  }
}
?>
