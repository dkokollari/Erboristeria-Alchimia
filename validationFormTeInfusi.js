function validateFormTeInfusi() {
   var name = document.forms["form_teinfusi"]["Nome"].value;
   var description = document.forms["form_teinfusi"]["Descrizione"].value;
   var nameMsgErr = "", descrMsgErr = "";

   if(!name.match(/[^a-zA-Z0-9]/g)) nameMsgErr = "il nome non pu&ograve; contenere caratteri speciali";
   if(name.length < 5 || name.length > 50) nameMsgErr = "il nome deve contenere almeno 5 caratteri non pi&ugrave; di 50";
   document.getElementById("err_nome").innerHTML = nameMsgErr;

   if(descrption.length < 20  ||  descrption.length > 500) descrMsgErr = "la descrizione deve contenere almeno 20 caratteri non pi&ugrave; di 500";
   document.getElementById("err_desc").innerHTML= descrMsgErr;
}
