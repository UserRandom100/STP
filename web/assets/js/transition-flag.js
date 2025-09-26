// Transition lors du changement de page (sortie)
document.querySelector('.transition-link-flag').addEventListener('click', function(e) {
    e.preventDefault();
  
    // Appliquer la classe pour activer la transition de sortie sur l'élément .container
    let container = document.getElementsByClassName("container")[0]; 
    if (container) {
      container.classList.add('page-exit');
    }
  
    // Appliquer la classe pour activer la transition de sortie sur l'élément .flag-container
    let container2 = document.getElementsByClassName("flag-container")[0];
    if (container2) {
      container2.classList.add('page-exit');
    }
  
    // Attendre la fin de la transition avant de charger la nouvelle page
    setTimeout(function () {
      window.location.href = e.target.href; // Naviguer vers la nouvelle page
    }, 1000); // 1000 ms pour correspondre à la durée de la transition
  });
  
  // Appliquer la transition d'entrée lors du retour ou du chargement de la page
  window.addEventListener('load', function() {
    // Appliquer la classe de transition "page-enter" lorsque la page est complètement chargée
    let container = document.getElementsByClassName("container")[0]; 
    if (container) {
      container.classList.add('page-enter'); // Appliquer la transition d'entrée
    }
  
    // Appliquer la classe "page-enter" sur flag-container si nécessaire
    let container2 = document.getElementsByClassName("flag-container")[0];
    if (container2) {
      container2.classList.add('page-enter');
    }
  });
  