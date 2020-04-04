<?php
class Image {
  public function isValid($name){
    $ext_ok = array('jpg', 'jpeg', 'png', 'gif');
    $temp = explode('.',$name);
    $ext = array_pop($temp);
    if(in_array($ext, $ext_ok)){
      return true;
    } else { return false;}
  }

  public function uploadImage($name , $temp, $id){
    $dir = "img/te_e_infusi/";
    move_uploaded_file($temp, $dir.$name));
    if(rename($dir.$name,$dir.$id.".jpg")){
      return true;
    }
    return false;

  }






}





 ?>
