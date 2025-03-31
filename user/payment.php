<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Loading Animation</title>
  <style>
    .loading-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      width: 0%;
      background-color: #2196F3; /* Blue color */
      z-index: 1000; /* Ensure it's above other content */
      transition: width 0.4s ease-in-out; /* Smooth animation */
    }

    /* Add some animation to make it more noticeable */
    @keyframes pulse {
      0% {
        opacity: 1;
      }
      50% {
        opacity: 0.5;
      }
      100% {
        opacity: 1;
      }
    }

    .loading-bar::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: #2196F3; /* Blue color */
      opacity: 1;
      animation: pulse 1.5s infinite;
    }
  </style>
</head>
<body>
  <!-- Loading Bar -->
  <div class="loading-bar" id="loadingBar"></div>

  <!-- Your content goes here -->
  <h1>This is your content...</h1>

  <!-- Simulate page loading -->
  <script>
    document.onreadystatechange = function () {
      if (document.readyState === "complete") {
        // Page is fully loaded, hide the loading bar
        document.getElementById("loadingBar").style.width = "100%";
        setTimeout(function() {
          document.getElementById("loadingBar").style.display = "none";
        }, 500); // Optional delay to make it more noticeable
      }
    };

    const options = {
  method: 'POST',
  headers: {
    accept: 'application/json',
    'content-type': 'application/x-www-form-urlencoded'
  },
  body: new URLSearchParams({
    grant_type: 'client_credentials',
    client_id: 'DfdUFji2eEieOcPJaXAsjtH6T8OyLn405LklGTuQ',
    client_secret: 'Y0RFAhENeln5WJ1xj3GLXKDFsvbXEr6xA9s3M0BtW9Gzgn7iFXuHsFTiSdKrxyfVJ5ryZeEVUAFza1cpQCDHXrD89ZyKSzJDYptCPsxsyYjHYkwYzWmCxNKgIh9Fr4sJ'
  })
};

fetch('https://api.instamojo.com/oauth2/token/', options)
  .then(response => response.json())
  .then(response => console.log(response))
  .catch(err => console.error(err));
  </script>
</body>
</html>
