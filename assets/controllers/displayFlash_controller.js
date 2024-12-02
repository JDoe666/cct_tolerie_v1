import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        setTimeout(() => {
            this.fadeOut();
        }, 5000);
    }

    fadeOut() {
        
        this.element.style.transition = "opacity 1s";
        this.element.style.opacity = "0";

        // Supprime l'élément du DOM après la transition
        this.element.addEventListener("transitionend", () => {
            this.element.remove();
        });
    }
}
