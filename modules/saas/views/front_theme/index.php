<?php defined('BASEPATH') || exit('No direct script access allowed');
echo theme_head_view();
get_template_part($navigationEnabled ? 'navigation' : '');
echo theme_template_view();
?>
<?php
echo theme_footer_view();
?>
<?php
/**
 * Check for any alerts stored in session.
 */
app_js_alerts();
?>
<!--
            =====================================================
                Footer Style Eight
            =====================================================
            -->
<footer class="theme-footer-eight mt-100">
    <div class="top-footer">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-xl-4 col-lg-3 col-12 footer-about-widget">
                    <div class="logo"><a href="<?php echo base_url('authentication/login'); ?>"><img src="<?php echo base_url('uploads/company/'.get_option('company_logo_dark')); ?>" alt="" width="200px"></a></div>
                    <br><?php echo format_organization_info(); ?>
                </div> <!-- /.about-widget -->
                <div class="col-lg-3 col-md-4 footer-list">
                    <h5 class="footer-title">Heading</h5>
                    <ul>
                        <li><a href="#">First Link</a></li>
                        <li><a href="#">Second Link</a></li>
                        <li><a href="#">Third Link</a></li>
                        <li><a href="#">Fourth Link</a></li>
                    </ul>
                </div> <!-- /.footer-list -->
                <div class="col-lg-3 col-md-4 footer-list">
                    <h5 class="footer-title">Legal</h5>
                    <ul>
                        <?php if (is_gdpr()) { ?>
                            <?php if ('1' == get_option('gdpr_show_terms_of_use_in_footer')) { ?>
                                <li>
                                    <a href="<?php echo site_url('saas/terms-of-use'); ?>" class="terms-of-use-footer">
                                        Terms Of Use
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ('1' == get_option('gdpr_show_terms_and_conditions_in_footer')) { ?>
                                <li>
                                    <a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer">
                                        Terms &amp; conditions
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ('1' == get_option('gdpr_show_privacy_policy_in_footer')) { ?>
                                <li>
                                    <a href="<?php echo site_url('privacy-policy'); ?>">
                                        Privacy policy
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div> <!-- /.footer-list -->
                <div class="col-xl-2 col-lg-3 col-md-4 footer-list">
                    <h5 class="footer-title">Heading</h5>
                    <ul>
                        <li><a href="#">First Link</a></li>
                        <li><a href="#">Second Link</a></li>
                        <li><a href="#">Third Link</a></li>
                        <li><a href="#">Fourth Link</a></li>
                    </ul>
                </div> <!-- /.footer-list -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </div>

    <div class="container">
        <div class="bottom-footer mt-50 md-mt-30">
            <div class="row">
                <div class="col-lg-6 order-lg-last mb-20">
                    <ul class="d-flex justify-content-center justify-content-lg-end social-icon">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
                <div class="col-lg-6 order-lg-first mb-20">
                    <p class="copyright text-center text-lg-left">Copyright • <?php echo get_option('companyname'); ?></p>
                </div>
            </div>
        </div>
    </div>
</footer> <!-- /.theme-footer-eight -->


<?php

?>

<!-- Optional JavaScript _____________________________  -->

<!-- Popper.js, then Bootstrap JS -->
<!-- Popper js -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/popper.js/popper.min.js'); ?>"></script>
<!-- Bootstrap JS -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
<!-- AOS js -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/aos-next/dist/aos.js'); ?>"></script>
<!-- js count to -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/jquery.appear.js'); ?>"></script>
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/jquery.countTo.js'); ?>"></script>
<!-- Slick Slider -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/slick/slick.min.js'); ?>"></script>
<!-- Fancybox -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/vendor/fancybox/dist/jquery.fancybox.min.js'); ?>"></script>

<!-- Theme js -->
<script src="<?php echo module_dir_url(SUPERADMIN_MODULE, 'assets/landing/js/theme.js'); ?>"></script>

</div> <!-- /.main-page-wrapper -->
</body>

</html>