var myStyle = {
  'fillColor': "#909396",
  'fillOpacity': 0.45,
  "color": "black",
  "weight": 4,
  "opacity": 0.55
};

var myStyle1 = {
  'fillColor': "#909396",
  'fillOpacity': 0.35,
  "color": "black",
  "weight": 2,
  "opacity": 0.35
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
  if (e.target.colo !== 'changed') {
    geojson.resetStyle(e.target);
  } else {
    e.target.setStyle({
      fillColor: e.target.colori,
      'fillOpacity': 0.45,
      "color": "black",
      "weight": 4,
      "opacity": 0.55
    });
  }
  info.update();
}

function highlightFeature2(e) {
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

function resetHighlight2(e) {
  if (e.target.colo !== 'changed') {
    geojson.resetStyle(e.target);
  } else {
    e.target.setStyle({
      fillColor: e.target.colori,
      'fillOpacity': 0.35,
      "color": "black",
      "weight": 2,
      "opacity": 0.35
    });
  }
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

function onEachFeature3(feature, layer) {
  layer.on({
    mouseover: highlightFeature2,
    mouseout: resetHighlight2,
    click: aler2
  });
}

function aler2(e) {

  marker = e.target,
    properties = e.target.feature.properties;
  markerGroup.clearLayers();
  marker_point = L.marker(e.latlng).on('click', onClickMarker).addTo(markerGroup);

}

function onClickMarker(e) {
  if (marker_point !== null) {
    markerGroup.clearLayers();
    marker_point = null;
  }
}

var template = '<form>\
<div class="form-group">\
<label for="input-spots">Αριθμός χώρων στάθμευσης:</label>\
  <input class=""  id="input-spots" class="popup-input" type="number" min="0"/>\
  </div>\
  <div class="">\
<label for="zones-select">Διαλέξτε μια ζώνη:</label>\
  <select class="form-control form-control-sm" id="zones-select" name="zones-select">\
    <option value="default">Προεπιλεγμένη</option>\
    <option value="kentro">Κέντρο</option>\
    <option value="home">Κατοικίες</option>\
    <option value="steady">Σταθερή</option>\
  </select>\
  </div>\
  <br><br>\
  <button class="btn btn-dark btn-block" id="button-submit" type="button">Αποθήκευση Αλλαγών</button>\
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
  var spots;
  marker = e.target,
    properties = e.target.feature.properties;

  if (marker.hasOwnProperty('_popup')) {
    marker.unbindPopup();
  }

  marker.bindPopup(template);
  marker.openPopup();

  id = properties.id;
  inputSpots = L.DomUtil.get('input-spots');

  L.DomEvent.addListener(inputSpots, 'change', function(e) {
    spots = e.target.value;
  });
  spots = inputSpots.value;

  zone = L.DomUtil.get('zones-select');
  choice = zone.value;
  L.DomEvent.addListener(zone, 'change', function(e) {
    choice = e.target.value;
  });

  buttonSubmit = L.DomUtil.get('button-submit');
  L.DomEvent.addListener(buttonSubmit, 'click', function(e) {
    marker.closePopup();

    var response = "";

    $.ajax({
      type: "POST",
      url: "/edit_pol.php",
      data: {
        id: id,
        choice: choice,
        spots: spots
      },
      success: function(result) {

        alert(result);
        response = result;
        if (!result.includes("No change made for polygon with ID:")) {
          $("#action").prop("disabled", false);
          $("#time").prop("disabled", false);
          $("#step").prop("disabled", false);
          $("#next").css("display", "none");
          $("#previous").css("display", "none");
          $("#reset").css("display", "none");
        }

      },
      error: function(result) {
        alert('Υπήρξε κάποιο σφάλμα');
      }
    });
    $.ajax({
      type: "POST",
      url: "/resp.php",
      dataType: 'json',
      data: {
        registration: "success",
        name: "xyz",
        email: "abc@gmail.com"
      },
      success: function(result) {

        mymap.eachLayer(function(layer) {
          mymap.removeLayer(layer);
        });
        mymap.addLayer(openmap);

        geojsonFeature = jQuery.parseJSON(result.abc);
        geojson = L.geoJson(geojsonFeature, {
          style: myStyle,
          onEachFeature: onEachFeature2
        }).addTo(mymap);

      },
      error: function(result) {
        alert('Υπήρξε κάποιο σφάλμα');
      }
    });
  });
}


$("#circles").click(function(e) {
  e.preventDefault();

  if (geojson !== null) {
    if (!mymap.hasLayer(circle1)) {
      centroid1 = L.geoJSON(centroid).addTo(mymap);

      circle1 = L.circle([res[1], res[0]], {
        radius: outer_radius,
        'fillColor': "#d5dce6",
        'fillOpacity': 0.35,
        "color": "black",
        "weight": 4,
        "opacity": 0.55
      }).addTo(mymap);

      circle2 = L.circle([res[1], res[0]], {
        radius: mid_radius,
        'fillColor': "#8296b5",
        'fillOpacity': 0.55,
        "color": "black",
        "weight": 4,
        "opacity": 0.55
      }).addTo(mymap);

      circle3 = L.circle([res[1], res[0]], {
        radius: inner_radius,
        'fillColor': "#8296b5",
        'fillOpacity': 0.75,
        "color": "black",
        "weight": 4,
        "opacity": 0.55
      }).addTo(mymap);

      $("#circles").html('Απόκρυψη Ζωνών');

    } else {
      mymap.removeLayer(circle1);
      mymap.removeLayer(circle2);
      mymap.removeLayer(circle3);
      mymap.removeLayer(centroid1);
      $("#circles").html('Δείξε Ζώνες');

    }
  }
});

$("#zones").click(function(e) {
  e.preventDefault();

  var zones = [];
  var spots = [];
  var ids = [];

  if (geojsonFeature !== null) {

    for (var i = 0; i < geojsonFeature.features.length; i++) {
      var properties = geojsonFeature.features[i].properties;
      ids[i] = properties.id
      var population = properties.population;
      var centre = properties.centroid;
      var latlng_point = L.latLng(res[1], res[0]);
      var latlng_centroid = L.latLng(centre.coordinates[1], centre.coordinates[0]);

      var dist = latlng_point.distanceTo(latlng_centroid);
      if (dist <= 1500) {
        zones[i] = 'kentro';
        var coef = 1;
      } else if (dist > 1500 && dist <= 3000) {
        zones[i] = 'home';
        var coef = 2;
      } else {
        zones[i] = 'steady';
        var coef = 3;
      }

      spots[i] = Math.ceil(coef * (population / 3));
    }
  }

  var jsonZones = JSON.stringify(zones);
  var jsonSpots = JSON.stringify(spots);
  var jsonIDs = JSON.stringify(ids);
  $.ajax({
    type: "POST",
    url: "/resp2.php",
    data: {
      registration: "success",
      zones: jsonZones,
      spots: jsonSpots,
      ids: jsonIDs
    },
    success: function(result) {

      alert(result);
    },
    error: function(result) {
      alert('error');
    }
  });

  $.ajax({
    type: "POST",
    url: "/resp.php",
    dataType: 'json',
    data: {
      registration: "success",
      name: "xyz",
      email: "abc@gmail.com"
    },
    success: function(result) {

      geojsonFeature = jQuery.parseJSON(result.abc);
      geojson = L.geoJson(geojsonFeature, {
        style: myStyle,
        onEachFeature: onEachFeature2
      }).addTo(mymap);

    },
    error: function(result) {
      alert('Υπήρξε κάποιο σφάλμα');
    }
  });
});
