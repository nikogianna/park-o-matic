function sim_resp2(flag, form = "#form_time") {

  var data;

  if (flag) {
    data = $(form).serialize();
  } else {
    data = null;
  }
var output;

  $.ajax({
    type: "POST",
    url: "/sim_resp2.php",
    data: data,
    success: function(result) {

      var asd = jQuery.parseJSON(result);
      // alert(asd.abc);
      output = asd.abc;
      var jsonObj = JSON.parse(asd.abc);

      var taken_spots = [];
      var ids = [];

      for (var i = 0; i < jsonObj.polygons.length; i++) {
        taken_spots[i + 1] = jsonObj.polygons[i].taken_spots;
      }

      geojson.eachLayer(function(layer) {

        var taken_spot = taken_spots[layer.feature.properties.id];
        var free_spot = layer.feature.properties.spots;

        if (taken_spot !== 'null' && free_spot !== "") {

          var percentage = Math.round((taken_spot / free_spot) * 100) / 100;

          layer.colo = 'changed';

          if (percentage <= 0.59) {
            layer.setStyle({
              fillColor: 'white'
            });
            layer.setStyle({
              fillColor: 'green'
            });
            layer.colori = 'green';
          } else if ((percentage > 0.59) && (percentage <= 0.84)) {

            layer.setStyle({
              fillColor: 'white'
            });
            layer.setStyle({
              fillColor: 'yellow'
            });
            layer.colori = 'yellow';
          } else if (percentage > 0.84) {

            layer.setStyle({
              fillColor: 'white'
            });
            layer.setStyle({
              fillColor: 'red'
            });
            layer.colori = 'red';
          }
        }
      });

    },
    error: function(result) {
      alert('error');
    }
  });
  return output;
}
