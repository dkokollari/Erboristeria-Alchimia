<?php
class Image {
  public function isValid($name){
    $ext_ok = array('jpg', 'jpeg', 'png', 'gif');
    $temp = explode('.',$name);
    $ext = array_pop($temp);
    if(in_array($ext, $ext_ok)){
      return "";
    } else { return '<small class="err_msg">inserisci immagine (.jpg,.jpeg,.png,.gif)</small>';}
  }

  public function uploadImage($name , $temp, $id){
    $dir = "img/te_e_infusi/";
    move_uploaded_file($temp, $dir.$name);
    $split = explode('.',$name);
    $ext = array_pop($split);
    if($id!="errore" && rename($dir.$name,$dir.$id.".".$ext)){
      return true;
    }
    else{
      unlink($dir.$name);
      return false;
    }
  }

}
?>
