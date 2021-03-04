<?php
get_header();?>
<div class="shop-intro row" style="background-image: url(<?php echo wp_get_attachment_image_src( 243, 'full', false )[0]  ?>);
  background-size: 100% 100%; background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;" class="resources-triangle">
  <img src="<?php echo wp_get_attachment_image_src( 345, 'full', false )[0]  ?>" alt="">
  <h1>Shop</h1>
</div>
<div class="shop-wrapper row">
  <div class="container">
    <div class="row shop">
      <div class="col-lg-4 categories">
        <img src="<?php echo wp_get_attachment_image_src( 344, 'full', false )[0]  ?>" alt="">
        <h1>Categories</h1>
        <?php  
      get_sidebar();?>
      </div>
      <div class="col-lg-8 products">
        <h3>Home/Shop</h3>
        <?php while ( have_posts() ) :
          the_post();

          the_content();

        endwhile; // End of the loop.
    
      ?>
      </div>
    </div>
  </div>
</div>
<?php
get_footer();
?>