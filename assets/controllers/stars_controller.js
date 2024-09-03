import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    let etoiles = document.querySelectorAll(".notes span");
    let input = document.querySelector('#user_avis_form_note');
    etoiles.forEach((etoile, pos) => {
      // Si je clique sur une étoile
      etoile.addEventListener("click", () => {
        // J'affecte la position +1 qui correspond a la note
        input.value = pos+1;
        // je vide tout
        videTout();
        // les etoiles jusqua celle que j'ai cliqué deviennent pleine
        for (let i = 0; i <= pos; i++) {
          etoiles[i].innerText = "★";
        }
      });
    });

    // je vide tout

    function videTout() {
      // Role : remplacer toutes les etoiles par des etoiles vides
      for (let i = 0; i < etoiles.length; i++) {
        etoiles[i].innerText = "☆";
      }
    }
  }
}
