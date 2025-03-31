<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Poster Design</title>
<style>
  body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
  }
  .container {
    position: relative;
    width: 100%;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  .poster-image {
    width: 100%;
    max-width: 500px;
    margin-bottom: 20px;
    border-radius: 10px;
  }
  .poster-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
  }
  .poster-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
  }
  .poster-text {
    font-size: 18px;
    color: #666;
    margin-bottom: 20px;
  }
  .poster-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }
  .poster-button:hover {
    background-color: #0056b3;
  }
</style>
</head>
<body>

<select id="districtSelect1"  name="district1" onchange="updatePlaces2('districtSelect1', 'placesSelect1')" class="In1">
                                <option value="">Select District</option>
                            </select>

                            <label for="state">Place</label>
                            <div><input type="text" name="places" id="state" readonly></div>

<select id="placesSelect1" name="alt-state" class="In1" onchange="updatePlaceInput()">
    <option value="">Select Place</option>
</select>

<script>
    function updateDistrictsAndPlaces2() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../dis.json', true);
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
            option.textContent = item.District + ',Kerala';
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
        xhr.open('GET', '../dis.json', true);
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
