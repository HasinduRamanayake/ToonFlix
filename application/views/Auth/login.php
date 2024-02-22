<?php

?>
<html>
    <head>
    <link rel="stylesheet" href="assets/register.css">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.4.0/backbone-min.js"></script>
    <script src="/toonflix/frontend/js/backbone_app/model.js"></script>
    <!-- <script src="/toonflix/frontend/js/backbone_app/collection.js"></script> -->
    <script src="/toonflix/frontend/js/backbone_app/view.js"></script>


    <script>      

        $(document).ready(function() {
            new LoginView();   
        });
    </script>

    </head>

    <body>

        <form id="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" required>

            <button type="submit">Login</button>
        </form>
    </body>
</html>