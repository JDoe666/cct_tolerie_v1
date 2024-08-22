import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('change', (e) => {
            const img = e.currentTarget.files[0];
            if (img) {
                const fileReader = new FileReader();
                const preview = document.createElement('img');
                const parent = e.currentTarget.closest('.vich-image');
                fileReader.onload = (e) => {
                    preview.src = e.target.result;
                    if (parent.querySelector('img')) {
                        parent.querySelector('img').remove();
                    }
                    parent.append(preview);
                }
                fileReader.readAsDataURL(img);
                
            }
        });
    }
}
