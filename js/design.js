// welcome to my personal hell <3




              /*---------------  VARIABLES AND CONSTANTS DECLARATION   ---------------*/



const headerImg = document.getElementById("header_image");
const content = document.querySelector("#content");
const topbar = document.querySelector("#topbar");
const topbarTitle = document.querySelector("#topbar_title");
const menu = document.querySelector("#menu");
const rightofmenu= document.querySelector("#right-of-menu");
const tips = document.getElementsByClassName("tips_and_tricks");
const card = document.getElementsByClassName("card");
const marginTop = document.documentElement.clientWidth*0.63;  // 75vw di margin-top del #content - 12vw di altezza #topbar
var yScrollPosition;
var i;
var j;





                    /*---------------    MENU    (mobile)   ---------------*/


    /*         function that shows/hides the menu (toggle)  and the brightness layer     */
function showMenu() {
  menu.classList.toggle("showmenu");
  rightofmenu.classList.toggle("hidemenu");
}


      /*---------------    PARALLAX + SCROLL + TOPBAR EFFECT (mobile)   ---------------*/


                    /* adds an event listener to create the parallax effect */
window.addEventListener("DOMContentLoaded", scrollFix, false);

/* function that actually creates the parallax effect by "slowing" the background movement while scrolling */
function scrollFix() {
    yScrollPosition = window.scrollY;
    headerImg.style.transform = "translate3d(" + 0 + ", " + yScrollPosition*-0.45 + "px, 0)";
    requestAnimationFrame(scrollFix);
}

                    /* function that makes the topbar element "sticky" on mobile */
 window.addEventListener('scroll', showTopbar);
 function showTopbar() {
    if (document.documentElement.scrollTop > marginTop) {
      topbar.style.zIndex = "2";                                //controllare che gli vada bene usare STYLE (spoiler alert: NON VA BENE)
      topbarTitle.classList.add("nopacity");
    } else {
      topbar.style.zIndex = "-2";
      topbarTitle.classList.remove("nopacity");
    }
 }


                  /*---------------  EXPAND "tips_and_tricks" ELEMENT  ---------------*/


  /* function that adds a different event listener to every "tips_and_tricks" element to make it expandable */
for (i = 0; i < tips.length; i++) {
  tips[i].addEventListener('click', expandTips);
  tips[i].addEventListener('keydown', (event) => {
    if (event.code === 'Space' || event.code === 'Enter') {
        event.target.click();
      }
  });
}
                    /* expands said "tips_and_tricks" element */

function expandTips() {
  this.classList.toggle("collapsed");
  this.lastElementChild.classList.toggle("rotated");
}


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








         /*--------------- OLD CODE WITH OLD CSS PERSPECTIVE PARALLAX ---------------*/



// const titolo = document.getElementById("titolo");                // titolo in corsivo
// const titlebar = document.getElementById("topbar_title");        // titolo nella barra in alto
// const topvar = document.getElementById("topbar");                // barra in alto da fissare
//
// const scro = document.getElementById("content");


//         INAFFIDABILE, OGNI TANTO LAGGA
// $(document).ready(function() {
//      $('#wrapper').scroll(function(event) {
//          var scroll = scro.scrollTop;
//          let opacity = 1 - (scroll / 200);
//          if (opacity > -0.1) {
//              $('#titolo').css('opacity', opacity);
//          }
//          if (-opacity < 1) {
//              $('#topbar_title').css('opacity', 1-opacity);
//          }
//
//      });
//  });

// scro.addEventListener('scroll', topbar);
// function topbar() {
//    scro.scrollTop > 210 ? topvar.classList.remove("hide") : topvar.classList.add("hide");
//    scro.scrollTop > 20  ? titolo.classList.add("nopacity") : titolo.classList.remove("nopacity");
//    scro.scrollTop > 20  ? titlebar.classList.add("opacity") : titlebar.classList.remove("opacity");
// }


// function expand(){
//   this.firstElementChild.lastElementChild.classList.toggle("expandedcard");
//   this.firstElementChild.firstElementChild.classList.toggle("expandedimg");
//   this.lastElementChild.classList.toggle("rotate");
// }
