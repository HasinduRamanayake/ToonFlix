var AuthView = Backbone.View.extend({
    events: {
        'submit #login-form': 'login',
        'submit #signup-form': 'signUp'
    },

    login: function(e) {
        e.preventDefault();

        var userData = {
            username: this.$('#username').val(),
            password: this.$('#password').val(),
        };

        $.ajax({
            url: 'http://localhost/toonflix/api/auth/login',
            type: 'POST',
            data: userData,
            success: function(response) {
                console.log('Login Successful', response);
                window.location.href = 'file:///C:/xampp/htdocs/ToonFlix/frontend/html/dashboard.html';
            },
            error: function(error) {
                console.log('Login Failed', error);
            }
        });
    },

    signUp: function(e) {
        e.preventDefault();

        var userData = {
            username: this.$('#new-username').val(),
            password: this.$('#new-password').val(),
            email: this.$('#email').val(),
        };
        console.log("usetData",userData);
        $.ajax({
            url: 'http://localhost/toonflix/api/auth/signup',
            type: 'POST',
            data: userData,
            success: function(response) {
                console.log('Sign Up Successful', response);
                // Optionally log in the user directly or show a success message
                alert("Registration successful. Please log in.");
                $('#signup-form').hide();
                $('#login-form').show();
                $('#toggle-form').text("Don't have an account? Sign Up");
            },
            error: function(error) {
                console.log('Sign Up Failed', error);
            }
        });
    }
});

var authView = new AuthView();
