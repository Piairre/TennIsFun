import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    this.liveContainer = document.getElementById('liveContainer');
    this.card = this.liveContainer.querySelector('.card');
    this.cardWidth = this.card ? this.card.offsetWidth : 0;
    this.gap = 16; // (.gap-4), 1rem = 16px
    this.slideWidth = this.cardWidth + this.gap;
    this.currentPosition = 0;
  }

  slideNext() {
    const containerWidth = this.liveContainer.offsetWidth;
    const maxScrollPosition = this.liveContainer.scrollWidth - containerWidth;

    if (this.currentPosition + containerWidth < this.liveContainer.scrollWidth) {
      this.currentPosition = Math.min(this.currentPosition + this.slideWidth, maxScrollPosition);
      this.liveContainer.style.transform = `translateX(-${this.currentPosition}px)`;
    }
  }

  slidePrev() {
    if (this.currentPosition > 0) {
      this.currentPosition = Math.max(this.currentPosition - this.slideWidth, 0);
      this.liveContainer.style.transform = `translateX(-${this.currentPosition}px)`;
    }
  }
}
