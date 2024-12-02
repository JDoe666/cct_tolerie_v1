import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const modalContainer = document.querySelector('.modal-container');
        const modalTrigger = document.querySelectorAll('.modal-trigger');
        const buttonYes = modalContainer.querySelector('#yes');
        this.element.addEventListener('submit', (e) => {
            e.preventDefault();
            modalContainer.classList.toggle('active');

            modalTrigger.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    modalContainer.classList.remove('active');
                })
            });
            buttonYes.addEventListener('click', () => {
                this.element.submit();
            })
        })
    }
}