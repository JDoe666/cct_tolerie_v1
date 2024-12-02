import { Controller } from "@hotwired/stimulus";
import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

export default class extends Controller {
    connect() {
        const element = this.element;
        const swiper = new Swiper(element, {
            slidesPerView: 2,
            spaceBetween: 10,
            modules: [Navigation],
            navigation: {
              nextEl: ".button-next-devis",
              prevEl: ".button-prev-devis",
            },
            loop: true,
          });
    }
}