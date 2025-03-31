<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Generation</title>
</head>
<body>
    <input type="text" id="textInput" >
    <button onclick="generateImage()">submit</button>
    <br>
    <div id="imageContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function generateImage() {
            const textin = document.getElementById('textInput').value;
            const options = {
    method: "POST",
    url: "https://api.edenai.run/v2/image/generation",
    headers: {
        authorization: "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiZWI5MDljZjctMTEwZS00MzRlLTlmZmQtYjAzZTE1MThmYjljIiwidHlwZSI6ImFwaV90b2tlbiJ9.Qy_IdicERJnARKeMeLMu04-G-2lGNK_lERB5NeblJ8w",
    },
    data: {
        providers: "openai",
        text: textin,
        resolution: "512x512",
        fallback_providers: "",
      
    },
};


            axios
                .request(options)
                .then((response) => {
    const imageUrl = response.data.openai.items[0].image_resource_url;
    document.getElementById('imageContainer').innerHTML = `<img src="${imageUrl}" alt="Generated Image">`;
})

                .catch((error) => {
                    console.error(error);
                });
        }
    </script>
</body>
</html>
