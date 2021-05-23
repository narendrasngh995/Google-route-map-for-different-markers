<style>
html,
body,
#dvMap {
  height: 400px;
  width: 98%;
}
</style>
<script src="jquery-latest.pack.js"></script> 
<script src="https://maps.googleapis.com/maps/api/js?key=API_KEY"></script>
<!--Show the multiple markers on google map and find the distance between all markers-->
<div id="info"></div>
<div id="dvMap"></div>
<div id="directions_panel" style="margin:20px;background-color:#FFEE77;"></div>
<div id="total"></div>
<div id="control_panel" style="float:right;width:30%;text-align:left;padding-top:20px">

<script>
var directionsDisplay = [];
var directionsService = [];
var map = null;
var g = [];
var path = new Array();
var routeSegment = 0;

//Static lal,long values
function calcRoute() {
  var msg = [
    '28.6767079	,77.0354187',
    '27.1767	,78.0081',
	'26.2183 , 78.1828',
	'25.4484, 78.5685',
	'25.2138, 75.8648',
	'24.6324 , 77.3002',
    '22.8630434		,75.9295463',
	'23.2599, 77.4126',
	'23.8388, 78.7378',
	'24.7456, 78.8321',
	'24.9168, 79.5910',
	'26.4499, 80.3319',
	'25.4358, 81.8463',
	'25.3176, 82.9739',
	'24.7914, 85.0002',
	'25.5941, 85.1376',
	'26.1542, 85.8918',
	'26.1197,85.3910',
	'26.6438, 84.9040',
	'26.7606, 83.3732',
	'26.8140, 82.7630',
	'26.7730, 82.1458',
	'27.1340, 81.9619',
	'27.5705, 81.5977',
	'26.8467, 80.9462',
	'27.3965, 80.1250',
	'27.0514, 79.9137',
//'24.6637, 93.9063',
	
  ];
  var input_msg = msg;
  var locations = new Array();


  var bounds = new google.maps.LatLngBounds();
  for (var i = 0; i < input_msg.length; i++) {
    var tmp_lat_lng = input_msg[i].split(",");
    //var s = new google.maps.LatLng(tmp_lat_lng[0], tmp_lat_lng[1]);
    locations.push(new google.maps.LatLng(tmp_lat_lng[0], tmp_lat_lng[1]));
    bounds.extend(locations[locations.length - 1]);
  }

  var mapOptions = {
    // center: locations[0],
    zoom: 12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
  map.fitBounds(bounds);
  var summaryPanel = document.getElementById("directions_panel");
  summaryPanel.innerHTML = "";
  var i = locations.length;
  var index = 0;

  while (i != 0) {

    if (i < 1) {
      var tmp_locations = new Array();
      for (var j = index; j < locations.length; j++) {
        tmp_locations.push(locations[index]);
      }
      drawRouteMap(tmp_locations);
      i = 0;
      index = locations.length;
    }

    if (i >= 1 && i <= 26) {
      console.log("before :fun < 10: i value " + i + " index value" + index);
      var tmp_locations = new Array();
      for (var j = index; j < locations.length; j++) {
        tmp_locations.push(locations[j]);
      }
      drawRouteMap(tmp_locations);
      i = 0;
      index = locations.length;
      console.log("after fun < 10: i value " + i + " index value" + index);
    }

    if (i >= 26) {
      console.log("before :fun > 10: i value " + i + " index value" + index);
      var tmp_locations = new Array();
      for (var j = index; j < index + 26; j++) {
        tmp_locations.push(locations[j]);
      }
      drawRouteMap(tmp_locations);
      i = i - 25;
      index = index + 25;
      console.log("after fun > 10: i value " + i + " index value" + index);
    }
  }
}


function drawRouteMap(locations) {

  var start, end;
  var waypts = [];

  for (var k = 0; k < locations.length; k++) {
    if (k >= 1 && k <= locations.length - 2) {
      waypts.push({
        location: locations[k],
        stopover: true
      });
    }
    if (k == 0) start = locations[k];

    if (k == locations.length - 1) end = locations[k];

  }
  var request = {
    origin: start,
    destination: end,
    waypoints: waypts,
    optimizeWaypoints: false,
    travelMode: google.maps.TravelMode.DRIVING
  };
  console.log(request);

  directionsService.push(new google.maps.DirectionsService());
  var instance = directionsService.length - 1;
  directionsDisplay.push(new google.maps.DirectionsRenderer({
    preserveViewport: true
  }));
  directionsDisplay[instance].setMap(map);
  directionsService[instance].route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      console.log(status);
      directionsDisplay[instance].setDirections(response);
      var f = response.routes[0];

      // g=g.concat(f);
      var summaryPanel = document.getElementById("directions_panel");
      // summaryPanel.innerHTML = "";
      // For each route, display summary information.
      for (var i = 0; i < f.legs.length; i++) {
        routeSegment += 1;
        summaryPanel.innerHTML += "<b>Route Segment: " + routeSegment + "</b><br />";

        summaryPanel.innerHTML += f.legs[i].distance.text + "<br /><br />";
      }
      computeTotalDistance(response);
    } else {
      alert("directions response " + status);
    }




  });
}
var totalDist = 0;
var totalTime = 0;

function computeTotalDistance(result) {

  var myroute = result.routes[0];
  for (i = 0; i < myroute.legs.length; i++) {
    totalDist += myroute.legs[i].distance.value;
    totalTime += myroute.legs[i].duration.value;
  }

  document.getElementById("total").innerHTML += "total distance is: " + (totalDist / 1000).toFixed(2) + " km &nbsp;total time is: " + (totalTime / 60).toFixed(2) + " minutes<br>";
}


$( document ).ready(function() {

google.maps.event.addDomListener(window, 'load', calcRoute);

});
</script>