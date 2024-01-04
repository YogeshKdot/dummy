(function($) {
    "use strict"; // Start of use strict

    function renderBlocks(item) {

        var block_id = 'block-' + item['id'];
        blockManager.add(block_id, {
            label: `<div class='gjs-block-customize'>
                 <img src="${item['thumb']}" class="block-image" />
                 <div class="my-label-block">${item['name']}</div>
               </div>`,
            category: item['block_category'],
            content: item['content'],
        });
    }

    var editor = grapesjs.init({
        height: '100%',
        showOffsets: 1,
        noticeOnUnload: 0,
        avoidInlineStyle: 1,
        storageManager: {
            type: 'remote',
            stepsBeforeSave: 5,
            urlStore: urlStore,
            urlLoad: urlLoad,
            params: {
                [csrfName]: csrfHash
            },
        },
        container: '#gjs',
        fromElement: true,
        assetManager: {
            multiUpload: false,
            upload: upload_Image,
            params: {
                [csrfName]: csrfHash
            },
            uploadName: 'files',
            assets: images_url,
        },
        plugins: ['gjs-preset-webpage'],
    });

    
    editor.on('storage:start:store', (e) => {
        // change contentype for codeigniter csrf
        editor.StorageManager.get('remote').set({ contentTypeJson: false, headers: { 'X-Requested-With': "XMLHttpRequest" } }); 
    });

    // Show button page
    var btnPage = document.querySelector('.btn-page-group');
    var btnPanel = document.querySelector('.gjs-pn-commands');
    btnPanel.appendChild(btnPage);
    
    // render blocks
    var blockManager = editor.BlockManager;
    blocks.forEach(renderBlocks);

    // add default css on template
    const head = editor.Canvas.getDocument().head;
    head.insertAdjacentHTML('beforeend', `
        <link rel="stylesheet" href="${url_default_css_template}">
        <link rel="stylesheet" href="${blockscss}">
      `);

    editor.on('asset:remove', (response) => {
        var src = response.get('src');
        var data = {
            [csrfName]: csrfHash,
            image_src: src
        };
        $.ajax({
            url: url_delete_image,
            type: 'POST',
            data: data,
        });
    });


    var pn = editor.Panels;
    [
        ['open-sm', 'Style Manager'],
        ['open-layers', 'Layers'],
        ['open-blocks', 'Blocks']
    ]
    .forEach(function(item) {
        pn.getButton('views', item[0]).set('attributes', {
            title: item[1],
            'data-tooltip-pos': 'bottom'
        });
    });
    

    // Show borders by default
    //pn.getButton('options', 'sw-visibility').set('active', 1);
    // Do stuff on load
    editor.on('load', function() {
        var $ = grapesjs.$;
        document.getElementById('gjs-clm-label').innerHTML = 'Events';
        var selectobject = document.getElementById("gjs-clm-states");
        for (var i=0; i<selectobject.length; i++) {
            if (selectobject.options[i].text == 'Even/Odd')
                selectobject.remove(i);
        }
        // Make private already inserted selectors
        editor.SelectorManager.getAll().each(selector => selector.set('private', 1));

        // All new selectors will be private
        editor.on('selector:add', selector => selector.set('private', 1));

        // Load and show settings and style manager
        var openTmBtn = pn.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        var openSm = pn.getButton('views', 'open-sm');
        openSm && openSm.set('active', 1);

        // Add Settings Sector
        var traitsSector = $('<div class="gjs-sm-sector no-select">' +
            '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
            '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        var traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-trt-traits'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-title').on('click', function() {
            var traitStyle = traitsProps.get(0).style;
            var hidden = traitStyle.display == 'none';
            if (hidden) {
                traitStyle.display = 'block';
            } else {
                traitStyle.display = 'none';
            }
        });

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);
        const assetManager = editor.AssetManager;
        editor.on('asset:upload:response', (response) => {
            
            if (response.error !== undefined ) {
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.error,
                });
                
            } else {
                assetManager.add(response);       
            }

        });
    });
    

    editor.on('storage:end:load', (e) => {
        $('#loadingMessage').css('display', 'none');
    });

     // event seleted
    editor.on('component:selected', model => {
        if (model.get('tagName') == "i") {
            $('#icons-modal-list').data('ccid', model.ccid); //setter
            modal.style.display = "block";
        }
    });
    
    // Get the modal
    var modal = document.getElementById("myModalIcons");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("modal_close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Collapsed Category in Blocks 
    const categories = editor.BlockManager.getCategories();
    categories.each(category => {
        category.set('open', false).on('change:open', opened => {
            opened.get('open') && categories.each(category => {
                category !== opened && category.set('open', false)
            })
        })
    })


   

    $('#icons-modal-list').on('click', '*', function(e) {
        var ccid = $('#icons-modal-list').data("ccid");
        if (ccid) {
            var component = editor.DomComponents.getWrapper().find("#" + ccid)[0];
            component.setClass(this.className);
            modal.style.display = "none";
        }
    });

    $("#input-icon-search").on('change', function postinput() {
        var keyword = $(this).val();
        var data = {
            [csrfName]: csrfHash,
            keyword: keyword
        };
        $.ajax({
            url: url_search_icon,
            type: 'POST',
            data: data,

        }).done(function(responseData) {

            $("#icons-modal-list").html(responseData.result);

        }).fail(function() {});

    });

    $("#save-builder").on("click", function(e) {
        editor.store(function(res) {

            var data = JSON.parse(res);
            var html = '';
            if (data.error !== undefined ) {
                html = '<i class="fa fa-times-circle text-error"></i><small> ' + data.error + '</smal>';
            } else {
                html = '<i class="fa fa-check-circle text-success"></i><small> ' + data.success + '</smal>';
            }
            Swal.fire({
                position: 'top-end',
                timer: 3000,
                toast: true,
                html: html,
                showConfirmButton: false,
            });
        });
    });



    if ($(window).width() <= 768) {
        $('#loadingMessage').css('display', 'none');
        $('#mobileAlert').css('display', 'block');
    }


    $("#back-button").on("click", function(e) {
        window.location.href = back_button_url;

    });


    $("#publish-builder").on("click", function(e) {
        window.location.href = publish_button_url;
    });

})(jQuery); // End of use strict