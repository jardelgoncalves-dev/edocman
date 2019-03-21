// function myFunction() {
//   var x = document.getElementById("myTopnav");
//   if (x.className === "topnav") {
//     x.className += " responsive";
//   } else {
//     x.className = "topnav";
//   }
// }

try {
  var modal = document.getElementById('myModal');
  document.getElementById("modal-show").addEventListener('click', () => {
    modal.style.display = "block";
  });
  document.getElementsByClassName("close")[0].addEventListener('click', () => {
    modal.style.display = "none";
  });
  window.addEventListener('click', (event) => {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  });
} catch(err) {}

