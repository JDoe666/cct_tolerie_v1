import { Controller } from "@hotwired/stimulus";
import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

export default class extends Controller {
  connect() {
    const element = this.element;
    const swiper = new Swiper(element, {
      // configure Swiper to use modules
      modules: [Navigation],
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      loop: true,
    });
  }
}
