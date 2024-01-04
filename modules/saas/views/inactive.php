<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Inactive Service">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-navbutton-color" content="#2a2a2a">
    <title><?php echo $title ?? ''; ?></title>
	<link href="<?php echo base_url() . 'modules/saas/assets/css/inactivetenant.css'; ?>" rel="stylesheet" type="text/css"/>
</head>

<body>

<header>
    <div class="jumbotron jumbotron-lg jumbotron-fluid mb-0 pb-3 bg-primary position-relative">
        <div class="container-fluid text-white h-25">
            <div class="d-lg-flex align-items-center justify-content-between text-center pl-lg-5">
                <div class="col pt-4 pb-4">
                    <h1 class="display-3"><?php echo $message ?? ''; ?></h1>
                    <?php if (isset($get_intouch_link)) { ?>
                        <h5 class="font-weight-light mb-4"><?php echo _l('please_contact_your_service_provider'); ?></h5>
                        <a href="<?php echo $get_intouch_link ?? ''; ?>" target="_blank" class="btn btn-lg btn-outline-white btn-round"><?php echo _l('get_in_touch'); ?></a>
                    <?php } ?>
                </div>
                </div>
        </div>
    </div>
    <svg style="-webkit-transform:rotate(-180deg); -moz-transform:rotate(-180deg); -o-transform:rotate(-180deg); transform:rotate(-180deg); margin-top: -1px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewbox="0 0 1440 126" style="enable-background:new 0 0 1440 126;" xml:space="preserve">
    <path class="bg-primary" d="M685.6,38.8C418.7-11.1,170.2,9.9,0,30v96h1440V30C1252.7,52.2,1010,99.4,685.6,38.8z"/>
    </svg>
</header>
<footer>
<br><br><br>
<div class="container-fluid">
    <div class="d-lg-flex align-items-center justify-content-between text-center pl-lg-5">
        <div class="col pt-4 pb-4">
            <?php echo _l('inconvenience'); ?>
        </div>
    </div>
</div>
</footer>
</body>
</html>
