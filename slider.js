var slider = document.getElementById("time");
var output = document.getElementById("demo2");
output.innerHTML = slider.value; // Display the default slider value
slider.oninput = function() {
  output.innerHTML = this.value;
}

var slider2 = document.getElementById("sug_time");
var output2 = document.getElementById("demo");
output2.innerHTML = slider2.value; // Display the default slider value
slider2.oninput = function() {
  output2.innerHTML = this.value;
}

var slider3 = document.getElementById("distance");
var output3 = document.getElementById("demo3");
output3.innerHTML = slider3.value; // Display the default slider value
slider3.oninput = function() {
  output3.innerHTML = this.value;
}

var geojsonFeature;
var geojson;
var centroid;
var res;
var marker_point;

var openmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Map tiles by <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  minZoom: 0
});
var mymap = L.map('mapid2');
mymap.addLayer(openmap);
var markerGroup = L.layerGroup().addTo(mymap);


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
        style: myStyle1,
        onEachFeature: onEachFeature3
      }).addTo(mymap);

      mymap.fitBounds(geojson.getBounds());

      centroid = turf.centroid(geojsonFeature);
      res = String(centroid.geometry.coordinates).split(",");

      sim_resp2(false);
    },
    error: function(result) {
      alert('error');
    }
  });
});
