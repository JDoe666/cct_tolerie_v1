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
      const dropdown = this.element.closest(".dropdown-pagination");
      dropdown.classList.toggle("active");
      const dropdownContent = dropdown.querySelector(
        ".dropdown-pagination-content"
      );
      if (dropdown.classList.contains("active")) {
        gsap.to(dropdownContent, {
          height: "auto",
          opacity: 1,
          overflow: "unset",
          ease: Power4.easeOut,
        });
      } else {
        gsap.to(dropdownContent, {
          height: 0,
          opacity: 0,
          overflow: "hidden",
          ease: Power4.easeOut,
        });
      }
    });
  }
}
