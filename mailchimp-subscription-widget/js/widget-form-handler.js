(function($) {
    $('#mcsw-form').submit(function(event) {
        var formData = {
            'firstName' : $('input#mcsw-firstName').val(),
            'lastName'  : $('input#mcsw-lastName').val(),
            'email'     : $('input#mcsw-email').val(),
            'status'    : $('input#mcsw-status').val(),
            'action'    : 'mcsw_process'
        };

        $.ajax({
            type: 'POST',
            url: mcswData.ajaxPath,
            data: formData,
            dataType: 'json'
        })
        .done(function(data) {
            if ( data['id'] ) {
                $('#mcsw-form').slideUp();
                $('#mcsw-message').html( mcswData.sucessMessage );
            } else if ( data['title'] == 'Member Exists' ) {
                $('#mcsw-form').slideUp();
                $('#mcsw-message').html( mcswData.subscribedMessage );
            } else {
                $('#mcsw-message').html( mcswData.errorMessage );
            }
        })
        .fail(function(req, error) {
            if ( error === 'error' ) {
                error = req.statusText;
            }
            console.log(error);
            $('#mcsw-message').html( mcswData.errorMessage );
        });

        return false;
    });
})(jQuery);