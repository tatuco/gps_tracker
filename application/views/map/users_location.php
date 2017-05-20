<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
#map_canvas_users{
    position: absolute;
    top: 0px;
    left: 0px;
    margin: 0px !important;
    width: 80%;
    border: 2px solid rgb(200,200,255);
    overflow: hidden;
}
#map_canvas_users .gm-style img{
    max-width: none !important;
}
#map_users{
    position: absolute;
    top: 0px;
    right: 0px;
    margin: 0px !important;
    width: 20%;
    border: 2px solid rgb(200,200,255);
    overflow: hidden;
}
#users_container{
	height: 100%;
	width: 100%;
	overflow: auto;
}
</style>

<div id="map_canvas_users">
    <div style="position: absolute;top:50%;left:50%; margin-left: -300px;">
        <h2>Please wait, map info is loading...</h2>
    </div>
    
</div>
<div id="map_users"><div id="users_container"></div></div>

<script>
$('#info_link').click(function(){
    $('#info').toggle(300);
});
</script>

<script>
$("#map_canvas_users").height($(window).height()-46);
$("#map_users").height($("#map_canvas_users").height());
// GOOGLE MAP API V3 !!!

function draw_map(latitude, longitude)
{
    if (first_loaded)
    {
        map = new google.maps.Map(document.getElementById("map_canvas_users"), 
                            { center:new google.maps.LatLng(latitude, longitude),//53.4175, -7.90663),
                              zoom: 7,
                              mapTypeId: google.maps.MapTypeId.ROADMAP });
        first_loaded = false;
        return;
    }
}

var first_loaded = true;

var color_chooser = {
    matrix: [[0, 0, 128],
            [0, 128, 0],
            [128, 0, 0],
            [0, 128, 128],
            [128, 0, 128],
            [128, 128, 0],
            [128, 128, 128]],
    color_index: 0
};
var users = {}; // object for users that contain markers, routes, checkbox for each user separately

function show_users_on_map(data)
{
    for (var i in data)
    {
        create_user(data[i]);
        create_marker(data[i]);
        draw_route(data[i]);
    }
}

function create_user(value)
{
    draw_map(value.latitude, value.longitude);
    if (users[value.id]) return;
   //create new user in users array if not exists and initialize with markers array and color.
    users[value.id] = {};
    users[value.id].id = value.id;
    users[value.id].markers = Array();
    var color_index = color_chooser.color_index;
    var colors = color_chooser.matrix;
    var factor = Math.floor(color_index/7+1);
    users[value.id].color = 'rgb('+colors[color_index%7][0]/factor+','
                                  +colors[color_index%7][1]/factor+','
                                  +colors[color_index%7][2]/factor+')';
    color_chooser.color_index++;
    users[value.id].visible = true;    // user is visible initially
    users[value.id].name = value.first_name+' '+value.last_name;
    users[value.id].phone_number = value.phone_number;
    users[value.id].email = value.email;
    create_checkbox(value);
}

function create_marker(value)
{
    var user = users[value.id];
    var markers = user.markers;
    var i = markers.length;
    var latlng = new google.maps.LatLng(value.latitude, value.longitude);
    if (i > 0)
    {
        var prev_marker = markers[i-1];
        if (prev_marker.position.equals(latlng))
            return;
        prev_marker.infowindow.close();
    }

    // create new marker at the end of markers array
    var curr_marker = markers[i] = new google.maps.Marker({
        icon:{
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: user.color,
            strokeOpacity: 1.0,
            strokeColor: user.color,
            strokeWeight: 3.0, 
            scale: 2,
        },
        position: latlng,
        map: map,
    });
    curr_marker.setVisible(user.visible);
    var div = document.createElement("div");
    div.innerHTML = "<div style='margin-right: 10px !important' "
                   +"id='infowindow'><font style='color: "+user.color+"'>"
                   +user.name+'<br />Last seen:<br />'
                   +value.gps_update_time
		   +"</font></div>";
    var pic = document.createElement("img");
    pic.src = "<?php echo base_url('web/pics/users'); ?>/"+user.email+".jpg";
    pic.style.height = "60px";
    pic.onload = function(){
        div.appendChild(pic);
    }
    curr_marker.infowindow = new google.maps.InfoWindow(
        {
            content: div,
            position: latlng
        });
    if (user.visible) curr_marker.infowindow.open(map, curr_marker);
    
    google.maps.event.addListener(curr_marker, 'click', function() {
                if (curr_marker.infowindow.getMap()) 
                     curr_marker.infowindow.close();
                else curr_marker.infowindow.open(map, curr_marker);
    });
}
// handle the directions service
function draw_route(value)
{
    var user = users[value.id];
    var markers = user.markers;
    var i = markers.length - 1;
    if (i <= 0) return;
    var curr_marker = markers[i];
    if (curr_marker.directionsDisplay) return;
    var prev_marker = markers[i-1];
    
    curr_marker.directionsService = new google.maps.DirectionsService();
    curr_marker.directionsDisplay = new google.maps.DirectionsRenderer({
                                    suppressMarkers: true,
                                    preserveViewport: true,
                                    polylineOptions: {
                                        strokeColor: user.color
                                    }
                                });
    curr_marker.directionsService.route
    (
        {
            origin: prev_marker.position,
            destination: curr_marker.position,
            travelMode: google.maps.TravelMode.DRIVING
        }, 
        function(result, status) 
        {
            if (status == google.maps.DirectionsStatus.OK) 
            {
              curr_marker.directionsDisplay.setDirections(result);
              if (user.visible)
                curr_marker.directionsDisplay.setMap(map);
            }
        }
    );
}

function create_checkbox(value)
{
    var user = users[value.id];

    var markers = users[value.id].markers;

    var label= document.createElement("label");
    label.style.color = user.color;
    label.style.width = "100%";
    label.style.right = "2px";
    label.style.float = "left";
    label.style.cursor = "pointer";
    label.style.borderBottom = "2px solid "+user.color;
    label.style.padding = "5px 2px 5px 2px";
    
    user.checkbox = document.createElement("input");
    user.checkbox.type = "checkbox";
    user.checkbox.checked = true;
    label.appendChild(user.checkbox);
    
    var name = document.createElement("span");
    name.innerHTML = user.name+"<br />";
    label.appendChild(name);
    
    var phone = document.createElement("span");
    phone.innerHTML = "phone: "+user.phone_number;
    phone.style.marginLeft = "20px";
    label.appendChild(phone);
    
    var pic = document.createElement("img");
    pic.src = src="<?php echo base_url('web/pics/users'); ?>/"+user.email+".jpg";
    pic.style.height = "60px";
    pic.onload = function(){
        label.appendChild(pic);
    }

    document.getElementById('users_container').appendChild(label);
    
    user.checkbox.onclick = function() 
    {        
        if (user.checkbox.checked) 
        {
            user.visible = true;
            var i = markers.length-1;
            markers[i].infowindow.open(map, markers[i]);
            for (var x in markers)
            {
                markers[x].setVisible(true);
                if (x > 0) markers[x].directionsDisplay.setMap(map);
            }
        } 
        else 
        {
            user.visible = false;
            for (var x in markers)
            {
                markers[x].infowindow.close();
                markers[x].setVisible(false);
                if (x > 0) markers[x].directionsDisplay.setMap(null);
            }
        }
    }
}

function refresh_map()
{
    $.ajax(
    {
        url : 'map/map/show_users_on_map',
        type: 'POST',
        data:{
            csrf_test_name: $.cookie('csrf_cookie_name'),
            json: 'json'
        },
        success:function(data)
        {
            show_users_on_map($.parseJSON(data));
            setTimeout(refresh_map, 5*60*1000);
        },
        error: function(data)
        {
        }
    });
}
setTimeout(refresh_map, 1*3*1000);
</script>
