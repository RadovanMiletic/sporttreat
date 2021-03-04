<?php
/*Template Name: Our Team*/
get_header();?>
<div class="our-team row">
  <div style="background-image: url(<?php echo wp_get_attachment_image_src( 176, 'full', false )[0]  ?>); background-repeat: no-repeat;
    background-size: 100% 100%;" class="our-team-triangle">
    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 177, 'full', false )[0]  ?>" alt="">
    <h1>Meet Our Team</h1>
    <p>Sportreat and TotaLife Care is a dynamic sports and health care facility with a fun and energetic allied health team dedicated to helping you achieve the best results possible.</p>
  </div>
</div>

<?php query_posts(array(
  'post_type' => 'our_team_all',
  'posts_per_page' => -1,
  'orderby'   => 'date',
  'order' => 'ASC',
)); ?>
<!-- <div class="container"> -->
  <div class="all-team-images row">
    <?php
      while (have_posts()) : the_post(); 
      $image = get_field('profile-img');
      $desc = substr(get_field('description'), 0, 200) ?>
        
          <div class="all-team-member col">
          <?php 
            if($image) { ?>
            <a href="<?php echo get_permalink(); ?>">
              <img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
              <h1><?php the_field('name'); ?></h1>
              <span><?php the_field('title'); ?></span>
            </a>
              <hr>
              <p><?php echo $desc ?>...</p>
              <button class="btn" href="<?php the_field('url'); ?>">READ MORE</button>
              <?php } ?>
          </div>        
      <?php endwhile; 
    ?>
  </div>
<!-- </div> -->

<div class="nearfooter-contact row">
  <h1>Contact us today to see how we can help!</h1>
  <button type="button" class="btn green">CALL NOW</button>
  <button type="button" class="btn">EMAIL</button>
</div>
<?php
get_footer();
?>