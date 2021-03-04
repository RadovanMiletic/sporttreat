<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sportstring
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Arimo:400,400i,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<script src="https://kit.fontawesome.com/ab3130ba7b.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="container-fluid" id="page" class="site">
	<div class="infoHeader row d-none d-lg-block">
		<div class="container">
			<div class="row">
				<div class="addressInfo">
					<p><img src="<?php echo wp_get_attachment_image_src( 80, 'full', false )[0]  ?>" alt="home"></i>367 Canning Hwy, Palmyra WA 6157</p>
				</div>
				<div class="phoneInfo">
					<p><img src="<?php echo wp_get_attachment_image_src( 81, 'full', false )[0]  ?>" alt="">(08) 9438 2400</p>
				</div>
				<div class="timeInfo ml-auto">
					<img src="<?php echo wp_get_attachment_image_src( 88, 'full', false )[0]  ?>" alt="">
					<div>
						<p>Mon - Fri | <i>7:00 AM - 7:00 PM</i></p>
						<p>Saturday  | <i>8:00 AM - 1:00 PM</i></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light content-area row">
			<a class="navbar-brand" href="#"><img class="logo" src="<?php echo wp_get_attachment_image_src( 5, 'full', false )[0]  ?>" alt=""></a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
				<!-- <img class="hidden-desktop" src="<?php echo wp_get_attachment_image_src( 80, 'full', false )[0]  ?>" alt="home">
				<img class="hidden-desktop" src="<?php echo wp_get_attachment_image_src( 88, 'full', false )[0]  ?>" alt=""> -->
				<div class="navbar-nav ml-auto">
					<?php wp_nav_menu(array('menu_class' => 'nav navbar-nav navbar-right nav-items')); ?>
				</div>
			</div>
		</nav>
	</div>
	
	<div id="content" class="site-content">
