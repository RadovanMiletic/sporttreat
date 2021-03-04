<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sportstring
 */
?>

</div><!-- #content -->
<div class="footer row">
  <img class="logo" src="<?php echo wp_get_attachment_image_src( 5, 'full', false )[0]  ?>" alt="">
  <div class="footer-links container m-auto row">
    <div class="col-3">
      <a href="#">Home</a>
      <a href="#">Services</a>
      <a href="#">About</a>
    </div>
    <div class="col-3">
      <a href="#">People</a>
      <a href="#">Blog</a>
      <a href="#">FAQ</a>
    </div>
    <div class="col-3">
      <a href="#">Shop</a>
      <a href="#">Registration Form</a>
      <a href="#">Contact</a>
    </div>
    <div class="col-3">
      <a href="#">Sponsorship</a>
      <a href="#">Useful Links</a>
    </div>
  </div>
  <div class="social-links container m-auto">
    <a href=""><img class="social-logo" src="<?php echo wp_get_attachment_image_src( 157, 'full', false )[0]  ?>"
        alt=""></a>
    <a href=""><img class="social-logo" src="<?php echo wp_get_attachment_image_src( 160, 'full', false )[0]  ?>"
        alt=""></a>
    <a href=""><img class="social-logo" src="<?php echo wp_get_attachment_image_src( 159, 'full', false )[0]  ?>"
        alt=""></a>
    <a href=""><img class="social-logo" src="<?php echo wp_get_attachment_image_src( 158, 'full', false )[0]  ?>"
        alt=""></a>
  </div>
  <div class="partners-wrapper container-fluid">
    <div class="partners container">
      <div class="row">
        <a class="col" href=""><img class="social-logo img-fluid"
            src="<?php echo wp_get_attachment_image_src( 165, 'full', false )[0]  ?>" alt=""></a>
        <a class="col" href=""><img class="social-logo img-fluid"
            src="<?php echo wp_get_attachment_image_src( 161, 'full', false )[0]  ?>" alt=""></a>
        <a class="col" href=""><img class="social-logo img-fluid"
            src="<?php echo wp_get_attachment_image_src( 164, 'full', false )[0]  ?>" alt=""></a>
        <a class="col" href=""><img class="social-logo img-fluid"
            src="<?php echo wp_get_attachment_image_src( 162, 'full', false )[0]  ?>" alt=""></a>
        <a class="col" href=""><img class="social-logo img-fluid"
            src="<?php echo wp_get_attachment_image_src( 163, 'full', false )[0]  ?>" alt=""></a>
      </div>
    </div>
  </div>
  <h3>&middot; Copyright &copy; 2019 Sportreat &middot;</h3>
</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>