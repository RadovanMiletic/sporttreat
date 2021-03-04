<?php
/*Template Name: Contact Us*/
get_header();?>
<div class="contact row">
  <div class="container">
    <?php  
    
    while ( have_posts() ) :
          the_post();

          the_content();

        endwhile; // End of the loop.
    
    ?>
  </div>
</div>
<?php
get_footer();
?>