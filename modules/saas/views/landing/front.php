<!--
			=============================================
				Theme Main Menu
			==============================================
			-->
<div class="theme-main-menu sticky-menu theme-menu-six bg-none fixed">
	<div class="d-flex align-items-center">
		<div class="logo order-lg-1"><a href="<?php echo base_url('authentication/login'); ?>"><img src="<?php echo base_url('uploads/company/'.get_option('company_logo_dark')); ?>" alt="" width="200px"></a></div>

		<div class="right-widget order-lg-3">
			<ul class="d-flex align-items-center">
				<li>
					<a href="<?php echo base_url('authentication/login'); ?>" class="signIn-action d-flex align-items-center">
						<img src="/modules/saas/assets/landing/images/icon/120.svg" alt="">
						<span>Login</span>
					</a>
				</li>
				<?php if (1 == get_option('allow_registration')) { ?>
					<li>
						<a href="#pricing" class="signup-btn">Sign up</a>
					</li>
				<?php } ?>
			</ul>
		</div>

		<nav id="mega-menu-holder" class="navbar navbar-expand-lg ml-lg-auto order-lg-2">
			<div class="nav-container">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
            </button>
				<button class="navbar-toggler">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
				</button>
				<div class="navbar-collapse collapse" id="navbarSupportedContent">
					<div class="d-lg-flex justify-content-between align-items-center">
						<ul class="navbar-nav main-side-nav" id="one-page-nav">
							<li class="nav-item">
								<a href="#about" class="nav-link">About</a>
							</li>
							<li class="nav-item">
								<a href="#pricing" class="nav-link">Pricing</a>
							</li>
							<li class="nav-item">
								<a href="#features" class="nav-link">Features</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('saas/tenants/find'); ?>" class="nav-link">Find my tenant</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</nav>
	</div>
</div> <!-- /.theme-main-menu -->


<!--
			=============================================
				Theme Hero Banner
			==============================================
			-->
<div class="hero-banner-nine lg-container">
	<img src="/modules/saas/assets/landing/images/shape/190.svg" alt="" class="shapes bg-shape">
	<div class="container">
		<div class="illustration-container">
			<img src="/modules/saas/assets/landing/images/assets/screen_17.png" alt="">
		</div>
		<div class="row">
			<div class="col-lg-6">
				<h1 class="hero-heading">A simplified <span>CRM <img src="/modules/saas/assets/landing/images/shape/189.svg" alt=""></span> for all your needs.</h1>
				<p class="hero-sub-heading">This landing page showcases a <strong>SaaS service CRM</strong> based on <strong>Perfex</strong> and using our <strong>SaaS module</strong>. This message is a placeholder text that can be replaced at your landing page, according to your requirements.</p>
				<form action="#">
					<input type="email" placeholder="info@themesic.com">
					<button class="d-flex justify-content-center align-items-center"><img src="/modules/saas/assets/landing/images/icon/119.svg" alt=""></button>
									<p class="term-text">* This newsletter form is not working on our demo page but it can be used as a subscription form at production</p>
				</form>

			</div>
		</div>
	</div>
	<div class="partner-slider-two mt-130 md-mt-100">
		<div class="container">
			<p>Simplifying Customer Relations for more than <span>2,000</span> businesses!</p>
			<div class="partnerSliderTwo">
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-21.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-22.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-23.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-24.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-25.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-22.png" alt=""></div>
				</div>
				<div class="item">
					<div class="img-meta d-flex align-items-center"><img src="/modules/saas/assets/landing/images/logo/logo-24.png" alt=""></div>
				</div>
			</div>
		</div>
	</div> <!-- /.partner-slider-two -->
</div> <!-- /.hero-banner-nine -->



<!--
			=============================================
				Fancy Feature Twenty Two
			==============================================
			-->
<div class="fancy-feature-twentyTwo mt-200 md-mt-120" id="about">
	<div class="container">
		<div class="row">
			<div class="col-xl-7 col-md-8 m-auto" data-aos="fade-up" data-aos-duration="1200">
				<div class="title-style-eight text-center mb-40 md-mb-10">
					<h2>One CRM. For any <span>business <img src="/modules/saas/assets/landing/images/shape/191.svg" alt=""></span></h2>
				</div>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200">
				<div class="block-style-twentyTwo">
					<div class="icon d-flex align-items-center justify-content-center" style="background:#FF4A8B;"><img src="/modules/saas/assets/landing/images/icon/121.svg" alt=""></div>
					<h4>Estimates</h4>
					<p>Create catchy estimates for your new projects and turn recipients to Customers.</p>
				</div> <!-- /.block-style-twentyTwo -->
			</div>
			<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
				<div class="block-style-twentyTwo">
					<div class="icon d-flex align-items-center justify-content-center" style="background:#6D49FF;"><img src="/modules/saas/assets/landing/images/icon/122.svg" alt=""></div>
					<h4>Invoices</h4>
					<p>Invoice your customers with recurringness, subscriptions and a lot of amazing features.</p>
				</div> <!-- /.block-style-twentyTwo -->
			</div>
			<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
				<div class="block-style-twentyTwo">
					<div class="icon d-flex align-items-center justify-content-center" style="background:#FFB951;"><img src="/modules/saas/assets/landing/images/icon/123.svg" alt=""></div>
					<h4>Support</h4>
					<p>Providing amazing support to your customers, through tickets and staff members/departments.</p>
				</div> <!-- /.block-style-twentyTwo -->
			</div>
		</div>
	</div>
</div> <!-- /.fancy-feature-twentyTwo -->

<div class="pricing-section-four mt-200 md-mt-100" id="pricing">
	<div class="container">
		<div class="row">
			<div class="col-xl-10  m-auto">
				<div class="title-style-six text-center">
					<h2>Agency or Individual?<br><strong>Perfex SaaS</strong> got you <span>covered</span>.</h2>
					<br>
					<p>Different packages (adjustable) for different needs.</p>
				</div> <!-- /.title-style-six -->
			</div>
		</div>
		<br><br>
	</div>

	<div class="pricing-table-area-four">
		<div class="tab-content">
			<div class="tab-pane active" id="month">
				<div class="row justify-content-center">
					<?php foreach ($plans as $plan) { ?>
						<div class="col-lg-4 col-sm-6 aos-init aos-animate" data-aos="fade-up" data-aos-duration="1200">
							<div class="pr-table-wrapper <?php echo (1 == $plan->most_popular) ? 'active most-popular' : ''; ?>">
								<div class="pack-name"><?php echo $plan->plan_name; ?></div>
								<div class="pack-details"><?php echo $plan->plan_description; ?></div>
								<div class="top-banner d-md-flex" style="background:#FFF7EB;">
									<div class="price">
										<sup>$</sup><?php echo str_replace('.00', '', $plan->price); ?>
									</div>
									<div>
										<span><?php echo (!empty($plan->taxes)) ? 'Excluding Taxes' : 'Including Taxes'; ?></span>
										<em>With Below Features</em>
									</div>
								</div> <!-- /.top-banner -->
								<i><font size="2rem" color="#572ff6">* Renewal Cycle : Every <?php echo $plan->recurring . ' ' . $plan->recurring_type ?>(s)</font></i>
								<ul class="pr-feature">
									<?php
                                        $selected_limitations = [];
					    if (isset($plan->limitations) && !empty($plan->limitations)) {
					        $selected_limitations = json_decode($plan->limitations, true);
					    }
					    foreach ($limitations as $key => $value) {
					        if (!empty($selected_limitations[$key])) {
					        	if ($selected_limitations[$key] >= 0 || $selected_limitations[$key] == -1) {
					            	echo '<li> Allowed '.$value['label'].': '.(($selected_limitations[$key] == -1) ? 'Unlimited' : $selected_limitations[$key]).'</li>';
					        	}
					        }
					    }
					    ?>
								</ul>
								<?php if (1 == get_option('allow_registration')) { ?>
									<a href="<?php echo site_url('authentication/register?plan='.$plan->id); ?>" class="trial-button">sign up now »</a>
								<?php } ?>
								<div class="trial-text">* No card required</div>
							</div> <!-- /.pr-table-wrapper -->
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div> <!-- /.pricing-table-area-four -->
</div>

<!--
			=============================================
				Fancy Feature Twenty Three
			==============================================
			-->
<div class="fancy-feature-twentyThree mt-170 md-mt-100" id="features">
	<div class="container">
		<div class="title-style-nine text-center pb-180 md-pb-100" data-aos="fade-up" data-aos-duration="1200">
			<h6>Perfex SaaS Features</h6>
			<h2>Easy Customer Relations Management, Invoicing & <span>Work Tracking <img src="/modules/saas/assets/landing/images/shape/192.svg" alt=""></span></h2>
			<p>Same as the paragraph above, this is yet another placeholder text you can change. </p>
		</div>
		<div class="block-style-twentyThree">
			<div class="row align-items-center">
				<div class="col-lg-6 order-lg-last ml-auto" data-aos="fade-left" data-aos-duration="1200">
					<div class="screen-container ml-auto">
						<div class="oval-shape" style="background:#69FF9C;"></div>
						<div class="oval-shape" style="background:#FFF170;"></div>
						<img src="/modules/saas/assets/landing/images/assets/screen_18.png" alt="" class="shapes shape-one">
					</div> <!-- /.screen-container -->
				</div>
				<div class="col-lg-5 order-lg-first" data-aos="fade-right" data-aos-duration="1200">
					<div class="text-wrapper">
						<h6>Tasks Management</h6>
						<h3 class="title">Tasks and Projects, made easy.</h3>
						<p>Manage tasks and project along with the whole team. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
					</div> <!-- /.text-wrapper -->
				</div>
			</div>
		</div> <!-- /.block-style-twentyThree -->

		<div class="block-style-twentyThree">
			<div class="row">
				<div class="col-lg-6">
					<div class="screen-container mr-auto" data-aos="fade-right" data-aos-duration="1200">
						<div class="oval-shape" style="background:#FFDE69;"></div>
						<div class="oval-shape" style="background:#FF77D9;"></div>
						<img src="/modules/saas/assets/landing/images/assets/screen_19.png" alt="" class="shapes shape-two">
					</div> <!-- /.screen-container -->
				</div>
				<div class="col-lg-5 ml-auto" data-aos="fade-left" data-aos-duration="1200">
					<div class="text-wrapper">
						<h6>WORKFLOW MANAGEMENT</h6>
						<h3 class="title">Timesheets and collaboration tools.</h3>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dummy text of the printing.</p>
					</div> <!-- /.text-wrapper -->
				</div>
			</div>
		</div> <!-- /.block-style-twentyThree -->

		<div class="block-style-twentyThree">
			<div class="row">
				<div class="col-lg-6 order-lg-last ml-auto" data-aos="fade-left" data-aos-duration="1200">
					<div class="screen-container ml-auto">
						<div class="oval-shape" style="background:#00F0FF;"></div>
						<div class="oval-shape" style="background:#FC6BFF;"></div>
						<img src="/modules/saas/assets/landing/images/assets/screen_20.png" alt="" class="shapes shape-three">
					</div> <!-- /.screen-container -->
				</div>
				<div class="col-lg-5 order-lg-first" data-aos="fade-right" data-aos-duration="1200">
					<div class="text-wrapper">
						<h6>EXTENDABLE FEATURES</h6>
						<h3 class="title">Modules-ready support.</h3>
						<p>Awesome third-party modules support for every customer. As easy as one-two-three.</p>
					</div> <!-- /.text-wrapper -->
				</div>
			</div>
		</div> <!-- /.block-style-twentyThree -->
	</div>
</div> <!-- /.fancy-feature-twentyThree -->



<!--
			=============================================
				Fancy Feature Twenty Four
			==============================================
			-->
<div class="fancy-feature-twentyFour pt-90 md-pt-60" id="service">
	<div class="container">
		<div class="title-style-nine text-center">
			<h6>Your next, big, SaaS idea</h6>
			<h2>Perfex is able to serve every <span>Business Segment <img src="/modules/saas/assets/landing/images/shape/194.svg" alt=""></span> with our SaaS module.</h2>
		</div>
	</div>
	<div class="bg-wrapper mt-100 md-mt-80">
		<a href="#feedback" class="click-scroll-button scroll-target d-flex justify-content-center"><img src="/modules/saas/assets/landing/images/shape/200.svg" alt=""></a>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #3FE193;"><img src="/modules/saas/assets/landing/images/icon/124.svg" alt=""></div>
							<div class="text">
								<h4>Sports & Fitness</h4>
								<p>Personal trainers, Gyms Fitness classes, Yoga classes Golf classes, Sport items renting</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #FF4F86;"><img src="/modules/saas/assets/landing/images/icon/125.svg" alt=""></div>
							<div class="text">
								<h4>Beauty and Wellness</h4>
								<p>Eyelash extensions , Hair salons, Spa salons Beauty salons, Nail salons</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #FFCF39;"><img src="/modules/saas/assets/landing/images/icon/126.svg" alt=""></div>
							<div class="text">
								<h4>Events & entertainment</h4>
								<p>Art classes, Escape rooms Photographers, Equipment Rental & more.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #6D64FF;"><img src="/modules/saas/assets/landing/images/icon/127.svg" alt=""></div>
							<div class="text">
								<h4>Officials & Financial</h4>
								<p>Embassies and consulates, City councils, Call centers Financial services, Interview scheduling.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #E752FF;"><img src="/modules/saas/assets/landing/images/icon/128.svg" alt=""></div>
							<div class="text">
								<h4>Personal meetings</h4>
								<p>Counselling ,Coaching, Business, Advisory, Spiritual services & more.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #29EEFB;"><img src="/modules/saas/assets/landing/images/icon/129.svg" alt=""></div>
							<div class="text">
								<h4>Driving Lessons</h4>
								<p>Driving Schools, Driving Instructors.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #32B5FF;"><img src="/modules/saas/assets/landing/images/icon/130.svg" alt=""></div>
							<div class="text">
								<h4>Education System</h4>
								<p>Universities, Colleges, Schools, Libraries, Parent meetings, Tutoring lessons.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
				<div class="col-lg-6 d-flex mb-35" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
					<div class="block-style-twentyFour">
						<div class="d-flex align-items-start">
							<div class="icon d-flex align-items-center justify-content-center" style="background: #FFA361;"><img src="/modules/saas/assets/landing/images/icon/131.svg" alt=""></div>
							<div class="text">
								<h4>Medical services</h4>
								<p>Massage & Body Treatments, Dental Clinics, Medical Clinics & more.</p>
							</div>
						</div>
					</div> <!-- /.block-style-twentyFour -->
				</div>
			</div>
		</div>
		<img src="/modules/saas/assets/landing/images/shape/195.svg" alt="" class="shapes shape-one">
		<img src="/modules/saas/assets/landing/images/shape/196.svg" alt="" class="shapes shape-two">
		<img src="/modules/saas/assets/landing/images/shape/197.svg" alt="" class="shapes shape-three">
		<img src="/modules/saas/assets/landing/images/shape/198.svg" alt="" class="shapes shape-four">
		<img src="/modules/saas/assets/landing/images/shape/199.svg" alt="" class="shapes shape-five">
	</div> <!-- /.bg-wrapper -->
</div>



<!--
			=====================================================
				Client Feedback Slider Six
			=====================================================
			-->
<div class="client-feedback-slider-six mt-170 md-mt-120" id="feedback">
	<div class="inner-container">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 m-auto" data-aos="fade-up" data-aos-duration="1200">
					<div class="title-style-nine text-center">
						<h6>Testimonials</h6>
						<h2>What <span>Clients <img src="/modules/saas/assets/landing/images/shape/201.svg" alt=""></span> tell About Us.</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="clientSliderSix style-two">
			<div class="item">
				<div class="feedback-wrapper">
					<span class="ribbon" style="background:#FF47E2;"></span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
					<div class="d-flex align-items-center">
						<img src="/modules/saas/assets/landing/images/media/img_78.png" alt="" class="avatar">
						<h6 class="name">Martin Jonas, <span>USA</span></h6>
					</div>
				</div> <!-- /.feedback-wrapper -->
			</div>
			<div class="item">
				<div class="feedback-wrapper">
					<span class="ribbon" style="background:#00E5F3;"></span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
					<div class="d-flex align-items-center">
						<img src="/modules/saas/assets/landing/images/media/img_79.png" alt="" class="avatar">
						<h6 class="name">Elias Brett, <span>USA</span></h6>
					</div>
				</div> <!-- /.feedback-wrapper -->
			</div>
			<div class="item">
				<div class="feedback-wrapper">
					<span class="ribbon" style="background:#FFCF24;"></span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
					<div class="d-flex align-items-center">
						<img src="/modules/saas/assets/landing/images/media/img_80.png" alt="" class="avatar">
						<h6 class="name">Rashed Ka, <span>Spain</span></h6>
					</div>
				</div> <!-- /.feedback-wrapper -->
			</div>
			<div class="item">
				<div class="feedback-wrapper">
					<span class="ribbon" style="background:#8F6BF6;"></span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
					<div class="d-flex align-items-center">
						<img src="/modules/saas/assets/landing/images/media/img_78.png" alt="" class="avatar">
						<h6 class="name">Martin Jonas, <span>USA</span></h6>
					</div>
				</div> <!-- /.feedback-wrapper -->
			</div>
		</div>
	</div> <!-- /.inner-container -->
</div> <!-- /.client-feedback-slider-six -->



<!--
			=====================================================
				Partner Section One
			=====================================================
			-->
<div class="partner-section-one mt-170 md-mt-90 pb-120 md-pb-80">
	<div class="container">
		<div class="title-style-nine text-center mb-100">
			<h6>Our Partners</h6>
			<h2>They <span>Trust Us <img src="/modules/saas/assets/landing/images/shape/201.svg" alt=""></span> & Vice Versa</h2>
		</div>

		<div class="row justify-content-center">
			<div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200">
				<div class="img-box bx-a">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-1.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="50">
				<div class="img-box bx-b">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-2.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
				<div class="img-box bx-c">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-3.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-2 col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="150">
				<div class="img-box bx-d">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-4.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-2 col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
				<div class="img-box bx-e">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-5.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-4 col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="250">
				<div class="img-box bx-f">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-6.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-2 col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
				<div class="img-box bx-g">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-7.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-3 col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="350">
				<div class="img-box bx-h">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-8.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
			<div class="col-xl-3 col-lg-11 col-md-4 col-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="400">
				<div class="img-box bx-i">
					<a href="#"><img src="/modules/saas/assets/landing/images/logo/p-9.png" alt=""></a>
				</div> <!-- /.img-box -->
			</div>
		</div>
	</div>
	<img src="/modules/saas/assets/landing/images/shape/202.svg" alt="" class="shapes shape-one">
	<img src="/modules/saas/assets/landing/images/shape/203.svg" alt="" class="shapes shape-two">
	<img src="/modules/saas/assets/landing/images/shape/204.svg" alt="" class="shapes shape-three">
	<img src="/modules/saas/assets/landing/images/shape/205.svg" alt="" class="shapes shape-four">
	<img src="/modules/saas/assets/landing/images/shape/206.svg" alt="" class="shapes shape-five">
	<img src="/modules/saas/assets/landing/images/shape/207.svg" alt="" class="shapes shape-six">
	<img src="/modules/saas/assets/landing/images/shape/208.svg" alt="" class="shapes shape-seven">
	<img src="/modules/saas/assets/landing/images/shape/209.svg" alt="" class="shapes shape-eight">
</div> <!-- /.partner-section-one -->




<!--
			=====================================================
				Fancy Short Banner Ten
			=====================================================
			-->
<div class="fancy-short-banner-ten mt-170 md-mt-80">
	<div class="container">
		<div class="row">
			<div class="col-xl-9 col-lg-11 m-auto" data-aos="fade-up" data-aos-duration="1200">
				<div class="text-center">
					<h2>Try our plans FOR FREE</h2>
					<p>Get your 14-days trial today and enjoy the Free version of our services!</p>
				</div>
			</div>
		</div>

			<div class="info-text">* No Credit Card Required. Cancel Anytime</div>

	</div> <!-- /.container -->
	<img src="/modules/saas/assets/landing/images/shape/210.svg" alt="" class="shapes shape-one">
	<img src="/modules/saas/assets/landing/images/shape/211.svg" alt="" class="shapes shape-two">
</div> <!-- /.fancy-short-banner-ten -->
