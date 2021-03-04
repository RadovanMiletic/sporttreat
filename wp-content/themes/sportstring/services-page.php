<?php
/*Template Name: Services*/
get_header();?>
<div class="our-services row">
  <div style="background-image: url(<?php echo wp_get_attachment_image_src( 176, 'full', false )[0]  ?>);
    background-size: 100% 100%;" class="our-services-triangle">
    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 202, 'full', false )[0]  ?>" alt="">
    <h1>Our services</h1>
  </div>
</div>
<div class="services-links container">
  <div class="row">
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 217, 'full', false )[0]  ?>); 
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 203, 'full', false )[0]  ?>" alt="">
      <h1>Physiotherapy</h1>      
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 214, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 204, 'full', false )[0]  ?>" alt="">
      <h1>Sports Medicine</h1>
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 212, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 205, 'full', false )[0]  ?>" alt="">
      <h1>Podiatry</h1>
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 211, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 206, 'full', false )[0]  ?>" alt="">
      <h1>Dietitian</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 213, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 207, 'full', false )[0]  ?>" alt="">
      <h1>Exercise Physiology</h1>
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 212, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 208, 'full', false )[0]  ?>" alt="">
      <h1>Clinical Pilates</h1>
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 215, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 209, 'full', false )[0]  ?>" alt="">
      <h1>Remedial Massage</h1>
    </div>
    <div class="col-3-lg service" style="background: linear-gradient( rgba(0,0,0,0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo wp_get_attachment_image_src( 216, 'full', false )[0]  ?>);
    background-size: 100% 100%;">
      <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 210, 'full', false )[0]  ?>" alt="">
      <h1>Women's Health</h1>
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