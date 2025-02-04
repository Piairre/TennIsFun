import './bootstrap.js';
import 'daisyui/dist/full.min.css';
import './styles/app.css';


// HomePage swap icon and circuit switch
function initializeSwaps() {
  const iconSwitch = document.getElementById('iconSwitch');
  const circuitSwitch = document.getElementById('circuitSwitch');
  const mainSwap = document.getElementById('mainSwap');

  if (!iconSwitch || !circuitSwitch || !mainSwap) return; // Protection si on n'est pas sur la home

  // Synchronisation de l'icône avec le switch principal
  iconSwitch.addEventListener('change', function() {
    circuitSwitch.checked = this.checked;
  });

  // Synchronisation du switch au clic sur les cards
  mainSwap.addEventListener('click', function() {
    iconSwitch.checked = circuitSwitch.checked;
  });
}

document.addEventListener('turbo:load', initializeSwaps);
