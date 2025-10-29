
<script type="text/javascript">
"use strict";
    $(document).ready(function() {
        $('.ol-select22').select2();
    });

    var map = L.map('map').setView([40.706486, -74.014700], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 5,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Create a popup
    var popup = L.popup();

    // Define a function to handle map clicks
    function onMapClick(e) {
        var lat = e.latlng.lat.toFixed(5);  
        var lng = e.latlng.lng.toFixed(5);
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked at:<br>Latitude: " + lat + "<br>Longitude: " + lng)
            .openOn(map);
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }
    map.on('click', onMapClick);

    function listing_image_delete(url, key){
        $.ajax({
            url: url,
            success: function(result){
                if(result == 1){
                    $("#image-icon"+key).hide();
                }
            }
        });
    }

    function listing_floor_plan_delete(url, key){
        $.ajax({
            url: url,
            success: function(result){
                if(result == 1){
                    $("#floor-plan-icon"+key).hide();
                }
            }
        });
    }


    document.getElementById('listing-icon-image').addEventListener('change', function(event) {
        const imageContainer = document.getElementById('image-container');
        const files = event.target.files;

        for (const file of files) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageIcon = document.createElement('div');
                imageIcon.classList.add('image-icon');
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Selected image';
                
                const trashIcon = document.createElement('i');
                trashIcon.classList.add('fas', 'fa-trash-alt');
                trashIcon.addEventListener('click', function() {
                    imageIcon.remove();
                });

                imageIcon.appendChild(img);
                imageIcon.appendChild(trashIcon);
                imageContainer.appendChild(imageIcon);
            }
            
            reader.readAsDataURL(file);
        }
    }); 

    $("#country").on('change', function(){
        var country = $("#country").val();
        var url = "{{route('admin.country.city',['id'=>':id'])}}";
        url = url.replace(':id', country);
        $.ajax({
            url: url,
            success: function(result){
                var cityDropdown = $("#city");
                cityDropdown.html($('<option>', {
                        value: '',
                        text: "{{get_phrase('Select listing City')}}"
                    }));
                $.each(result, function(index, city) {
                    cityDropdown.append($('<option>', {
                        value: city.id,
                        text: city.name
                    }));
                });
            }
        })
    })

    function team_select(key) {
        var checkbox = document.getElementById('flexCheckDefault' + key);
        document.getElementById('team-checked' + key).classList.toggle('d-none');
    }
    function menu_select(key) {
        var checkbox = document.getElementById('flexCheckDefault' + key);
        document.getElementById('menu-checked' + key).classList.toggle('d-none');
    }
    function service_select(key) {
        var checkbox = document.getElementById('flexCheckDefau' + key);
        document.getElementById('service-checked' + key).classList.toggle('d-none');
    }
    function feature_select(key) {
        var checkbox = document.getElementById('flexCheckDefau' + key);
        document.getElementById('feature-checked' + key).classList.toggle('d-none');
    }
    function room_select(key) {
        var checkbox = document.getElementById('flckDefault' + key);
        document.getElementById('room-checked' + key).classList.toggle('d-none');
    }

</script>
