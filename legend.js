  var geojsonFeature;
  var geojson;
  var centroid;
  var res;
  var inner_radius = 1500;
  var mid_radius = 3000;
  var outer_radius = 5500;
  var info;
  var circle1;
  var circle2;
  var centroid1;

  var openmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map tiles by <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    minZoom: 0
  });
  var mymap = L.map('mapid');
  mymap.addLayer(openmap);

  $(document).ready(function() {
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

        mymap.fitBounds(geojson.getBounds());

        centroid = turf.centroid(geojsonFeature);
        res = String(centroid.geometry.coordinates).split(",");

      },
      error: function(result) {
        alert('error');
      }
    });

    info = L.control({
      position: 'bottomleft'
    });

    info.onAdd = function(mymap) {
      this._div = L.DomUtil.create('div', 'info');
      this.update();
      return this._div;
    };

    info.update = function(polyg) {
      this._div.innerHTML = '<h4>Πληθυσμός και ID</h4>' + (polyg ?
        '<b>ID: ' + polyg.id + '</b><br />' + polyg.population + ' κάτοικοι' :
        'Τοποθετήστε τον δείκτη πάνω σε κάποιο σκιασμένο σημείο');
    };

    info.addTo(mymap);
  });
