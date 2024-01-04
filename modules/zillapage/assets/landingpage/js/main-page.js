(function($) {
    "use strict"; // Start of use strict
    // load content page
    const functionFormSubmit = function() {
        var url = window._formLink.trim();
        var values = $(this).serialize();
        var form = $(this);
        $.ajax({
            url: url,
            type: 'POST',
            data: values + `&${csrfName}=${csrfHash}&_code=${codePage}`,
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    if (data.type_form_submit == 'url') {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = window._thankYouURL;
                    }

                    form.css("display", "none");
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

        return false;
    };


    $.ajax({
        url: window._loadPageLink,
        type: 'POST',
        data: `${csrfName}=${csrfHash}&_code=${codePage}`,
        success: function(res) {

            var data = res;

            if ($.isEmptyObject(data.error)) {

                if(data.custom_header)
                    $('head').append(data.custom_header);
                if(data.main_page_script)
                    $('body').prepend(`<script type="text/javascript">
                                    ${data.main_page_script}
                                 </script>`);
                if(data.html)
                    $('body').prepend(data.html);
                if(data.css)
                    $('body').prepend(`<style>${data.css}</style>`);

                if(data.blockscss)
                    $('body').prepend(`<style>${data.blockscss}</style>`);

                if(data.custom_footer)
                    $('body').append(data.custom_footer);
                
               
                $('#loadingMessage').css('display', 'none');

                $('form').on('submit', functionFormSubmit);

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