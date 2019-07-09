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
  info.update(layer.feature.properties);
}

function resetHighlight(e) {
  geojson.resetStyle(e.target);
  info.update();
}

function zoomToFeature(e) {
  mymap.fitBounds(e.target.getBounds());
}

function onEachFeature2(feature, layer) {
  layer.on({
    mouseover: highlightFeature,
    mouseout: resetHighlight,
    click: aler
  });
}

// var template = '<p>Hello world!<br />This is a nice popup.</p>';
var template = '<form>\
<div class="form-group">\
<label for="input-spots">Num of spots:</label>\
  <input class="form-control"  id="input-spots" class="popup-input" type="number" min="0"/>\
  </div>\
  <br><br>\
  <div class="form-group">\
<label for="zones-select">Choose a zone:</label>\
  <select class="form-control" id="zones-select" name="zones-select">\
    <option value="default">Default</option>\
    <option value="kentro">Center</option>\
    <option value="home">Home</option>\
    <option value="steady">Steady</option>\
  </select>\
  </div>\
  <br><br>\
  <button class="form-group" id="button-submit" type="button">Save Changes</button>\
</form>';

function aler(e) {

  mymap.fitBounds(e.target.getBounds());

  var choice = null;
  var spots = null;
  var buttonSubmit = null;
  var marker = null;
  var properties = null;
  var inputSpots = null;
  var id = null;
  var zone;
  // alert('asdsdad');
  marker = e.target,
    properties = e.target.feature.properties;

    if (marker.hasOwnProperty('_popup')) {
      marker.unbindPopup();
    }

  marker.bindPopup(template);
  marker.openPopup();

  // var spots;
  id = properties.id;
  inputSpots = L.DomUtil.get('input-spots');
  spots = inputSpots.value;
  L.DomEvent.addListener(inputSpots, 'change', function(e) {
    spots = e.target.value;
  });


  zone = L.DomUtil.get('zones-select');
  choice = zone.value;
  // choice.value = properties.speed;
  L.DomEvent.addListener(zone, 'change', function(e) {
    choice = e.target.value;
  });

  buttonSubmit = L.DomUtil.get('button-submit');
  L.DomEvent.addListener(buttonSubmit, 'click', function(e) {
    marker.closePopup();
    // alert(choice);
    // alert(spots);

    $.ajax({
      type: "POST",
      url: "/test.php",
      data: {
        id: id,
        choice: choice,
        spots: spots
      },
      success: function(result) {

        // alert("Zones are now loaded on the DB");
        alert(result);

      },
      error: function(result) {
        alert('error');
      }
    });

  });
}
