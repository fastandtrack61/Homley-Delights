<label for="alt-city">City:</label>
                <select id="districtSelect1" name="alt-city" onchange="updatePlaces2('districtSelect1', 'placesSelect1')" class="In1">
    <option value="">Select District</option>
</select>

                <label id="error2"></label>
                <label for="alt-state">State:</label>
                <select id="placesSelect1" name="alt-state" class="In1" >
        <option value="">Select Place</option>
    </select>

<script>

function updateDistrictsAndPlaces2() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', './dis.json', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                populateSelect2('districtSelect1', 'placesSelect1', data); // Corrected IDs
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
    xhr.send();
}


function populateSelect2(districtSelectId, placesSelectId, data) {
    const districtSelect = document.getElementById(districtSelectId);
    const placesSelect = document.getElementById(placesSelectId);

    // Clear previous options
    placesSelect.innerHTML = '<option value="">Select Place</option>';

    // Populating the districts
    const districtNames = new Set(); // Using Set to avoid repetition
    data.sort((a, b) => a.District.localeCompare(b.District));
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.District;
        option.textContent = item.District+',Kerala';
        if (!districtNames.has(item.District)) { // Check if district name already exists
            districtSelect.appendChild(option);
            districtNames.add(item.District); // Add district name to Set
        }
    });
}

function updatePlaces2(districtSelectId, placesSelectId) {
    const districtSelect = document.getElementById(districtSelectId);
    const placesSelect = document.getElementById(placesSelectId);
    const selectedDistrict = districtSelect.value;

    // Clear previous options
    placesSelect.innerHTML = '<option value="">Select Place</option>';

    const xhr = new XMLHttpRequest();
    xhr.open('GET', './dis.json', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const districtData = data.find(item => item.District === selectedDistrict);
                if (districtData) {
                    const places = districtData.Places;
                    const placeNames = new Set(); // Using Set to avoid repetition
                    places.forEach(place => {
                        if (!placeNames.has(place)) { // Check if place name already exists
                            const placeOption = document.createElement('option');
                            placeOption.value = place;
                            placeOption.textContent = place;
                            placesSelect.appendChild(placeOption);
                            placeNames.add(place); // Add place name to Set
                        }
                    });
                } else {
                    console.log('No places found for the selected district.');
                }
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
    xhr.send();
}

// Call function to populate districts and places initially
updateDistrictsAndPlaces2();

</script>

</body>
</html>
