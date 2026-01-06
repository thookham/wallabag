import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['input', 'suggestions'];

  connect() {
    this.fetchSuggestions();
  }

  fetchSuggestions() {
    fetch('/api/tags/suggestions')
      .then(response => response.json())
      .then(tags => {
        this.suggestionsTarget.innerHTML = '';
        tags.forEach(tag => {
          const option = document.createElement('option');
          option.value = tag;
          this.suggestionsTarget.appendChild(option);
        });
      });
  }

  toggle() {
    this.element.classList.toggle('hidden');

    if (!this.element.classList.contains('hidden')) {
      this.inputTarget.focus();
    }
  }
}
