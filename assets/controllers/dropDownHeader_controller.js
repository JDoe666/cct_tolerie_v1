import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    let drops = document.querySelectorAll(".dropdown");

    drops.forEach((drop) => {
      drop.addEventListener("click", (e) => {
        e.stopImmediatePropagation();

        // si y'a deja un truc ouvert je le ferme
        let open = document.querySelector(".sm-open");
        if (open != null) {
          refermeLeSousMenu(open);
        }

        let sm = drop.querySelector(".sous-menu");

        if (sm.classList.contains("sm-open")) {
          refermeLeSousMenu(sm);
          return;
        }
        let enfants = sm.children;
        console.log(enfants);
        let hauteur = 0;
        Array.from(enfants).forEach((enfant) => {
          hauteur += enfant.clientHeight + 16;
        });
        sm.style.height = hauteur - 24 + "px";
        sm.classList.add("sm-open");

        document.body.addEventListener("click", () => {
          refermeLeSousMenu(sm);
        });
      });
    });

    function refermeLeSousMenu(s) {
      s.classList.remove("sm-open");
      s.style.height = "0px";
      document.body.removeEventListener("click", () => {
        refermeLeSousMenu(sm);
      });
      return;
    }

    /**
     * Burger
     */

    const burger = document.querySelector(".burger");
    const lateral = document.querySelector(".menu-lateral");
    burger.addEventListener("click", toggleLateral);
    const mask = document.querySelector(".mask");
    mask.addEventListener("click", closeLateral);

    function toggleLateral() {
      if (lateral.classList.contains("lateral-opened")) {
        closeLateral();
        return;
      }
      lateral.style.transition = "transform 0.6s ease";
      lateral.style.transform = "translateX(0)";
      lateral.classList.add("lateral-opened");
      mask.style.display = "block";
    }

    function closeLateral() {
      lateral.style.transform = "translateX(-300px)";
      lateral.classList.remove("lateral-opened");
      mask.style.display = "none";
    }

    window.addEventListener("resize", () => {
      if (window.innerWidth > 600) {
        closeLateral();
        lateral.style.transform = "translateX(0)";
        mask.style.display = "none";
      }
    });
  }
}
