function validateFormTeInfusi() {
   var name = document.getElementById("Nome").value;
   var ingredients = document-getElementById('Ingredienti').value;
   var description = document.getElementById("Descrizione").value;
   var preparation = document.getElementById('Preparazione').value;
   var nameMsgErr=""; var descrMsgErr="";
   var ingrMsgEr=""r; var prepaMsgErr=""; var errori=0;

   if(!name.match(/[a-zA-Z_-]/g)) {
     nameMsgErr = "il nome non pu&ograve; contenere caratteri speciali";
     errori++;
   }
   if(name.length < 5 || name.length > 50) {
     nameMsgErr = "il nome deve contenere almeno 5 caratteri non pi&ugrave; di 50";
     errori++;
   }

   if(ingredients!=""){
     if(ingredients.length < 5 || ingredients.length >500){
       ingrMsgErr="deve contenere almeno 5 caratteri (al massimo 500)";
       errori++;
     }
     if(!ingredients.match(/[a-zA-Z0-9_-,.:;?!]/g)){
       ingrMsgErr="Non pu&ograve; contenere caraterri speciali";
       errori++;
     }
   }

   if(description.length < 5 || descrption.length >500){
     descrMsgErr = "deve contenere almeno 5 caratteri (al massimo 500)";
     errori++;
   }
   if(!descrption.match(/[a-zA-Z0-9_-,.:;?!]/g)){
     descrMsgErr = "Non pu&ograve; contenere caraterri speciali";
     errori++;
   }

   if(preparation!=""){
     if(preparation.length < 5 || preparation.length >500){
       prepaMsgErr="deve contenere almeno 5 caratteri (al massimo 500)";
       errori++;
     }
     if(!preparation.match(/[a-zA-Z0-9_-,.:;?!]/g)){
       prepaMsgErr="Non pu&ograve; contenere caraterri speciali";
       errori++;
     }
   }

   if(errori>0){
     document.getElementById("err_nome").innerHTML = nameMsgErr;
     document.getElementById("err_desc").innerHTML= descrMsgErr;
     document.getElementById("err_ing").innerHTML= ingrMsgErr;
     document.getElementById("err_prepa").innerHTML= prepaMsgErr;
     return false;
   }
   return true;
}


function validateFormEventi() {
  var title = document.getElementById("titolo_evento").value;
  var subtitle1 = document.getElementById("sottotitolo1").value;
  var subtitle2 = document.getElementById("sottotitolo2").value;
  var subtitle3 = document.getElementById("sottotitolo3").value;
  var subtitle4 = document.getElementById("sottotitolo4").value;
  var subtitle5 = document.getElementById("sottotitolo5").value;
  var relators = document.getElementById("relatori").value;
  var address = document.getElementById("mappa_evento").value;
  var descr_address = document.getElementById("desc_mappa_evento").value;
  var organization = document.getElementById("organizzazione_evento").value;

  var err_tit=""; var err_desc=""; var err_rel=""; var err_ind=""; var err_org="";
  var errori = 0;

  if(!title.match(/[^a-zA-Z0-9]/g)) {
    err_tit = "non pu&ograve; contenere caratteri speciali";
    errori++;
  }
  if(name.length < 5 || name.length > 50) {
    err_tit = "deve contenere almeno 5 caratteri non pi&ugrave; di 50";
    errori++;
  }

  if(subtitle1!= "" || subtitle2!= "" || subtitle3!= "" || subtitle4!= "" || subtitle5!= "" ){
    if(subtitle1.length < 5 || subtitle1.length>100 || subtitle2.length < 5 || subtitle2.length>100
    || subtitle3.length < 5 || subtitle3.length>100 || subtitle4.length < 5 || subtitle4.length>100
    || subtitle5.length < 5 || subtitle5.length>100){
      err_desc = "i sottotiloli deve contenere almeno 5 caraterri(massimo 100)";
      errori++;
    }
    if(!subtitle1.match(/[a-zA-Z_-?!;:,""]/g) || !subtitle2.match(/[a-zA-Z_-?!;:,""]/g) ||
        !subtitle3.match(/[a-zA-Z_-?!;:,""]/g) || !subtitle4.match(/[a-zA-Z_-?!;:,""]/g) ||
         !subtitle5.match(/[a-zA-Z_-?!;,:""]/g)){
           err_desc = "i sottotilol non possono contenere caratteri speciali";
           errori++;
         }
  }

  if(relators!=""){
    if(relators.length < 5 || relators.length >500){
      err_rel ="deve contenere almeno 5 caratteri (al massimo 500)";
      errori++;
    }
    if(!relators.match(/[a-zA-Z0-9_-,:]/g)){
      err_rel="Non pu&ograve; contenere caraterri speciali";
      errori++;
    }
  }

  if(!address || !descr_address){
    err_ind = "inserire l'indirizzo e la descrizione dell'evento";
    errori++;
  } else {
    if(address.length <5 || address.length > 50 || !address.match(/[a-zA-Z0-9,-]/g)){
      err_ind = "controllare che sia corretto l'indirizzo";
      errori++;
    }
    if(descr_address.length < 5 || descr_address.length >500  || !descr_address.match(/[a-zA-Z0-9_-,.:;?!]/g)){
      err_ind = "la descrizione dell'indirizzo deve contenere almeno 5 caratteri (al massimo 500) e no caratteri speciali";
      errori++;
    }
  }

  if(organization!=""){
    if(organization.length<5 || organization.length>500 || !organization.match(/[a-zA-Z0-9_-,:]/g)){
      err_org = "deve contenere almeno 5 caratteri (al massimo 500) e no caratteri speciali";
      errori++;
    }
  }

  if(errori>0){
    document.getElementById("err_tit").innerHTML = err_tit;
    document.getElementById("err_desc").innerHTML= err_desc;
    document.getElementById("err_rel").innerHTML= err_rel;
    document.getElementById("err_ind").innerHTML= err_ind;
    document.getElementById("err_org").innerHTML= err_org;
    return false;
  }
  return true;

}
