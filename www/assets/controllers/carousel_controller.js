import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['wrapper'];

    connect() {
        this.setupCarousel();
    }

    setupCarousel() {
        const track = this.wrapperTarget.querySelector('.carousel-track');
        if (!track) return;

        // Pause/Resume on hover
        this.wrapperTarget.addEventListener('mouseenter', () => {
            track.style.animationPlayState = 'paused';
        });

        this.wrapperTarget.addEventListener('mouseleave', () => {
            track.style.animationPlayState = 'running';
        });
    }
}

