<?php
/*Template Name: Faqs*/
get_header();?>
<div style="background-image: url(<?php echo wp_get_attachment_image_src( 243, 'full', false )[0]  ?>); background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;" class="faqs-intro row">
  <div class="container faqs-intro-text">
    <img src="<?php echo wp_get_attachment_image_src( 244, 'full', false )[0]  ?>" alt="">
    <h1>Ask A Specialist</h1>
    <p>If you have a general query, you’re not sure if you need to see
a professional, or you’re simply seeking some clarification use this
feature to ask one of our experienced physiotherapists
and they’ll get back to you as soon as possible.</p>
  </div>
</div>
<div class="faqs-form-wrapper row">
  <div class="container faqs-form">
    <?php echo do_shortcode('[contact-form-7 id="247" title="Contact form 1"]') ?>
    <p><i>Private questions will not be displayed in the answers list below!</i></p>
  </div>
</div>
<div class="featQuestions row">
  <div class="container">
    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 248, 'full', false )[0]  ?>" alt="">
    <h1>Featured Questions</h1>
    <div class="row">
      <div class="col-lg-5 select-ctg box">
        <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 249, 'full', false )[0]  ?>" alt="">
        <h1>Select a Category...</h1>
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
      </div>
      <div class="col-lg-7 select-questions box">
        <ul class="questions">
          <?php
            for($i=1; $i <= 40; $i++): ?>
              <li><img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 250, 'full', false )[0]  ?>" alt=""> Question <?=$i ?>?
              <div class="questions-info-wrap">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum eum tempora commodi ipsum repellat dolorum, voluptatum eius ad doloribus quod sunt quos porro quas neque totam accusamus nam exercitationem atque!</p>
                <div class="question-info">
                  <p class="likeButton">Physiotherapy</p>
                  <div class="time">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 251, 'full', false )[0]  ?>" alt="">
                    <p>01.01.2018</p>
                  </div>
                  <div class="therapist">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 249, 'full', false )[0]  ?>" alt="">
                    <p class="therapistName">Therapist Name </p>
                  </div>
                  

                  <div class="social-info">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 252, 'full', false )[0]  ?>" alt="">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 253, 'full', false )[0]  ?>" alt="">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 254, 'full', false )[0]  ?>" alt="">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 255, 'full', false )[0]  ?>" alt="">
                    <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( 256, 'full', false )[0]  ?>" alt="">
                  </div>
                </div>
              </div>
              </li>
          <?php endfor; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="nearfooter-contact faqs-nearfooter row">
  <h1>Contact us today to see how we can help!</h1>
  <button type="button" class="btn green">CALL NOW</button>
  <button type="button" class="btn">EMAIL</button>
</div>
<?php
get_footer();
?>