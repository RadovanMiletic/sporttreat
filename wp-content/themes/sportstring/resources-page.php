<?php
/*Template Name: Resources*/
get_header();?>

<div class="resources-intro row" style="background-image: url(<?php echo wp_get_attachment_image_src( 243, 'full', false )[0]  ?>);
  background-size: 100% 100%; background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;" class="resources-triangle">
  <img src="<?php echo wp_get_attachment_image_src( 258, 'full', false )[0]  ?>" alt="">
  <h1>Resources</h1>
</div>
<div class="resources-main row">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 left">
        <div class="posts">
          <?php
            $posts = array(
              array('image' => false, 'video' => true),
              array('image' => true, 'video' => false),
              array('image' => false, 'video' => false),
              array('image' => false, 'video' => false),
              array('image' => false, 'video' => false)
            );
            for($i=0; $i < count($posts); $i++): ?>
          <div class="post">
            <h1>Heading Text</h1>
            <div class="time">
              <p class="likeButton">Physiotherapy</p>
              <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 251, 'full', false )[0]  ?>" alt="">
              <p>01.01.2018</p>
            </div>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Beatae voluptatibus tempore harum ut excepturi
              sint repudiandae voluptatem voluptas mollitia. Laboriosam, consequatur! Eos sequi neque, iusto maiores
              ipsam cupiditate est molestias.</p>
            <div class="social-info row">
              <button type="button" class="btn col-4">READ MORE</button>
              <div class="col-4"></div>
              <div class="col-4">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 252, 'full', false )[0]  ?>" alt="">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 253, 'full', false )[0]  ?>" alt="">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 254, 'full', false )[0]  ?>" alt="">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 255, 'full', false )[0]  ?>" alt="">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 256, 'full', false )[0]  ?>" alt="">
              </div>
            </div>
          </div>
          <?php endfor; ?>
        </div>
      </div>
      <div class="col-lg-4 right d-none d-lg-block">
        <h1>Get in Touch</h1>
        <img src="<?php echo wp_get_attachment_image_src( 260, 'full', false )[0]  ?>" alt="">
        <br>
        <img src="<?php echo wp_get_attachment_image_src( 259, 'full', false )[0]  ?>" alt="">
        <h1>Categories</h1>
        <ul class="categories">
          <li class="active">Physiotherapy</li>
          <li>Sports Medicine Doctor</li>
          <li>Pediatry</li>
          <li>Dietitian</li>
          <li>Exercise Physiology</li>
          <li>Clinical Pilates</li>
          <li>Remedial Massage Therapist</li>
          <li>General</li>
        </ul>
        <br>
        <h1>Featured Posts</h1>
        <ul class="featuredPosts">
          <?php
            for($i=1; $i <= 6; $i++): ?>
          <li class="row">
            <div class="col-4 post-image">
              <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 261, 'full', false )[0]  ?>" alt="">
              <p class="centered">Photo #<?=$i ?></p>
            </div>
            <div class="col-8 post-text">
              <h2>Heading text #<?=$i ?></h2>
              <p class="likeButton">Physiotherapy</p>
              <div class="time">
                <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 251, 'full', false )[0]  ?>" alt="">
                <p>01.01.2018</p>
              </div>
            </div>
          </li>
          <?php endfor; ?>
        </ul>
      </div>
    </div>
  </div>

</div>
<div class="nearfooter-contact resources-nearfooter row">
  <h1>Contact us today to see how we can help!</h1>
  <button type="button" class="btn green">CALL NOW</button>
  <button type="button" class="btn">EMAIL</button>
</div>
<?php
get_footer();
?>