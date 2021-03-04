<?php

get_header();
$image = get_field('profile-img');?>
<div style="background-image: url(<?php echo wp_get_attachment_image_src( 105, 'full', false )[0]  ?>); background-repeat: no-repeat;
  background-size: 100% 100%;" class="single-member-intro row">
  <div class="container single-member-profile">
    <img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
    <h1><?php the_field('name'); ?></h1>
    <span><?php the_field('title'); ?></span>
  </div>
</div>
<div class="single-member-about container">
  <div class="row">
    <div class="col-lg-6 aboutLeft">
        <h1><img class="peopleLogo" src="<?php echo wp_get_attachment_image_src( 201, 'full', false )[0]  ?>" alt=""> About</h1>
        <p><?php the_field('description'); ?></p>
    </div>
    <div class="col-lg-6 aboutRight">
      <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/B_9FyTiq3SA" allowfullscreen></iframe>
      </div>  
    </div>
  </div>
</div>
<div class="nearfooter-contact row">
  <h1>Contact us today to see how we can help!</h1>
  <button type="button" class="btn green">CALL NOW</button>
  <button type="button" class="btn">EMAIL</button>
</div>
<?php
get_footer();
?>