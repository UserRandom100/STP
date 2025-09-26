  // Appliquer la transition d'entrée lors du retour ou du chargement de la page
  window.addEventListener('load', function() {
    setTimeout(function () {
        window.location.href ="?action=seConnecter"; // Naviguer vers la nouvelle page
      }, 1000);
  });