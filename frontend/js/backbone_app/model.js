// Login Model
var LoginModel = Backbone.Model.extend({
    urlRoot: '/toonflix/api/login', // Adjust the endpoint as needed
    defaults: {
        username: '',
        password: ''
    }
});