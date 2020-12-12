<?php
    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

    if (!$conn) {//loi ket noi csdl
    	echo "Error: Unable to connect to MySQL." . PHP_EOL;
    	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    	exit;
    }
    else{//ket noi den csdl
        mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

        // Đọc thông tin khu trọ và chủ trọ tương ứng.
        $query = 'SELECT ten, so_nha, tenduong, phuongxa, quanhuyen, tinh, c.hoten, c.sdt, k.id, k.lat, k.lng
        FROM khu_tro k, chu_tro c 
        WHERE k.cmnd = c.cmnd';

        $result = mysqli_query($conn, $query);

        $ds_khu_tro = [];
        while ($row = mysqli_fetch_array($result)) {
            $ds_khu_tro[] = [
                $row["lat"],
                $row["lng"],
                $row["ten"], 
                $row["so_nha"],
                $row["tenduong"],
                $row["phuongxa"],
                $row["quanhuyen"],
                $row["tinh"], 
                $row["hoten"], 
                $row["sdt"],
                $row["id"]
            ];
        }


        // Đọc tên quận huyện.
        $query = "SELECT d.TenPhuong, COUNT(k.id) sl_khutro 
        FROM diagioihanhchinh d, khu_tro k 
        WHERE d.TenPhuong = k.phuongxa 
        GROUP BY d.TenPhuong";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($conn));
            exit();
        }
        $phuong = [];
        while ($row = mysqli_fetch_array($result)) {
            $phuong[] = [$row["TenPhuong"], $row["sl_khutro"]];
        }

        // Đọc danh sách trường.
        $query = "SELECT lat, lng, icon_path, ten FROM truong";
        $result = mysqli_query($conn, $query);
        $school_marker = [];
        while ($row = mysqli_fetch_array($result)) {
            $school_marker[] = [$row["lat"], $row["lng"], $row["icon_path"], $row["ten"]];
        }

        mysqli_close($conn); 
    }
?>


<div id="map" class="mt-1">
    <div class="btn-group-vertical float-left ml-5 mt-1 p-0" style="z-index: 1001"> <!-- Use 401 to be between map and controls -->
        <button type="buttons" title="Lấy vị trí hiện tại" 
        style="border: 1px solid grey;"
        class="btn btn-light mt-2 p-1" onclick="vtri_htai();">
            <img src="/images/icons/gps.png" class="m-0" style="width: 25px" alt="Responsive image">
        </button>
    </div>

    <div class="btn-group float-right p-0" style="z-index: 1001; margin-top: 95px; width: 310px; margin-right: 10px"> <!-- Use 401 to be between map and controls -->
        <input type="text" id="txt_tim" class="form-control" placeholder="Tìm khu trọ" aria-label="Tìm khu trọ" aria-describedby="basic-addon2">
        <button type="buttons" id="btn_tim" class="btn btn-primary px-2" title="Tìm khu trọ theo tên trọ hoặc tên chủ trọ"  onclick="">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
            <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
        </button>
        <button type="buttons" id="btn_huy_tim" class="btn btn-danger px-2" title="Tắt tìm kiếm">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-square-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
            </svg>
        </button>
    </div>
</div>

<script src="../js/cairang.js"></script>

<script>
    <?php
        // Cập nhật số lượng nhà trọ từ csdl lên polygon.
        foreach ($phuong as $i => $qh) {
            echo "polygonData['features'][".$i."]['properties']['density'] = ".$qh[1].";".PHP_EOL;
        }
    ?>
</script>

<script src="../js/create_map.js"></script>

<script>
    <?php
        // Hiển thị danh sách trường lên map.
        foreach ($school_marker as $i => $s) {
            echo '
                var myIcon = L.icon({
                    iconUrl: "'.$s[2].'",
                    shadowUrl: "",
            
                    iconSize:     [36, 36], // size of the icon
                    shadowSize:   [0, 0], // size of the shadow
                    iconAnchor:   [18, 36], // point of the icon which will correspond to marker"s location
                    shadowAnchor: [4, 62],  // the same for the shadow
                    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                });
            
                var marker = L.marker(['.$s[0].','.$s[1].'], {title : "'.$s[3].'", icon : myIcon});
                marker.addTo(map);
            ';
        }

        // Hiển thị danh sách khu trọ lên map.
        foreach ($ds_khu_tro as $i => $s) {
            echo '
                var myIcon = L.icon({
                    iconUrl: "/images/icons/house.png",
                    shadowUrl: "",
                    iconSize:     [36, 36], // size of the icon
                    shadowSize:   [0, 0], // size of the shadow
                    iconAnchor:   [18, 36], // point of the icon which will correspond to marker"s location
                    shadowAnchor: [4, 62],  // the same for the shadow
                    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                });
            
                var marker = L.marker(['.$s[0].','.$s[1].'], {title : "'.$s[2].'", icon : myIcon});
                marker.addTo(layer_khu_tro);
            ';
        }
        
    ?>
</script>

<script src="/js/leaflet-routing-machine.js"></script>

<script>
    $(document).ready(function () {

        var ds_khu_tro = <?php echo json_encode($ds_khu_tro); ?>;

        var R = L.Routing.control({
            routeWhileDragging: false,
        });

        function hienthi_kq_timkiem(ds_kq){

            map.removeLayer(layer_tim_kiem);
            layer_tim_kiem = new L.FeatureGroup();
            layer_routing = new L.FeatureGroup();

            ds_kq.forEach((ktro, index) => {
                var myIcon = L.icon({
                    iconUrl: "/images/icons/symbol_home.png",
                    shadowUrl: "",
                    className: "blinking",
                    iconSize:     [46, 60], // size of the icon
                    shadowSize:   [0, 0], // size of the shadow
                    iconAnchor:   [23, 60], // point of the icon which will correspond to marker"s location
                    shadowAnchor: [4, 50],  // the same for the shadow
                    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                });

                var title_search = (ktro[2] + ", Chủ trọ: "+ ktro[8] +"");
            
                var marker = L.marker([ktro[0], ktro[1]], {title : title_search ,icon : myIcon}).on('click', function (e) { 
                    
                    map.removeControl(R);

                    this.addTo(layer_routing);

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
                            gps_marker.addTo(layer_routing);
                            

                            R = L.Routing.control({
                                waypoints: [
                                    L.latLng(latitude, longitude),
                                    L.latLng(ktro[0], ktro[1])
                                ],
                                createMarker: function() { return null; },
                                routeWhileDragging: false,
                                language: 'de'
                            }).addTo(map);
                            map.fitBounds(layer_routing.getBounds());
                        });
                    }
                    
                    
                });
                marker.addTo(layer_tim_kiem);
            });

            layer_tim_kiem.addTo(map);
            map.fitBounds(layer_tim_kiem.getBounds());
            map.removeLayer(geojson);
            $("#map > div.leaflet-control-container > div.leaflet-bottom.leaflet-right > div.info.legend.leaflet-control").hide();
            $("#map > div.leaflet-control-container > div.leaflet-top.leaflet-right > div").hide();
        }

        function timkiem_ktro(){
            
            if($("#txt_tim").val() != ""){

                enable_searching = true;
                map.removeLayer(layer_khu_tro);
                map.removeLayer(layer_tim_kiem);

                key = $("#txt_tim").val().toLowerCase();

                ds_kq = [];

                ds_khu_tro.forEach((ktro, index) => {
                    ten_ktro = ktro[2].toLowerCase();
                    ten_chtro = ktro[8].toLowerCase();
                    if (ten_ktro.includes(key) || ten_chtro.includes(key)){
                        ds_kq.push(ktro);
                    }
                });

                if(ds_kq.length == 0){
                    alert("Không tìm thấy");
                }
                else{
                    hienthi_kq_timkiem(ds_kq);
                    // console.log($("#map > div.leaflet-control-container > div.leaflet-top.leaflet-right > div.leaflet-routing-container.leaflet-bar.leaflet-control"));
                }
            }
            else{
                alert("Vui lòng nhập nội dung tìm kiếm");
            }
        }

        function huy_tim_kiem(){
            enable_searching = false;
            $("#txt_tim").val("");

            map.setZoom(default_zoom);

            map.removeLayer(layer_tim_kiem);
            map.removeLayer(layer_routing);

            $("#map > div.leaflet-control-container > div.leaflet-bottom.leaflet-right > div.info.legend.leaflet-control").show();
            $("#map > div.leaflet-control-container > div.leaflet-top.leaflet-right > div").show();

            $('img[src$="/images/icons/gps.png"]').remove();

            map.removeControl(R);
        }

        $("#txt_tim").keypress(function (e) { 
            if (e.which == 13) {
                timkiem_ktro();
            }
        });

        $("#btn_tim").click(function (e) { 
            timkiem_ktro();
        });

        $("#btn_huy_tim").click(function (e) { 
            huy_tim_kiem();
        });

    });
</script>