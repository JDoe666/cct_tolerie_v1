import { Controller } from "@hotwired/stimulus";
import { gsap, Power4 } from "gsap";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {
    this.element.addEventListener("click", () => {
      let filterForm = document.querySelector(".form-filter");
      filterForm.classList.toggle("active");

      if (filterForm.classList.contains("active")) {
        gsap.to(".form-filter", {
          height: "auto",
          opacity: 1,
          overflow: "unset",
          ease: Power4.easeOut,
        });
      } else {
        gsap.to(".form-filter", {
          height: 0,
          opacity: 0,
          overflow: "hidden",
          ease: Power4.easeOut,
        });
      }
    });
  }
}
