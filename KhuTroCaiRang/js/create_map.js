var default_zoom = 13;

var enable_searching = false;

var mapOptions = {
    center: [10.00299572347174, 105.81870413527979],
    zoom: default_zoom,
    zoomDelta: 0.25,
    zoomSnap: 0
};

var map = new L.map('map', mapOptions);

var layer_to_mau = new L.FeatureGroup();

var layer_khu_tro = new L.FeatureGroup();

var layer_tim_kiem = new L.FeatureGroup();

var layer_domain = new L.FeatureGroup();

// Openstreemap Layer.
// var layer_map = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

// MapBox Layer.
// L.mapbox.accessToken = 'pk.eyJ1IjoibHl2YW1heCIsImEiOiJja2djN3JxOGYwbzNoMnJydnU5amJiMXBxIn0.-aX9gOgQhcCtpaf79ufgUw';
// var layer_map = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=' + L.mapbox.accessToken, {
//    attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
//    tileSize: 512,
//    zoomOffset: -1
// });

// Gooogle Map Layer.
var layer_map = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '© Google Map'
});

layer_map.addTo(map);

function getColor(d) {
    return d > 8 ? '#0d010f' :
        d > 7  ? '#700c85' :
        d > 6  ? '#7d3513' :
        d > 5  ? '#1f1991' :
        d > 4   ? '#44f261' :
        d > 3   ? '#44f2b2' :
        d >= 2   ? '#f2e744' :
                    '#f5f0f0';
}

function style(feature) {
    return {
        fillColor: getColor(feature.properties.density),
        weight: 2,
        opacity: 1,
        color: '#134220',
        dashArray: '3',
        fillOpacity: 0.7
    };
}

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
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

var geojson;
geojson = L.geoJson(polygonData, {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};

// method that we will use to update the control based on feature properties passed
info.update = function (props) {
    this._div.innerHTML = '<h5><b>Mật độ khu trọ Quận Cái Răng</b></h5>' +  (props ?
        '<b>Phường ' + props.name + '</b><br />' + props.density + ' khu trọ'
        : 'Đưa chuột lên vùng cần xem thông tin<br><br>Hoặc zoom để xem các khu trọ');
};

info.addTo(map);

var legend = L.control({position: 'bottomright'});

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 2, 3, 4, 5, 6, 7, 8],
        labels = [];

    // loop through our density intervals and generate a label with a colored square for each interval
    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
            '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
            grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
    }

    return div;
};

legend.addTo(map);

var gps_marker = null;

map.on('zoomend', function() {
    if (map.getZoom() > default_zoom){
        map.removeLayer(geojson);
        map.removeLayer(info);
        if (!enable_searching){
            map.addLayer(layer_khu_tro);
            $("#map > div.leaflet-control-container > div.leaflet-bottom.leaflet-right > div.info.legend.leaflet-control").hide();
            $("#map > div.leaflet-control-container > div.leaflet-top.leaflet-right > div").hide();
        }
        
    }
    else {
        map.removeLayer(layer_khu_tro);
        if (gps_marker)
            gps_marker.remove();

        if (!enable_searching){
            map.addLayer(geojson);
            $("#map > div.leaflet-control-container > div.leaflet-bottom.leaflet-right > div.info.legend.leaflet-control").show();
            $("#map > div.leaflet-control-container > div.leaflet-top.leaflet-right > div").show();
        }
            
    }
});

// Hàm tính vị trí hiện tại
function vtri_htai(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            gps_icon = L.icon({
                iconUrl: '/images/icons/gps.png',
                shadowUrl: '',
    
                iconSize:     [40, 40], // size of the icon
                shadowSize:   [0, 0], // size of the shadow
                iconAnchor:   [20, 40], // point of the icon which will correspond to marker's location
                shadowAnchor: [0, 0],  // the same for the shadow
                popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
            });
            
            gps_marker = L.marker([latitude, longitude], { icon: gps_icon }).bindTooltip("<span style='color:red; font-weight: bold;'>Vị trí của bạn</span>", {
            });
            gps_marker.addTo(map);
            map.flyTo([latitude, longitude], 18);
        });
    }
}
