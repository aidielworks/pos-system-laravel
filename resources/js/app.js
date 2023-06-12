import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'

import Chart from 'chart.js/auto';

window.Alpine = Alpine;
Alpine.plugin(focus)
Alpine.start();
window.Chart = Chart;

