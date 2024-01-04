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
    <link rel="shortcut icon" type="image/x-icon" href="" />
    <?php $favicon = get_option('favicon'); ?>
    <link rel="icon" href="<?php echo base_url('uploads/company/'.$favicon); ?>" type="image/png">
    <link rel="shortcut icon" type="image/x-icon" href="" />
    <title><?php echo html_escape($title); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,900" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/css/template.css'); ?>">

</head>
 <body>
    <script>
        window._loadTemplateLink = "<?php echo admin_url('zillapage/templates/gettemplatejson')."/".$item->id; ?>";
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    </script>
    <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/publish.js'); ?>"></script>
    <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/landingpage/js/frame-thank.js'); ?>"></script>
</body>

</html>