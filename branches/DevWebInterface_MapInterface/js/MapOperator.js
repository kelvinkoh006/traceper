// Map class

function MapOperator() {
	
	MAP_OPERATOR = this;
	
	document.writeln("<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=true'></script>");
	document.writeln("<script type='text/javascript' src='http://code.google.com/apis/gears/gears_init.js'></script>");
	this.lang = "en";
		
	var initialLocation;
	var siberia = new google.maps.LatLng(60, 105);
	var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
	var browserSupportFlag =  new Boolean();
	var map;
	var infowindow = new google.maps.InfoWindow();
	
	
	// Try W3C Geolocation method (Preferred)
	if(navigator.geolocation) 
	{
		var myOptions = {
		   zoom: 6,
		   mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		browserSupportFlag = true;
	    navigator.geolocation.getCurrentPosition(function(position) {
	      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	      contentString = "Your location found using W3C standard";
	      map.setCenter(initialLocation);
	      infowindow.setContent(contentString);
	      infowindow.setPosition(initialLocation);
	      infowindow.open(map);
	    }, function() {
	      handleNoGeolocation(browserSupportFlag);
	    });
	}
	else if (google.gears) 
	{
	    // Try Google Gears Geolocation
	    browserSupportFlag = true;
	    var geo = google.gears.factory.create('beta.geolocation');
	    geo.getCurrentPosition(function(position) {
	      initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
	      contentString = "Location found using Google Gears";
	      map.setCenter(initialLocation);
	      infowindow.setContent(contentString);
	      infowindow.setPosition(initialLocation);
	      infowindow.open(map);
	    }, function() {
	      handleNoGeolocation(browserSupportFlag);
	    });
	} 
	else 
	{
	    // Browser doesn't support Geolocation
	    browserSupportFlag = false;
	    handleNoGeolocation(browserSupportFlag);
  	}
		
}

function handleNoGeolocation(errorFlag) 
{
	if (errorFlag == true) 
	{
		initialLocation = newyork;
		contentString = "Error: The Geolocation service failed.";
		} 
		else 
		{
			initialLocation = siberia;
		contentString = "Error: Your browser doesn't support geolocation. Are you in Siberia?";
		}
		map.setCenter(initialLocation);
		infowindow.setContent(contentString);
		infowindow.setPosition(initialLocation);
		infowindow.open(map);
}