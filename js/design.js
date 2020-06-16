// welcome to my personal hell



              /*---------------  VARIABLES AND CONSTANTS DECLARATION   ---------------*/


const headerImg = document.getElementById("header_image");
const content = document.querySelector("#content");
const topbar = document.querySelector("#topbar");
const topbarTitle = document.querySelector("#topbar_title");
const menu = document.querySelector("#menu");
const rightofmenu= document.querySelector("#right-of-menu");
const tips = document.getElementsByClassName("tips_and_tricks");
const card = document.getElementsByClassName("card");


const username = document.querySelector("#username");

const marginTop = document.documentElement.clientWidth*(320-64);  // 75vw di margin-top del #content - 12vw di altezza #topbar
// const marginTopEm = 254;
var multiplier = document.documentElement.clientWidth*0.5;
var x =-1*430/ 698 ;
//percorso = w*3/4 - t
//con 640 -> 668 delta = 28
//con 494 -> 480 delta = -14
    // console.log(document.documentElement.clientWidth);
var yScrollPosition;
var i;
var j;

var menu_btn = document.getElementsByClassName("menu_btn");


document.addEventListener('keydown', function(e) {
  if (e.keyCode === 9) {
    $('body').addClass('show-focus-outlines');
  }
});

document.addEventListener('click', function(e) {
  $('body').removeClass('show-focus-outlines');
});

                    /*---------------    MENU    (mobile)   ---------------*/

/* add an event listener to menu_btn. onclick=""" hada problem with the keyboard navigation (no keyboard on mobile tho, my bad) */
for (i = 0; i < menu_btn.length; i++) {
  menu_btn[i].addEventListener('click', menuToggle);
}


    /*         function that shows/hides the menu (toggle)  and the brightness layer     */
function menuToggle() {
  menu.classList.toggle("showmenu");
  rightofmenu.classList.toggle("hidemenu");
}



$(document).ready(function() {
  if(document.getElementById("username")){
    if($("#username").val() != "") {
      $(username.previousElementSibling).addClass("filled");
    }
  }
});


/*        function that moves the label in the login and register page     */
$("#nome, #cognome, #username, #password, #password_conferma, #data_nascita").on("blur", function() {
$(this.previousElementSibling).removeClass("filled");
if($(this).val() != "") {
$(this.previousElementSibling).addClass("filled");
}
});


      /*---------------    PARALLAX + SCROLL + TOPBAR EFFECT (mobile)   ---------------*/


                    /* adds an event listener to create the parallax effect */
// window.addEventListener("DOMContentLoaded", scrollFix, false);


/* function that actually creates the parallax effect by "slowing" the background movement while scrolling */
// function scrollFix() {
//
//     yScrollPosition = window.scrollY;
//     headerImg.style.transform = "translate3d(" + 0 + ", " +( yScrollPosition*-0.62) + "px, 0)";
//     requestAnimationFrame(scrollFix);
// }
//
//                     /* function that makes the topbar element "sticky" on mobile */
// window.addEventListener('scroll', showTopbar);
//  function showTopbar() {
//     if (window.scrollY > marginTopEm) {
//       topbar.style.zIndex = "2";                                //controllare che gli vada bene usare STYLE (spoiler alert: NON VA BENE => DA CAMBIARE!!!!!!!!!!!!!!!!!)
//       topbarTitle.classList.add("nopacity");
//     } else {
//       topbar.style.zIndex = "-2";
//       topbarTitle.classList.remove("nopacity");
//     }
//  }


                  /*--------------- EXPAND "tips_and_tricks" ELEMENT  ---------------*/

  /* function that adds a different event listener to every "tips_and_tricks" element to make it expandable */
for (i = 0; i < tips.length; i++) {
  tips[i].addEventListener('click', expandCard);
  // tips[i].addEventListener('keydown', (event) => {
  //   if (event.code === 'Space' || event.code === 'Enter') {
  //       event.target.click();
  //     }
  // });
}
                    /* expands said "tips_and_tricks" element */
/*function that enables the display of the hidden tip: first it gotta change the "display:none"property, then make the element visible; (to preserve accessibility and animations) */
// function expandTips() {
//   if (this.classList.contains("display_none")) {
//     this.classList.toggle("display_none");
//     setTimeout(() => {
//        this.classList.toggle("collapsed");
//        this.lastElementChild.classList.toggle("rotated");
//     }, 100);
//     /* I could create a function and pass the classes as parameters... but not now */
//   } else {
//     this.classList.toggle("collapsed");
//     this.lastElementChild.classList.toggle("rotated");
//     setTimeout(() => {
//        this.classList.toggle("display_none");
//     }, 600);
//   }
// }


                        /*---------------  EXPAND "card" ELEMENT  ---------------*/


  /* function that adds a different event listener to every "card" element to make it expandable */
for (j = 0; j < card.length; j++) {
  card[j].addEventListener('click', expandCard);
  card[j].addEventListener('keydown', (event) => {
    if (event.code === 'Space' || event.code === 'Enter') {
        event.target.click();
      }
  });
}
                    /* expands said "card" element */
function expandCard() {
  this.classList.toggle("collapsed");
  this.lastElementChild.classList.toggle("rotated");
}

// 
// document.getElementById("torna_su_btn").addEventListener('click', function () {
//       window.scrollTo(0,0);
// });
//





const immagine_prodotto = document.getElementById("immagine_prodotto");

// console.log(immagine_prodotto);
                    /* adds an event listener to create the parallax effect */


var marginTopEm = calcVar();
function calcVar() {
  x = 256;
  if(document.getElementById("body_scheda_prodotto")){
    x = 320;
  }
  else if(document.getElementsByClassName("home").length > 0){
    x = 544;
  }
  return x;
}
// console.log(marginTopEm);
// console.log(document.getElementsByClassName("home").length);
// window.addEventListener("DOMContentLoaded", calcMarginTop, false);

window.addEventListener("DOMContentLoaded", scrollFixProdotto, false);

// console.log(marginTopEm);
/* function that actually creates the parallax effect by "slowing" the background movement while scrolling */
function scrollFixProdotto() {

    yScrollPosition = window.scrollY;
    immagine_prodotto.style.transform = "translate3d(" + 0 + ", " +( yScrollPosition*-0.62) + "px, 0)";
    requestAnimationFrame(scrollFixProdotto);
}

                    /* function that makes the topbar element "sticky" on mobile */
// window.addEventListener('scroll', showTopbar);
//  function showTopbar() {
//     if (window.scrollY > marginTopEm) {
//       topbar.style.zIndex = "2";                                //controllare che gli vada bene usare STYLE (spoiler alert: NON VA BENE => DA CAMBIARE!!!!!!!!!!!!!!!!!)
//       topbarTitle.classList.add("nopacity");
//     } else {
//       topbar.style.zIndex = "-2";
//       topbarTitle.classList.remove("nopacity");
//     }
//  }
// const marginTopEmProdotto = 320;

// function calcMarginTop() {
//
//   var marginTopEm = 256;
//   if (document.getElementById("body_scheda_prodotto")) {
//     marginTopEm = 320;
//   }
//   console.log(marginTopEm);
// }
// const marginTopEmProdotto = 256;
// window.addEventListener('scroll', showTopbar);
//  function showTopbar() {
//     if (window.scrollY > marginTopEm) {
//       topbar.classList.add("visible_topbar")                        //controllare che gli vada bene usare STYLE (spoiler alert: NON VA BENE => DA CAMBIARE!!!!!!!!!!!!!!!!!)
//       topbarTitle.classList.add("nopacity");
//     } else {
//       topbar.classList.remove("visible_topbar")
//       topbarTitle.classList.remove("nopacity");
//     }
//  }

const scopri = document.getElementById("scopri_il_sito");
const title = document.getElementById("title");
 window.addEventListener('scroll', showTopbar2);
  function showTopbar2() {
     if (window.scrollY > marginTopEm) {
       topbar.classList.add("visible_topbar");
       topbarTitle.classList.add("nopacity");
       if(scopri) scopri.classList.add("hide_header_elements");
       title.classList.add("hide_header_elements");
     } else {
       topbar.classList.remove("visible_topbar")
       topbarTitle.classList.remove("nopacity");
       if(scopri) scopri.classList.remove("hide_header_elements");
       title.classList.remove("hide_header_elements");
     }
  }

/*---------------=======-------VALIDAZIONE FORM LOGIN -----------------------*/

/*
NON VA DC!!!!!!!!!!
document.getElementById("form").addEventListener("submit", (e) => {
  if(!validazioneForm()) {
    e.preventDefault();
  }
});*/


function validazioneForm() {
  var email = document.getElementById("email");
  var password = document.getElementById("password");
  //voglio mostrare un solo errore: per questo se una delle due mostra un errore, non invoco l'altra!
  return checkEmail(email) && checkPassword(password);
}

function checkEmail(emailInput) {
  if(!emailInput.value || emailInput.value.trim().length == 0) {
    const emptyField = 'Inserire sia una email che una password(js)';
    showErrorSecurity(emptyField);
    return false;
  } else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailInput.value.trim()))) {
    const wrongField = 'La email o la password inserite non sono corrette(js)';
    showErrorSecurity(wrongField);
    return false;
  }
  return true;
}

function checkPassword(pwdInput) {
  if(!pwdInput.value || pwdInput.value.trim().length == 0) {
    const emptyField = 'Inserire sia una email che una password(js)';
    showErrorSecurity(emptyField);
    return false;
  } else if(pwdInput.value.trim().length < 6 || pwdInput.value.trim().length > 12
	|| !(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,12}$/.test(emailInput.value.trim()))) {
    const wrongField = 'La email o la password inserite non sono corrette(js)';
    showErrorSecurity(wrongField);
    return false;
  }
  removeErrorSecurity(pwdInput);
  return true;
}

function showErrorSecurity(textError) {
    var form = document.getElementById('log_in_form');
    const errorsShown = form.getElementsByClassName("errore");
    removeErrorSecurity(errorsShown);
    var span = document.createElement("span");
    span.className = "errore";
    span.append(textError);
    form.prepend(span);
    console.log(form);
}

function removeErrorSecurity(errorsToRemove) {
  for(var error of errorsToRemove) {
    error.remove();
  }
}
