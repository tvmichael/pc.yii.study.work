<?php
/**
 * Created by PhpStorm.
 * User: user-pc
 * Date: 29-Jul-19
 */
?>

<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css" type="text/css">
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Leaflet</div>
                <div class="panel-body">

                    <div id="map" class="map"></div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- script>
    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([30.489, 50.492]),
            zoom: 12
        })
    });
</script -->

<script>
    var baseMapLayer = new ol.layer.Tile({
        source: new ol.source.OSM()
    });
    var map = new ol.Map({
        target: 'map',
        layers: [ baseMapLayer],
        view: new ol.View({
            center: ol.proj.fromLonLat([-74.0061,40.712]),
            zoom: 7 //Initial Zoom Level
        })
    });
    //Adding a marker on the map
    var marker = new ol.Feature({
        geometry: new ol.geom.Point(
            ol.proj.fromLonLat([-74.006,40.7127])
        ),  // Cordinates of New York's Town Hall
        name: 'NEW',
    });
    var vectorSource = new ol.source.Vector({
        features: [marker]
    });

    var iconStyle = new ol.style.Style({
        image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            anchor: [0.5, 46],
            anchorXUnits: 'fraction',
            anchorYUnits: 'pixels',
            opacity: 0.5,
            src: '/images/marker.png',
        }))
    });

    var markerVectorLayer = new ol.layer.Vector({
        source: vectorSource,
        style: iconStyle
    });
    map.addLayer(markerVectorLayer);

</script>