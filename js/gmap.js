/**
 * @file
 * Javascript functions for generating Google Maps for the Realtor Express plugin.
 */

/**
 *  Displays a simple Gmap with a marker.
 *  
 * @param float lat
 * @param float lng
 * @param HTML element ID elementId
 * @param int width
 * 	Map width
 * @param int height
 * 	Map height
 */
function updateRexAdminGmap(lat, lng, elementId, width, height) {
	if (elementId === null) return;
	
	// Google map styles.
	if (!document.getElementById('rex-admin-map')) {
		if (typeof(height) === 'undefined' || height < 1) height = 300;
		if (typeof(width) === 'undefined' || width < 1) width = 400;
		document.getElementById(elementId).style.height = height + 'px';
		document.getElementById(elementId).style.width = width + 'px';
	}
	else {
		document.getElementById('rex-admin-map').style.height = '180px';
		document.getElementById('rex-admin-map').style.width = '240px';
		document.getElementById('rex-admin-map').style.border = "1px solid #ccc";
	}
	
	var myLatlng = new google.maps.LatLng(lat,lng);
	var mapOptions = {
	  zoom: 15,
	  center: myLatlng,
	  scrollwheel: false,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	if (typeof(elementId) === 'undefined') elementId = 'rex-admin-map';
	
	var map = new google.maps.Map(document.getElementById(elementId), mapOptions);
	
	var marker = new google.maps.Marker({
	    position: myLatlng,
	    map: map,
	    title: 'Listing Location'
    });
}

/**
 * Generates the Multi Map using Google Maps API.
 * 
 * @param JSON object locationsJson
 * 	A JSON object contains the necessary information about the listing. @see RexListing.php
 * @param HTML element ID elementId
 * 	The element the map should be rendered in.
 * @param boolean attachInfoWindows
 * 	Whether or not to attach the info windows to the markers.
 */
function updateMultiMap(locationsJson, elementId, attachInfoWindows) {
	if (elementId === null || locationsJson === null) return;
	if (typeof(elementId) === 'undefined') elementId = 'rex-map-widget';
	
	var widgetContainer = document.getElementById(elementId);
	var width = widgetContainer.clientWidth;
	
	document.getElementById(elementId).style.height = width + 'px';
	document.getElementById(elementId).style.width = width + 'px';
	
	var mapOptions = {
	  scrollwheel: false,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	var map = new google.maps.Map(document.getElementById(elementId), mapOptions);
	var bounds = new google.maps.LatLngBounds();
	
	var infoWindow = new google.maps.InfoWindow();
	
	// for (i = 0; i < markers.length; i++) {
	for (var key in locationsJson) {
		if (!locationsJson.hasOwnProperty(key)) return;
		
		var mkLat = locationsJson[key].lat;
		var mkLng = locationsJson[key].lng;
		var mkTitle = locationsJson[key].listing_title;
		var mkLink = locationsJson[key].link;
		var mkImage = locationsJson[key].image;
		var mkPrice = locationsJson[key].price;
		var mkBeds = locationsJson[key].bedrooms;
		var mkBaths = locationsJson[key].baths;
		var mkFlrSpace = locationsJson[key].floor_space;
		var mkFlrUnits = locationsJson[key].flr_space_units;
		var contentString;
		
		var markerLatLng = new google.maps.LatLng(mkLat, mkLng);
		var marker = new google.maps.Marker({
	        position: markerLatLng,
	        map: map,
	        title:  mkTitle,
	        url: mkLink,
	    });
		
	    bounds.extend(markerLatLng); // Include all markers in map view
	    
	    
	    if (attachInfoWindows) {
			contentString = "<div class='listing-info-window'>";
	    	contentString += "<div><hgroup><h1><a href='" + marker.url + "'>" + marker.title + "</a></h1></hgroup></div>";
	    	
	    	if (mkImage[0]) {
	    		contentString += "<div><a href='" + marker.url + "'><img src='" + mkImage[0] + "' alt='listing image' /></a></div";
	    	}
	    	
	    	contentString += "<div>Price: " + mkPrice + "<br/>";
	    	contentString += "Bedrooms: " + mkBeds + "<br/>";
	    	contentString += "Baths: " + mkBaths + "<br/>";
	    	contentString += "Floor Space: " + mkFlrSpace + " " + mkFlrUnits + "<br/>";
	    	contentString += "</div>";
	    	contentString += "</div>";
	    	
			attachInfoWindowToMarker(marker, map, infoWindow, contentString);
	    }
	    else {
	    	attachLinkToMarker(marker, marker.url);
	    }
	}
	map.fitBounds(bounds);
}

/**
 * Helper function for attaching info windows to markers.
 * 
 * @param Gmap marker
 * @param Gmap map
 * @param Global infoWindow
 * @param HTML contentString
 */
function attachInfoWindowToMarker(marker, map, infoWindow, contentString) {
    google.maps.event.addListener(marker, 'click', function() {
    	infoWindow.close();
    	infoWindow.setContent(contentString);
    	infoWindow.open(map, marker);
	});
}

/**
 * Helper function for attaching links to markers.
 * 
 * @param Gmap marker
 * @param href link
 */
function attachLinkToMarker(marker, link) {
	google.maps.event.addListener(marker, 'click', function() {
		window.location.href = link;
	});
}

/**
 * Init the shortcode map and the multi-map.
 */
function initMaps() {
	if (document.getElementById('rex-multi-map-widget') != null) 
		initMapWidget();
	if (document.getElementById('rex-multi-map') != null)
		initShortcodeMultiMap();
}