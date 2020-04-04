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


const username = document.querySelector("#username");

const marginTop = document.documentElement.clientWidth*0.63;  // 75vw di margin-top del #content - 12vw di altezza #topbar
var yScrollPosition;
var i;
var j;





                    /*---------------    MENU    (mobile)   ---------------*/
// console.log("design.js successfully loaded");
// console.log(formField);

    /*         function that shows/hides the menu (toggle)  and the brightness layer     */
function showMenu() {
  menu.classList.toggle("showmenu");
  rightofmenu.classList.toggle("hidemenu");
}



$( document ).ready(function() {
    if($("#username").val() != "") {
      $(username.previousElementSibling).addClass("filled");
    }
});
    /*        function that moves the label in the login page     */
$("#username, #password").on("blur", function() {
  $(this.previousElementSibling).removeClass("filled");
  if($(this).val() != "") {
    $(this.previousElementSibling).addClass("filled");
  }
});


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
