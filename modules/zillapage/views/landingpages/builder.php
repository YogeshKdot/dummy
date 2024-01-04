
<!doctype html>
<html dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <?php $favicon = get_option('favicon'); ?>
    <link rel="icon" href="<?php echo base_url('uploads/company/'.$favicon); ?>" type="image/png">
    <link rel="shortcut icon" type="image/x-icon" href="" />
    <title><?php echo html_escape($title); ?></title>

    <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/builder.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/customize.css'); ?>">
    <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/builder.js'); ?>"></script>

    <script type="text/javascript">
        var exits_ecommerce = false;
        var url_get_products = "";
       
    </script>
    <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/grapeweb.js'); ?>"></script>

</head>

 <body>
    <div id="mobileAlert">
      <div class="message">
        <h3><?php echo _l('builder_not_work_on_mobile'); ?></h3>
        <a href ="<?php echo admin_url('zillapage/landingpages/index'); ?>"><?php echo _l('back'); ?></a>
      </div>
    </div>
    <div id="myModalIcons" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal_close">&times;</span>
          <h3>Font awesome icons 5</h3>
        </div>
        <div class="modal-body">
          <div class="div-search-modal-icon">
             <input type="text" name="search" id="input-icon-search" class="form-control-builder" placeholder="<?php echo _l('search'); ?>">
           </div>
         <div id="icons-modal-list">
             <?php 
              foreach($all_icons as $item){
                echo '<i class="' .$item. '"></i>';
              }
             ?>
         </div>
        </div>
        <div class="modal-footer">
          <h3>&nbsp;</h3>
        </div>
      </div>
    </div>
   
    <input type="text" name="code" value="<?php echo $page->code; ?>" hidden class="form-control">
    
    <div id="loadingMessage">
      <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
    <div class="btn-page-group"> 
        <a href="<?php echo admin_url('zillapage/landingpages/builder')."/".$page->code; ?>" class="btn btn-light <?php if($this->uri->segment('6') != 'thank-you-page') { echo 'active'; } ?>" id="btn-main-page"><?php echo _l('main_page'); ?> </a>
        <a href="<?php echo admin_url('zillapage/landingpages/builder')."/".$page->code."/thank-you-page"; ?>" class="btn btn-light <?php if($this->uri->segment('6') == 'thank-you-page') { echo 'active'; } ?>" id="btn-thank-you-page"><?php echo _l('thank_you_page'); ?> </a>
    </div>
    <div id="gjs">
    </div>

    <script type="text/javascript">
      var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
      csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
      var type_page = '<?php echo $this->uri->segment('6'); ?>';
      var urlStore = '<?php echo admin_url('zillapage/landingpages/updatebuilder')."/".$page->code."/".$this->uri->segment('6'); ?>';
      var urlLoad = '<?php echo admin_url('zillapage/landingpages/loadbuilder')."/".$page->code."/".$this->uri->segment('6'); ?>';
      var upload_Image = '<?php echo admin_url('zillapage/landingpages/uploadimage'); ?>';
      var url_delete_image = '<?php echo admin_url('zillapage/landingpages/deleteimage'); ?>';
      var url_search_icon = '<?php echo admin_url('zillapage/landingpages/searchicon'); ?>';
      var url_default_css_template = '<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/template.css'); ?>';
      var back_button_url = "<?php echo admin_url('zillapage/landingpages/index'); ?>";
      var publish_button_url = "<?php echo admin_url('zillapage/landingpages/setting')."/".$page->code; ?>";

      var images_url = JSON.parse(JSON.stringify(<?php echo $images_url; ?>));
      var blockscss = `<?php echo base_url('zillapage/getblockscss'); ?>`;
      var url_get_blocks = `<?php echo admin_url('zillapage/landingpages/getallblocks'); ?>`;

      var blocks = JSON.parse(JSON.stringify(<?php echo $blocks; ?>));
      console.log(blocks);
    </script>
    <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/customize-builder.js'); ?>"></script>
    
  </body>


</html>