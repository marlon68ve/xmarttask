    $('#search-button').click(function() {
        var phoneNumber = $('#phone').val();
        $.ajax({
            url: $('base').attr('href') + 'checkUserExists',
            type: 'POST',
            data: { phone: phoneNumber },
            success: function(response) {
                // Display modal based on response
                if (response.exists) {
                    // Display modal with user information
                    $('#user-modal').modal('show');
                } else {
                    // Display modal indicating user does not exist
                    $('#user-not-found-modal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking user existence:', error);
            }
        });
    });