window.gOverride = {
  gColor: '#EEEEEE',
  gColumns: 12,
  gOpacity: 0.35,
  gWidth: 18,
  pColor: '#C0C0C0',
  pHeight: 18,
  pOffset: 2,
  pOpacity: 0.55,
  center: true,
  gEnabled: false,
  pEnabled: true,
  setupEnabled: true,
  fixFlash: true,
  size: 950
};

create_gridder = function() {
	document.body.appendChild(document.createElement('script')).src='http://gridder.andreehansson.se/releases/latest/960.gridder.js';
}

$(document).ready(function() {
	create_gridder();
});