var myStyle = {
  'fillColor': "#909396",
  'fillOpacity': 0.45,
  "color": "black",
  "weight": 4,
  "opacity": 0.55
};

var myStyle2 = {
  'fillColor': "#3dbf4c",
  'fillOpacity': 0.45,
  "color": "black",
  "weight": 4,
  "opacity": 0.55
};

var myStyle3 = {
  'fillColor': "#f2fa02",
  'fillOpacity': 0.55,
  "color": "black",
  "weight": 4,
  "opacity": 0.55
};

var myStyle4 = {
  'fillColor': "#fa0202",
  'fillOpacity': 0.55,
  "color": "black",
  "weight": 4,
  "opacity": 0.55
};


function highlightFeature(e) {
  var layer = e.target;

  layer.setStyle({
    weight: 5,
    color: '#666',
    dashArray: '',
    fillOpacity: 0.7
  });

  if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
    layer.bringToFront();
  }
}

function resetHighlight(e) {
  geojson.resetStyle(e.target);
}

function zoomToFeature(e) {
  mymap.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
  layer.on({
    mouseover: highlightFeature,
    mouseout: resetHighlight,
    click: zoomToFeature
  });
}
