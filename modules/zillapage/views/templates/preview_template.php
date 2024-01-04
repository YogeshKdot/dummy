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
    <?php 
       $favicon = get_option('favicon'); 
       $type = "";
       $company_logo = get_option('company_logo' . ($type == 'dark' ? '_dark' : ''));
    ?>

    <link rel="icon" href="<?php echo base_url('uploads/company/'.$favicon); ?>" type="image/png">
    <link rel="shortcut icon" type="image/x-icon" href="" />
    <title><?php echo html_escape($title); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,900" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/preview/core/core/core.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/preview/core/app/css/customize.css'); ?>">
</head>
<body class="">
    <div class="container-fluid pl-0 pr-0">
        <div class="row mr-0">
            <div class="col-lg-12 navbar_section pr-0">
                <div class="navbar-area-start">
                    <div class="row mr-0">
                        <div class="col-lg-3 col-md-12">
                            <div class="navbar-page-list">
                                <div class="page_navbar">
                                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo admin_url('zillapage/landingpages/templates'); ?>">
                                        <div class="sidebar-brand-icon">
                                            <img src="<?php echo base_url('uploads/company/'.$company_logo); ?>" alt="Logo Company" height="40">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="display-view text-center">
                                <a href="#" class="active" id="labtop_device"><i class="fas fa-desktop"></i></a>
                                <a href="#" id="tablet_device"><i class="fas fa-tablet-alt"></i></a>
                                <a href="#" id="mobile_device" class=""><i class="fas fa-mobile-alt"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 text-center">
                            <button id="btn-main-page" class="btn btn-secondary active"><?php echo _l('main_page'); ?></button>
                            <button id="btn-thank-you-page" class="btn btn-secondary"><?php echo _l('thank_you_page'); ?></button>
                        </div>
                        <div class="col-lg-3 col-md-12 text-center">
                            <button class="btn btn-primary btn_builder_template" data-id="<?php echo $item->id ?>" data-toggle="modal" data-target="#createModal"><?php echo _l('use_template'); ?></button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="website-append">
          <iframe id="frameMainPage" src="<?php echo admin_url('zillapage/templates/framemainpage')."/".$item->id; ?>" frameborder="0"></iframe>
          <iframe id="frameThankYouPage" class="d-none" src="<?php echo admin_url('zillapage/templates/framethankyoupage')."/".$item->id; ?>" frameborder="0"></iframe>
       </div>

    </div>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo _l('new_landingpage'); ?></h5>
                </div>
                <?php echo form_open(admin_url('zillapage/landingpages/save'),array('id'=>'form_save_landingpage')); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="number" class="form-control" name="template_id" value=<?php echo $item->id; ?> hidden required="" id="template_id_builder">
                        <label for="name" class="col-form-label"><?php echo _l('name'); ?></label>
                        <input type="text" class="form-control" name="name" required="" id="page-name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-primary" id="saveandbuilder"><?php echo _l('save_builder'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/preview/core/core/core.js'); ?>"></script>
        <script src="<?php echo base_url(ZILLAPAGE_ASSETS_PATH.'/preview/core/app/js/app.js'); ?>"></script>
</body>
</html>