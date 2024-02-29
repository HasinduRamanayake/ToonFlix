
var Post = Backbone.Model.extend({
    urlRoot: 'http://localhost/toonflix/api/create_post', 
    defaults: {
        title: '',
        image: null 
    }
});

