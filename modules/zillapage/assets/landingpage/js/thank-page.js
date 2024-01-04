(function($) {
    "use strict"; // Start of use strict
    // load content page
    $.ajax({
        url: window._loadPageLink,
        type: 'POST',
        data: `&${csrfName}=${csrfHash}&_code=${codePage}`,
        success: function(data) {
            if ($.isEmptyObject(data.error)) {
                $('head').append(data.custom_header);
                $('body').prepend(data.thank_you_page_html);
                $('body').prepend(`<style>${data.thank_you_page_css}</style>`);
                $('body').prepend(`<style>${data.blockscss}</style>`);
                $('body').append(data.custom_footer);
                
                $('#loadingMessage').css('display', 'none');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: data.error,
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr);
        }
    });

})(jQuery); // End of use strict