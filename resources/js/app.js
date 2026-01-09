import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Only start Alpine if Livewire is not present, as Livewire 3 bundles and starts Alpine itself.
// This prevents the "Detected multiple instances of Alpine running" warning.
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Livewire === 'undefined') {
        Alpine.start();
    }
});
