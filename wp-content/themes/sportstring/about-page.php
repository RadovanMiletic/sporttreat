<?php
/*Template Name: About Us*/
get_header();?>

<div style="background-image: url(<?php echo wp_get_attachment_image_src( 104, 'full', false )[0]  ?>); background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;" class="about-us row">
  <div class="container about-us-text">
    <img src="<?php echo wp_get_attachment_image_src( 197, 'full', false )[0]  ?>" alt="">
    <h1>About Us</h1>
  </div>
</div>

<div class="health-care-info row">
  <div style="background-image: url(<?php echo wp_get_attachment_image_src( 167, 'full', false )[0]  ?>); background-repeat: no-repeat;
    background-size: 100% 100%;" class="about-us-triangle">
    <div class="container about-us-triangle-text">
      <img class="logo" src="<?php echo wp_get_attachment_image_src( 5, 'full', false )[0]  ?>" alt="">
      <h1>The Sportreat & TotaLifeCare Difference</h1>
    </div>
  </div>

  <div class="container health-care-offerings">
    <img class="img-fluid health-care-logo" src="<?php echo wp_get_attachment_image_src( 171, 'full', false )[0]  ?>" alt="">
    <h1>The Sportreat & TotaLifeCare Difference</h1>

    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> Our Core values: Care and compassion, quality with integrity      Happy family-friendly environment,
      Safe and professional organisation and Exceeding expectations
    </p>
    <a href="#"><img class="hide-image" src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> <span>View</span> – Our vision, mission statements and core values</a>

    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> 100% of your consultation time is spent one on one with your provider.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> A ‘client centred approach’ is proudly used.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> All new injuries have individualised patient management plans implemented and extended time taken for the consultation.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> A multi-professional approach is used in order to get you the best outcome for your situation.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> Over each discipline offered we boast high quality, handpicked health professionals.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> An Orthopaedic surgeon consulting from the same practice which includes an on-site practice Nurse.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> We use private treatment rooms, not curtained off sections.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> There is no ‘Doubling up’ of patients – Our providers will never see more than one patient at a time.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> We support our local community, including; businesses, sporting teams and charities.
    </p>
    <p><img src="<?php echo wp_get_attachment_image_src( 170, 'full', false )[0]  ?>" alt=""> We are also an education facility for high school work experience and Curtin University Physiotherapy students.
    </p>
  </div>
</div>

<div class="health-matters row">
  <div class="container health-matters-text">
    <img src="<?php echo wp_get_attachment_image_src( 175, 'full', false )[0]  ?>" alt="">
    <h1>Your Health Matters</h1>
  </div>
</div>

<div class="health-benefits row">
  <div class="container">
    <img class="img-fluid health-benefits-logo" src="<?php echo wp_get_attachment_image_src( 172, 'full', false )[0]  ?>" alt="">
    <h1>Sportreat & TotaLifeCare has a state of the art facility including:</h1>
    <div class="row">
      <div class="benefit col-12 col-lg-6">
        <div class="benefit-image">
          <img class="img-fluid health-benefits-logo" src="<?php echo wp_get_attachment_image_src( 174, 'full', false )[0]  ?>" alt="">
        </div>
        <p>On-site private gym with treadmill,
  bike, clinical Pilates equipment and TV.</p>
      </div>
      <div class="benefit col-12 col-lg-6">
        <div class="benefit-image">
          <img class="img-fluid health-benefits-logo" src="<?php echo wp_get_attachment_image_src( 173, 'full', false )[0]  ?>" alt="">
        </div>
        <p>Onsite private bathrooms and
  changing facilities available.</p>
      </div>
      <div class="benefit col-12 col-lg-6">
        <div class="benefit-image">
          <img class="img-fluid health-benefits-logo" src="<?php echo wp_get_attachment_image_src( 168, 'full', false )[0]  ?>" alt="">
        </div>
        <p>A generous waiting room with
  complimentary tea, coffee,
  hot chocolate, TV, Newspaper
  and other reading materials.</p>
      </div>
      <div class="benefit col-12 col-lg-6">
        <div class="benefit-image">
          <img class="img-fluid health-benefits-logo" src="<?php echo wp_get_attachment_image_src( 169, 'full', false )[0]  ?>" alt="">
        </div>
        <p>On-site parking with wheelchair
  access, as well as wheelchair friendly
  treatment/ consultation rooms.</p>
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