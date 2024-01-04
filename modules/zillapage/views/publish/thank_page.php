<!doctype html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title><?php echo html_escape($page->seo_title); ?></title>
        <meta name="description" content="<?php echo html_escape($page->seo_description); ?>">
        <meta name="keywords" content="<?php echo html_escape($page->seo_keywords); ?>">
        <!-- Apple Stuff -->
        <link rel="apple-touch-icon" href="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->favicon); ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Title">
        <!-- Google / Search Engine Tags -->
        <meta itemprop="name" content="<?php echo html_escape($page->seo_title); ?>">
        <meta itemprop="description" content="<?php echo html_escape($page->seo_description); ?>">
        <meta itemprop="image" content="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->social_image); ?>">

        <!-- Facebook Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo html_escape($page->social_title); ?>">
        <meta property="og:description" content="<?php echo html_escape($page->social_description); ?>">
        <meta property="og:image" content="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->social_image); ?>">
        <meta property="og:url" content="<?php echo base_url(uri_string());  ?>">
        
        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo html_escape($page->social_title); ?>">
        <meta name="twitter:description" content="<?php echo  $page->social_description; ?>">
        <meta name="twitter:image" content="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->social_image); ?>">
        <link rel="icon" href="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->favicon); ?>" type="image/png">
        <!-- MS Tile - for Microsoft apps-->
        <meta name="msapplication-TileImage" content="<?php echo base_url(ZILLAPAGE_IMAGE_PATH.'/uploads/'. $page->favicon); ?>">

        <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/template.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/custom-publish.css'); ?>">
    </head>

    <body class="">
        
        <div id="loadingMessage">
          <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>

        <script type="text/javascript">
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
            csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>',
            codePage = '<?php echo $page->code; ?>';
            window._loadPageLink = '<?php echo base_url("zillapage/getpagejson") ?>';
        </script>
        <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/publish.js'); ?>"></script>
        <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/thank-page.js'); ?>"></script>

    </body>
</html>