// Hiển thị thông báo thêm, update phòng.
function hien_thi_thong_bao(ketqua, chucnang){

    var id_alert = "";
    var mess = "";
    if (chucnang == "add"){
        id_alert = "#alert_them_phong";
        mess = "Thêm";
    }

    if (chucnang == "update"){
        id_alert = "#alert_update_phong";
        mess = "Lưu";
    }

    if(ketqua == 1){
        $(id_alert + " span").text(mess + " thành công");
        $(id_alert).removeClass("alert-danger").addClass("alert-success");
        $(id_alert).fadeTo(1500, 500).slideUp(500, function(){
            $(id_alert).slideUp(500);
        });
    }

    if(ketqua == 0){
        $(id_alert + " span").text(mess + " thành công");
        $(id_alert).removeClass("alert-success").addClass("alert-danger");
        $(id_alert).fadeTo(1500, 500).slideUp(500, function(){
            $(id_alert).slideUp(500);
        });
    }

    if(ketqua == "ex"){
        $(id_alert + " span").text(mess + " thành công");
        $(id_alert).removeClass("alert-success").addClass("alert-danger");
        $(id_alert).fadeTo(1500, 500).slideUp(500, function(){
            $(id_alert).slideUp(500);
        });
    }
}

// Hiển thị modal update thông tin khu trọ.
function show_md_update(ten, sonha, duong, phuong, id, lat, lng) {
    $("#txt_id").val(id);
    $("#txt_ten").val(ten);
    $("#txt_sonha").val(sonha);
    $("#txt_duong").val(duong);
    $("#txt_phuong").val(phuong);

    $("#btn_update").prop('disabled', false);
    $("#lb_vt").html("");

    show_marker(lat, lng);
}

// Xóa thông tin một phòng theo stt.
function xoa_phong(stt, id){

    var r = confirm('Xóa phòng ' + stt);
    if (r == false) {
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: "/XuLy/xoa_phong.php",
        data: {"stt": id},
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            if (response.ok == 1){
                $("#tr_" + id).remove();

                count = ($("#tb_phong tr").length - 1);

                if (count == 0){
                    html_text = "<tr><td colspan='4' class='text-center'>Chưa có dữ liệu</td></tr>";
                    $("#tb_phong").append(html_text);
                }
            }
        }
    });
}

// Lưu thông tin danh sách phòng.
function luu_phong(){
    var select  = $("#tb_phong select");

    var insert_val = {};

    for(var i=0;i<select.length;i++){
        
        id = $(select.get(i)).attr("id");

        column = id.split('_')[0];
        stt = id.split('_')[1];
        val = $(select.get(i)).val();

        if (column == "slL"){
            insert_val["stt"] = stt;
            insert_val["maloai"] = val;
        }
        if (column == "slT"){
            insert_val["trang_thai"] = val;
            
            let data = JSON.stringify(insert_val);
            $.ajax({
                type: "POST",
                url: "/XuLy/update_phong.php",
                data:{"data":data},
                dataType: 'JSON',
                success: function (response) {
                    hien_thi_thong_bao(response.ok, "update");
                }
            });

            insert_val = {};
        }
    }
}

// Hiển thị modal danh sách phòng của một khu trọ theo id.
function show_md_phong(id, ten){
    $("#md_phong b").text(ten);
    $("#id_tro").val(id);
    $.ajax({
        type: "POST",
        url: "/XuLy/lay_ds_phong.php",
        data: {"id": id},
        dataType: 'JSON',
        success: function (response) {

            $("#md_phong .modal-body").empty();

            var html_text  = '<table id="tb_phong" class="table table-hover table-bordered">\
                <thead class="table-success"><tr><td>Stt</td><td>Loại phòng</td><td>Trạng thái</td><td></td></thead>';

            if (response != null){

                var len = response.length;
                for(var i=0; i<len; i++){
                    html_text += "<tr id='tr_"+ response[i].stt +"'><td class='align-middle'>"+ (i + 1) +
                    "</td><td>"+ response[i].ten_loai +
                    "</td><td>"+ response[i].trang_thai +
                    "</td><td>"+
                    "<button class='btn btn-danger' onclick='xoa_phong(\""+ (i+1) +"\",\""+ response[i].stt +"\");'>X</button></td></tr>";
                }
                html_text += "</table>";
            }
            else{
                html_text += "<tr><td colspan='4' class='text-center'>Chưa có dữ liệu</td></tr>";
            }

            $("#md_phong .modal-body").append(html_text);
        }
    });
}

var map = L.map('map').setView([10.00299572347174, 105.81870413527979], 20);
var map_a = L.map('map_a').setView([9.991353548490608, 105.75253072292591], 16);

var myIcon = L.icon({
    iconUrl: "/images/icons/house.png",
    shadowUrl: "",

    iconSize:     [35, 35], // size of the icon
    shadowSize:   [0, 0], // size of the shadow
    iconAnchor:   [10, 20], // point of the icon which will correspond to marker"s location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [10, -20] // point from which the popup should open relative to the iconAnchor
});

var marker = L.marker();

// Cập nhật và hiển thị marker trên bản đồ trong modal update thông tin khu trọ.
function show_marker(lat, lng, add_new=false){

    if (add_new){
        map_a.removeLayer(marker);
        marker = L.marker([lat, lng],{'icon': myIcon}).addTo(map_a);
    }
    else {
        map.removeLayer(marker);
        marker = L.marker([lat, lng],{'icon': myIcon}).addTo(map);
    }
    map.setView([lat, lng]);

    if (add_new) {
        $("#txt_lat_a").val(lat);
        $("#txt_lng_a").val(lng);
    }
    else{
        $("#txt_lat").val(lat);
        $("#txt_lng").val(lng);
    }
    
}

// Hiển thị bản đồ trong modal update thông tin khu trọ.
function show_map(){
    // Comment out the below code to see the difference.
    $('#md_update').on('shown.bs.modal', function() {
        map.invalidateSize();
        var layer_map = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google Map'
        }).addTo(map);
    });

    $('#md_add').on('shown.bs.modal', function() {
        map_a.invalidateSize();
        var layer_map = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google Map'
        }).addTo(map_a);
    });
}

function kiemtra_vitri(address, add_new=false){
    if (address.lastIndexOf("VNM") == -1){
        return 4;
    }

    if (address.lastIndexOf("Cần Thơ") == -1){
        return 3;
    }

    var phuong = "";
    if (address.lastIndexOf("Phường") != -1){
        var T = address.substring(address.lastIndexOf("Phường") + 7);
        phuong = T.substring(0, T.indexOf(","));
    }

    var quan = "";
    if (address.lastIndexOf("Quận") != -1){
        var T = address.substring(address.lastIndexOf("Quận") + 5);
        quan = T.substring(0, T.indexOf(","));
    }
    if (address.lastIndexOf("Huyện") != -1){
        var T = address.substring(address.lastIndexOf("Huyện") + 6);
        quan = T.substring(0, T.indexOf(","));
    }

    if (quan != "" && quan != "Cái Răng"){
        return 1;
    }

    if (!add_new && phuong != "" && phuong != $("#txt_phuong").val()){
        return 2;
    }
    return 0;
}


// Cập nhật marker trên bản đồ update.
function marker_update_map(e){
    fetch(`https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&langCode=VN&location=${e.latlng.lng},${e.latlng.lat}`)
    .then(res => res.json())
    .then(myJson => {
        
        show_marker(e.latlng.lat, e.latlng.lng);
        marker.bindPopup(myJson.address.Match_addr).openPopup();
        kt = kiemtra_vitri(myJson.address.LongLabel);
        if (kt == 0){
            $("#lb_vt").html("Vị trí: <b style='color: green;'>"+ myJson.address.LongLabel +"</b>");
            $("#btn_update").prop('disabled', false);
        }
        if (kt == 1){
            $("#lb_vt").html("<b style='color: red;'>Lỗi: Vị trí ngoài quận cái răng</b> ");
            $("#btn_update").prop('disabled', true);
        }
        if (kt == 2){
            $("#lb_vt").html("<b style='color: red;'>Lỗi: Vị trí không đúng phường đã nhập</b> ");
            $("#btn_update").prop('disabled', true);
        }
        if (kt == 3){
            $("#lb_vt").html("<b style='color: red;'>Lỗi: Vị trí ngoài Cần Thơ</b> ");
            $("#btn_update").prop('disabled', true);
        }
        if (kt == 4){
            $("#lb_vt").html("<b style='color: red;'>Lỗi: Vị trí ngoài Việt Nam</b> ");
            $("#btn_update").prop('disabled', true);
        }
    });   
}

// Cập nhật marker trên bản đồ add.
function marker_add_map(e){
    fetch(`https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=pjson&langCode=VN&location=${e.latlng.lng},${e.latlng.lat}`)
    .then(res => res.json())
    .then(myJson => {
        
        show_marker(e.latlng.lat, e.latlng.lng, true);
        marker.bindPopup(myJson.address.Match_addr).openPopup();
        kt = kiemtra_vitri(myJson.address.LongLabel, true);

        console.log("kt = " + kt);

        if (kt == 0){
            $("#lb_vt_a").html("Vị tri: <b style='color: green;'>"+ myJson.address.LongLabel +"</b>");
            $("#btn_add").prop('disabled', false);
            $("#txt_p_a").val(myJson.address.Neighborhood.replace("Phường ", ""));

            console.log(myJson.address);

            sonha_duong = myJson.address.ShortLabel.split(" ");

            if (sonha_duong.length == 2) {
                $("#txt_sn_a").val(sonha_duong[0]);
                $("#txt_d_a").val(sonha_duong[1]);
            }

            if (sonha_duong.length == 1) {
                $("#txt_sn_a").val("");
                $("#txt_d_a").val(sonha_duong);
            }

            // $("#txt_sn_a").val(myJson.address.AddNum);
            // $("#txt_d_a").val(myJson.address.Address.split(" ")[1]);

            $("#txt_q_a").val(myJson.address.District.replace("Huyện ", ""));
            $("#txt_q_a").val(myJson.address.District.replace("Quận ", ""));
            $("#txt_t_a").val(myJson.address.City);
        }
        if (kt == 1){
            $("#lb_vt_a").html("<b style='color: red;'>Lỗi: Vị trí ngoài quận cái răng</b> ");
            $("#btn_add").prop('disabled', true);
        }
        if (kt == 2){
            $("#lb_vt_a").html("<b style='color: red;'>Lỗi: Vị trí không đúng phường đã nhập</b> ");
            $("#btn_add").prop('disabled', true);
        }
        if (kt == 3){
            $("#lb_vt_a").html("<b style='color: red;'>Lỗi: Vị trí ngoài Cần Thơ</b> ");
            $("#btn_add").prop('disabled', true);
        }
        if (kt == 4){
            $("#lb_vt_a").html("<b style='color: red;'>Lỗi: Vị trí ngoài Việt Nam</b> ");
            $("#btn_add").prop('disabled', true);
        }
    }); 
}

$(document).ready(function() {

    // Đưa tb_data vào bảng thao tác của jquery datatable api. 
    // Không cho phép tìm kiếm trên cột chỉ số 4 (cột thứ 5 - cột các button tao tác trên dòng)
    $('#tb_data').dataTable( {
        "columnDefs": [
            { "searchable": false, "targets": 4 }
        ]
    } );

    map.on('click', function(e) {
        marker_update_map(e);
    });

    map_a.on('click', function(e) {
        marker_add_map(e, "add");
    });

    show_map();

    $("#alert_khutro").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert_khutro").slideUp(500);
    });

    $("#alert_them_phong").hide();
    $("#alert_update_phong").hide();

    $("#btn_them_phong").click(function (e) { 
        
        $.ajax({
            type: "POST",
            url: "/XuLy/them_phong.php",
            data: {
                "maloai": $("#sl_L").val(),
                "trang_thai": $("#sl_tt").val(),
                "id_khu_tro": $("#id_tro").val()
            },
            dataType: 'JSON',
            success: function (response) {
                hien_thi_thong_bao(response.ok, "add");
                show_md_phong($("#id_tro").val(), $("#md_phong b").text());
            }
        });

    });

    $("#txt_cmnd").change(function (e) { 
        $.ajax({
            type: "POST",
            url: "/XuLy/tim_chu_tro.php",
            data: {
                "cmnd": $("#txt_cmnd").val()
            },
            dataType: 'JSON',
            success: function (response) {
                
                if (response != null){
                    chutro = response[0];
                    $("#txt_hoten").val(chutro.hoten);
                    $("#txt_sdt").val(chutro.sdt);
                    $("#sl_gt").val(chutro.gioitinh);
                    $("#new_admin").val("no");

                    $("#txt_hoten").prop('readonly', true);
                    $("#txt_sdt").prop('readonly', true);
                    $("#sl_gt").attr("disabled", true); 
                }
                else{
                    $("#txt_hoten").val("");
                    $("#txt_sdt").val("");
                    $("#sl_gt").val("M");
                    $("#new_admin").val("yes");

                    $("#txt_hoten").prop('readonly', false);
                    $("#txt_sdt").prop('readonly', false);
                    $("#sl_gt").attr("disabled", false); 
                }
            }
        });
        
    });

    $("#f_add").submit(function(e) {
        
        if ($("#txt_q_a").val() == "" ) {
            e.preventDefault();
            alert('Vui lòng click chọn vị trí khu trọ trên bản đồ.');
            return false;
        }
    });
});