import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    let image = this.element;
    let lightBox = document.querySelector(".light-box");
    let imageSrc = image.cloneNode();
    imageSrc.removeAttribute("data-controller");

    image.addEventListener("click", () => {
      // Lorsque je clique sur l'image je veux afficher la lightbox(l'overlay)
      lightBox.children[0].append(imageSrc);
      lightBox.classList.add("light-box-active");
      setTimeout(() => {
        lightBox.style.opacity = 1;
      }, 10);
      // Maintenant que ma lightbox est visible, je veux que le clone de mon image s'affiche a l'écran
      if (lightBox.classList.contains("light-box-active")) {
        lightBox.addEventListener("click", () => {
          lightBox.style.opacity = 0;
          setTimeout(() => {
            lightBox.classList.remove("light-box-active");
          }, 300);
        });
      }
    });
  }
}
