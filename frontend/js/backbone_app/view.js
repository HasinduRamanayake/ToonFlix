var LoginView = Backbone.View.extend({
    el: '#login-form',

    events: {
        'submit': 'submitForm'
    },

    submitForm: function(e) {
        e.preventDefault();
        var loginDetails = {
            username: this.$('#username').val(),
            password: this.$('#password').val()
        };
        var loginModel = new LoginModel();
        loginModel.save(loginDetails, {
            success: function(model, response) {
                if (response.status) {
                    // Login was successful
                    console.log('Logged in:', response.data);
                    // Redirect to a new page or change the view
                } else {
                    // Login failed
                    console.error('Login failed:', response.message);
                }
            },
            error: function(model, response) {
                console.error('An error occurred:', response);
            }
        });
    }
});
