var PostFormView = Backbone.View.extend({
    el: '#postForm',

    events: {
        'submit': 'submitForm'
    },

    submitForm: function(e) {
        e.preventDefault();

        var formData = new FormData(this.el);

        // Instantiate a new Post model
        var post = new Post();
        
        // Since Backbone does not natively support file uploads, use $.ajax
        $.ajax({
            url: post.urlRoot,
            type: 'POST',
            data: formData,
            processData: false, // Tell jQuery not to process the data
            contentType: false, // Tell jQuery not to set contentType
            success: function(response) {
                console.log('Post created successfully:', response);
                // Here you can redirect the user or clear the form as feedback
            },
            error: function(error) {
                console.error('Failed to create post:', error);
                // Here you should handle errors, display user feedback
            }
        });
    }
});

var postFormView = new PostFormView();