/**
 * Script Module D.OSMap Locations
 *
 * @version 1.0
 * 
 * @author D.art <d.art.reply@gmail.com>
 */

// Fire on document
jQuery(function($) {
    // Points from JSON-file.
    var pointsJSON = null;

    // Fire on document.
    $(document).ready(function() {
        // API Leaflet v1.9.4.
        if (dosmapVerAPI == 'leaflet_194') {
            // Set tiles.
            var tilesMap = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            });

            // Icons for markers.
            var pointIcon = new L.Icon({
                iconUrl: dosmapUrlIconPoints,
                iconSize: [30, 38],   // [width, height]
                iconAnchor: [15, 38], // x = width/2, y = height
                popupAnchor: [0, 0]   // x = 0, y = 0
            });

            // Cluster Group.
            if (dosmapClusterization) {
                var markersCluster = L.markerClusterGroup();
            }

            // Init Map.
            var leafletMap = L.map( 'dosmap-locations-map-' + dosmapModuleIndex, {
                center: [dosmapInitLatitude, dosmapInitLongitude],
                zoom: dosmapInitZoom,
                minZoom: 2,
                maxZoom: 18
            }).addLayer(tilesMap);

            // Get markers.
            $.ajax({
                url: dosmapUrlPoints,
                dataType: 'json',
                beforeSend: function() {},
                complete: function() {},
                success: function( jsonPoints ) {
                    //console.log(jsonPoints);

                    pointsJSON = jsonPoints;

                    // Add markers to map.
                    var leafletLayer = L.geoJSON(pointsJSON, {
                        //style: function (feature) {},
                        //filter: function (feature, layer) {},
                        //onEachFeature: function (feature, layer) {},
                        pointToLayer: pointToLayer
                    }).addTo(leafletMap);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    console.log("Error! Points not loaded.");
                }
            });

            // Select location from list.
            $( '.module-dosmap_locations .module-dosmap_locations-list' ).on( 'click', '.item button', function() {
                var _this = $(this);
                var buttonID = $(this).data('id');

                _this.closest('.module-dosmap_locations-list').find('.item-coords_not_found').remove();

                if (buttonID) {
                    // Coordinates of current location.
                    var zoom = parseInt(dosmapPanZoom);
                    var pointsLength = pointsJSON.features.length;

                    for (var i = 0; i < pointsLength; i++) {
                        var jsonID = pointsJSON.features[i].id;

                        if ( jsonID == buttonID ) {
                            var coordinates = pointsJSON.features[i].geometry.coordinates;
                            //var hintContent = pointsJSON.features[i].properties.hintContent
                            //var iconCaption = pointsJSON.features[i].properties.iconCaption;
                            //var balloonContentBody = pointsJSON.features[i].properties.balloonContentBody;
                            //var balloonContentHeader = pointsJSON.features[i].properties.balloonContentHeader;
                            //var balloonContentFooter = pointsJSON.features[i].properties.balloonContentFooter;

                            // Select Marker.
                            selectMarker(zoom, coordinates);

                            break;
                        } else {
                            if ( (i + 1) == pointsLength ) {
                                _this.closest('.item').append('<div class="item-coords_not_found">' + error_coords_nfound + '</div>');
                            }
                        }
                    }
                }
            });

            // Select Marker (function).
            function selectMarker(zoom, coordinates) {
                // Moving map to current marker.
                leafletMap.flyTo({lat: coordinates[0], lng: coordinates[1]}, zoom, {animate: true, duration: 1.0});
            }

            // Edit Marker with coordinates.
            function pointToLayer (feature, latlng) {
                // Swap coordinates.
                var swapLatLng = {
                    lat: latlng.lng,
                    lng: latlng.lat
                };

                /* Marker */

                var markerOptions = {
                    icon: pointIcon
                }

                var marker = L.marker(swapLatLng, markerOptions);

                /* Show balloon by click - ON/OFF */

                if (dosmapBaloonInfo) {
                    /* Balloon content */

                    var popupContent  = '';

                    if (removeHTMLTags(feature.properties.balloonContentHeader)) {
                        popupContent += '<div class="balloonContentHeader" style="color: #000; font-size: 16px; font-weight: 700; margin: 0 0 3px;">' + feature.properties.balloonContentHeader + '</div>';
                    }

                    if (removeHTMLTags(feature.properties.balloonContentBody)) {
                        popupContent += '<div class="balloonContentBody" style="font-size: 14px;">' + feature.properties.balloonContentBody + '</div>';
                    }

                    if (removeHTMLTags(feature.properties.balloonContentFooter)) {
                        popupContent += '<div class="balloonContentFooter" style="color: #999; font-size: 12px; margin: 5px 0 0;">' + feature.properties.balloonContentFooter + '</div>';
                    }

                    /* Marker Events */

                    if (removeHTMLTags(popupContent)) {
                        marker.bindPopup(popupContent).on('click', function(e) {
                            e.target.bindPopup();
                        });
                    }
                }

                /* Hint content */

                if (feature.properties.hintContent) {
                    var popupHint  = '';
                        popupHint += '<div style="color: #333; font-size: 12px;">' + feature.properties.hintContent + '</div>';

                    marker.bindTooltip(popupHint, {direction: 'bottom', offset: [0, 0]}).openTooltip();
                }

                // Add marker to cluster group.
                if (dosmapClusterization) {
                    markersCluster.addLayer(marker);
                    leafletMap.addLayer(markersCluster);
                }

                /* Return marker */

                if (dosmapClusterization) {
                    return false;
                } else {
                    return marker;
                }
            }

        // API Other.
        } else {}
    });

    // Remove HTML tags.
    function removeHTMLTags(htmlString) {
        // Create a new DOMParser instance.
        const parser = new DOMParser();

        // Parse the HTML string into a DOM document.
        const doc = parser.parseFromString(htmlString, 'text/html');

        // Extract the text content from the parsed document.
        const textContent = doc.body.textContent || "";

        // Return string.
        // Trim any leading or trailing whitespace.
        return textContent.trim();
    }
});