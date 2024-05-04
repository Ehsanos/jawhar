<!DOCTYPE html>

<html lang="<?php echo e(app()->getLocale()); ?>">
<head>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Jawhar جوهر</title>



	<!--Css Files -->
	<link rel="shortcut icon" href="<?php echo e(url($setting->logo)); ?>"/>
	<link href="https://fonts.googleapis.com/css?family=Tajawal:300,400,500,700,800&display=swap&subset=arabic" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo e(url('web/css/bootstrap.css')); ?>" >
	<link rel="stylesheet" href="<?php echo e(url('web/css/font-awesome.css')); ?>" >
	<link rel="stylesheet" href="<?php echo e(url('web/css/animate.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(url('web/css/owl.carousel.min.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(url('web/css/owl.theme.default.min.css')); ?>" />

	<link rel="stylesheet" href="<?php echo e(url('web/css/style.css')); ?>" >

	<?php if(app()->getLocale() == 'ar'): ?>
		<link href="<?php echo e(asset('web/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
		<link href="<?php echo e(asset('web/css/rtl.css')); ?>" rel="stylesheet">
	<?php endif; ?>

	<link rel="stylesheet" href="<?php echo e(url('web/css/responsive.css')); ?>" >

	<script src="<?php echo e(url('web/js/jquery-1.12.2.min.js')); ?>"></script>

</head>

<body>

<div class="mobile-menu">
	<div class="menu-mobile">
		<div class="brand-area">

		</div>
		<div class="mmenu">
			<ul>
				<a href="#">
					<img  width="25%" src="<?php echo e(url($setting->logo)); ?>">
				</a>
				<li><a href="#home" data-value="home"><?php echo e(__('website.home')); ?></a></li>
				<li><a href="#about" data-value="about"><?php echo e(__('website.about')); ?></a></li>
				<li><a href="<?php echo e(url(getLocal().'/privacy_policy')); ?>" rel="m_PageScroll2id"><?php echo e(__('website.privacy')); ?></a></li>
				<li><a href="#contact" data-value="contact"><?php echo e(__('website.contact')); ?></a></li>

			</ul>
			<ul class="clearfix">
				<li><?php $lang = LaravelLocalization::getSupportedLocales()['ar']?><a href="<?php echo e(LaravelLocalization::getLocalizedURL('ar', null, [], true)); ?>">العربية</a></li>
				<li><?php $lang = LaravelLocalization::getSupportedLocales()['en']?><a href="<?php echo e(LaravelLocalization::getLocalizedURL('en', null, [], true)); ?>">English</a></li>				</ul>
		</div>
	</div>
	<div class="m-overlay"></div>
</div>
<!--mobile-menu-->

<div class="main-wrapper">
	<header id="header">
		<div class="container">

			<ul class="main_menu clearfix">
				<li><a href="#home" rel="m_PageScroll2id"><?php echo e(__('website.home')); ?></a></li>
				<li><a href="#about" rel="m_PageScroll2id"><?php echo e(__('website.about')); ?></a></li>
				<li><a href="<?php echo e(url(getLocal().'/privacy_policy')); ?>" rel="m_PageScroll2id"><?php echo e(__('website.privacy')); ?></a></li>
				<li><a href="#contact" rel="m_PageScroll2id"><?php echo e(__('website.contact')); ?></a></li>

				<li class="lang-site dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="zmdi zmdi-globe"></i><span class="caret-ar"><i class="fa fa-globe" aria-hidden="true"></i></span></a>
					<ul class="dropdown-menu ul-drop-lang">
						<li><?php $lang = LaravelLocalization::getSupportedLocales()['ar']?><a href="<?php echo e(LaravelLocalization::getLocalizedURL('ar', null, [], true)); ?>">العربية</a></li>
						<li><?php $lang = LaravelLocalization::getSupportedLocales()['en']?><a href="<?php echo e(LaravelLocalization::getLocalizedURL('en', null, [], true)); ?>">English</a></li>
					</ul>
				</li>
			</ul>
			<button type="button" class="hamburger is-closed">
				<span class="hamb-top"></span>
				<span class="hamb-middle"></span>
				<span class="hamb-bottom"></span>
			</button>
		</div>
	</header>
	<!--header-->
	<section class="section_home" id="home">
		<div class="container">
			<div class="row">
				<div class="col-md-5 col-sm-4">
					<div class="home_txt">
						<div class="logo-site">
							<a href="#"><img src="<?php echo e(url($setting->logo)); ?>" alt="" class="img-responsive"></a>
						</div>
						<h2 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.500s"><?php echo e(__('website.get')); ?> <strong><?php echo e(($setting->title)); ?></strong> </h2>
						<p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.600s"><?php echo e(__('website.downloadApplication')); ?></p>
						<ul class="buttons_store clearfix wow fadeInUp animated" data-wow-duration="1s" data-wow-delay="0.600s">
							<li><a href="<?php echo e(($setting->play_store_url)); ?>" class="google_store"><i class="fa fa-play"></i> متجر جوجل</a></li>
							<li><a href="<?php echo e(url('uploads/apk/Jawhar.apk')); ?>" target="_blank" class="google_store"><i class="fa fa-download"></i> رابط مباشر</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-7 col-sm-4">
					<div class="home_thumbs wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.500s">
						<div class="item">
						<!--<img src="<?php echo e(url('web/image/Top.png')); ?>" alt="" class="img-responsive mockup">-->
							<video width="100%" controls>
								<source src="<?php echo e(url('uploads/videos/jawharstores.mp4')); ?>" type="video/mp4">
								Your browser does not support HTML video.
							</video>
						</div>
					</div>
				</div>
			</div>
			<div class="img_cover"></div>
		</div>
	</section>
	<!--section_services-->

	<!--sec_site-->
	<section class="section_about" id="about">
		<div class="container">
			<div class="sec_head">
				<h2><?php echo e(__('website.about')); ?></h2>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="serv_item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.900s">
						<div class="serv_txt">
							<h3><?php echo e(($setting->title)); ?></h3>
							<p><?php echo e(($setting->description)); ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="serv_item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.800s">
						<div class="serv_icon">
							<img src="<?php echo e(url('web/image/Down.png')); ?>" alt="" class="img-responsive">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--section_services-->
	<!--section_application-->
	<section class="section_contact" id="contact">
		<div class="container">
			<div class="sec_head wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.500s">
				<div class="container site_wrapper">
					<div class="row">
						<div class="col-md-4">
							<div class="box_sec text-center">
								<h5><i class="fa fa-map-marker"></i><?php echo e(__('website.address')); ?></h5>
								<p><?php echo e(($setting->address)); ?></p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="box_sec text-center">
								<h5><i class="fa fa-phone"></i><?php echo e(__('website.phone')); ?></h5>
								<p><?php echo e(($setting->phone)); ?></p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="box_sec_last text-center">
								<h5><i class="fa fa-envelope"></i><?php echo e(__('website.email')); ?></h5>
								<p><?php echo e(($setting->info_email)); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<form method="post" class="form_contact" action="<?php echo e(route('contactUs')); ?>"  enctype="multipart/form-data">
				<?php echo e(csrf_field()); ?>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.550s">
							<label for="Name"><?php echo e(__('website.name')); ?></label>
							<input name="name" type="text" class="form-control" id="name" minlength="4" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.650s">
							<label for="Phone"><?php echo e(__('website.phone')); ?></label>
							<input name="mobile" type="number" class="form-control"minlength="7" maxlength="13" id="mobile" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.600s">
							<label for="Email"><?php echo e(__('website.email')); ?></label>
							<input name='email' type="email" class="form-control" id="email" minlength="6" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.650s">
							<label for="subject"><?php echo e(__('website.subject')); ?></label>
							<input name='subject' type="text" class="form-control" id="subject" minlength="4" required>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group mess wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.700s">
							<label for="message"><?php echo e(__('website.message')); ?></label>
							<textarea class="form-control" id="message" name='message' minlength="20" required></textarea>
						</div>
					</div>
				</div>
				<div class="btn_send">
					<button type="submit" class="btn btn_contact wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.750s"><?php echo e(__('website.send')); ?></button>
				</div>
			</form>
		</div>

	</section>
	<!--section_contact-->
	<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="social-media text-left">
						<a href="<?php echo e(($setting->facebook)); ?>"><i class="fa fa-facebook-f"></i></a>
						<a href="<?php echo e(($setting->instagram)); ?>"><i class="fa fa-instagram"></i></a>
						<a href="<?php echo e(($setting->twitter)); ?>"><i class="fa fa-whatsapp"></i></a>
					</div>
				</div>
				<div class="col-md-6">
					<div class="text-right">
						<p class="copyright">© <script>document.write( new Date().getFullYear() );</script> Jawhar .Powered by  <a href="https://hexacit.com/" class="white" ><img width="70px" src="https://hexacit.com/images/logo-page.png" alt="Hexa for Information Technology"></a></p>
					</div>
				</div>
			</div>

		</div>
	</footer>
	<!--footer-->
</div>


<!--JavaScript Files -->


<script src="<?php echo e(url('web/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(url('web/js/jquery.malihu.PageScroll2id.min.js')); ?>"></script>
<script src="<?php echo e(url('web/js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(url('web/js/respond.js')); ?>"></script>
<script src="<?php echo e(url('web/js/wow.js')); ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDluJ2gHFz5urot7mm6NTtsm95Iz7uFpeg&callback=myMap"></script>
<script src="<?php echo e(url('web/js/script.js')); ?>"></script>


<script>
	function myMap() {
		var mapProp= {
			center:new google.maps.LatLng(51.508742,-0.120850),
			zoom:5,
		};
		var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	}
</script>
<script>
	(function($){
		$(window).on("load",function(){

			/* Page Scroll to id fn call */
			$("a[rel='m_PageScroll2id']").mPageScroll2id({
				scrollSpeed: 1200
				,scrollEasing:"easeOutQuint"
				,liveSelector:"a[rel='m_PageScroll2id']"
			});

		});
	})(jQuery);
</script>


</body>

</html>

<?php /**PATH E:\Downloads\jawharBackUp\resources\views/website/index.blade.php ENDPATH**/ ?>