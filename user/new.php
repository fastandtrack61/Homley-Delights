
<script>
async function fetchPincodeDetailsByState(state) {
    try {
        // Fetch pincode data for the given state from a state-wise pincode API
        const pincodeResponse = await fetch(`https://example.com/statewise-pincode-api/${state}`);
        const pincodeData = await pincodeResponse.json();
        
        // Extract pincode(s) from the pincode data
        const pincodes = pincodeData.map(entry => entry.pincode);

        // Fetch details for each pincode using the Postal Pincode API
        const detailsPromises = pincodes.map(pincode => fetchPostalPincodeDetails(pincode));
        const detailsResponses = await Promise.all(detailsPromises);
        const detailsData = await Promise.all(detailsResponses.map(response => response.json()));

        // Process and display details data as needed
        console.log(detailsData);
    } catch (error) {
        console.error('Error fetching pincode details:', error);
    }
}

async function fetchPostalPincodeDetails(pincode) {
    // Fetch pincode details from the Postal Pincode API
    return fetch(`https://api.postalpincode.in/pincode/${pincode}`);
}

// Example usage
const states = ['State1', 'State2', 'State3']; // Replace with your list of states
states.forEach(state => fetchPincodeDetailsByState(state));


</script>