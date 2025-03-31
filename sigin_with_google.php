<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Sign-In</title>
    <script src="https://accounts.google.com/gsi/client" async></script>
</head>
<body>
    <div id="g_id_onload"
        data-client_id="80186504444-1cdil7t134ac9qdti0ov0ofk4fhln5r3.apps.googleusercontent.com"
        data-context="signin"
        data-ux_mode="popup"
        data-callback="handleCredentialResponse"
        data-auto_prompt="false">
    </div>

    <div class="g_id_signin"
        data-type="standard"
        data-shape="pill"
        data-theme="filled_blue"
        data-text="signin_with"
        data-size="large"
        data-logo_alignment="left">
    </div>

    <div id="registrationMessage"></div>

    <script>
        function handleCredentialResponse(response) {
            const responsePayload = decodeJwtResponse(response.credential);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process.php');
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function() {
                if(xhr.status === 200) {
                    console.log('Data sent to PHP successfully.');
                    const responseData = JSON.parse(xhr.responseText);
                    if (responseData.status === 'success') {
                        document.getElementById('registrationMessage').style.color = "green";
                        document.getElementById('registrationMessage').innerText = responseData.msg;
                        setTimeout(function() {
                            window.location.href = 'user/profile.php';
                        }, 3000); // Redirect to user page after 3 seconds
                    } else if (responseData.status === 'error' && responseData.msg === 'exist') {
                        document.getElementById('registrationMessage').style.color = "green";
                document.getElementById('registrationMessage').innerText = 'Logging in...';
                setTimeout(function() {
        // Check user role and redirect accordingly
      
        if (responseData.userRole == 1) {
            window.location.href = './seller/overview.php'; // Redirect to admin page
        } else if (responseData.userRole === '0') {
            window.location.href = './user/profile.php'; // Redirect to user page
        } else {
            window.location.href = 'generic_login_page.php'; // Redirect to a generic login page if user role is not defined
        }
    }, 2000);  // 3000 milliseconds = 3 seconds
                    } else {
                       
                        document.getElementById('registrationMessage').style.color = "red";
                        document.getElementById('registrationMessage').innerText = 'An error occurred. Login failed';
                    }
                } else {
                    console.error('Error sending data to PHP.');
                }
            };
            xhr.send(JSON.stringify(responsePayload));
        }

        function decodeJwtResponse(token) {
            var base64Url = token.split(".")[1];
            var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
            var jsonPayload = decodeURIComponent(
                atob(base64)
                .split("")
                .map(function (c) {
                    return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
                })
                .join("")
            );

            return JSON.parse(jsonPayload);
        }
    </script>
</body>
</html>
