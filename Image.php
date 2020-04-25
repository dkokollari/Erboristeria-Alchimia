<?php
class Image {
  public function isValid($name){
    if(checkExt($name)){
      return "";
    }
    else{
      return '<small class="err_msg">inserisci immagine (.jpg,.jpeg,.png,.gif)</small>';
    }
  }

  public function uploadImageTeInfusi($name , $temp, $id){
   if($id!="errore"){
      $dir = "img/te_e_infusi/";
      move_uploaded_file($temp, $dir.$name);
      $split = explode('.',$name);
      $ext = array_pop($split);
      if(rename($dir.$name,$dir.$id.".".$ext)){
        return true;
      } else {
        deleteImage($dir.$name);
        return false;
      }
    }
    return false;
  }

  public function deleteImage($file){
    unlink($file);
  }

  public function getImage($path, $id){
    $result = glob($path.$id.".{jpg,jpeg,png,gif}", GLOB_BRACE);
    return ($result[0] ? $result[0]: $path."0.jpg");
  }

  /* controlla se il nome inserito ha un'estensione approvata (guardare $ext_ok) */
  private function checkExt($name){
    $ext_ok = array('jpg', 'jpeg', 'png', 'gif');
    $temp = explode('.',$name);
    $ext = array_pop($temp);
    return in_array($ext, $ext_ok);
  }
}
?>
