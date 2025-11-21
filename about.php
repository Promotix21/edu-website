<?php
/**
 * EDU Career India - About Us Page
 * Dynamic content from database
 */

// Include main configuration
require_once __DIR__ . '/config.php';

// Get statistics
$statStudents = getStat('students_counseled', 5000);
$statSuccess = getStat('success_rate', 95);
$statInstitutions = getStat('partner_institutions', 200);
$statExperience = getStat('years_experience', 15);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- ============================================
       BASIC META TAGS
       ============================================ -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- ============================================
       SEO META TAGS - OPTIMIZED
       ============================================ -->
  <title>About Us - EDU Career India | <?php echo $statExperience; ?>+ Years of Excellence in Education Counseling</title>
  <meta name="description" content="Learn about EDU Career India's <?php echo $statExperience; ?>-year journey in education counseling. <?php echo number_format($statStudents); ?>+ students guided, <?php echo $statSuccess; ?>% success rate. Meet our expert team committed to Your Dream, Our Mission.">
  <meta name="keywords" content="about EDU Career India, education consultancy India, career counseling experts, college admission consultants, study abroad consultancy, education counseling services">
  <meta name="author" content="EDU Career India">
  <meta name="robots" content="index, follow">

  <!-- ============================================
       CANONICAL URL
       ============================================ -->
  <link rel="canonical" href="https://www.educareerindia.com/about">

  <!-- ============================================
       OPEN GRAPH / SOCIAL MEDIA META TAGS
       ============================================ -->
  <meta property="og:locale" content="en_IN">
  <meta property="og:type" content="website">
  <meta property="og:title" content="About EDU Career India - Your Trusted Education Partner">
  <meta property="og:description" content="<?php echo $statExperience; ?>+ years of excellence in education counseling. <?php echo number_format($statStudents); ?>+ students guided with <?php echo $statSuccess; ?>% success rate. Expert team committed to your educational success.">
  <meta property="og:url" content="https://www.educareerindia.com/about">
  <meta property="og:site_name" content="EDU Career India">
  <meta property="og:image" content="https://www.educareerindia.com/assets/images/about-og-image.jpg">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="About EDU Career India - <?php echo $statExperience; ?>+ Years of Excellence">
  <meta name="twitter:description" content="Learn about our journey, mission, and expert team dedicated to guiding students to their dream colleges.">
  <meta name="twitter:image" content="https://www.educareerindia.com/assets/images/about-og-image.jpg">

  <!-- ============================================
       FAVICON
       ============================================ -->
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">

  <!-- ============================================
       PRECONNECT FOR PERFORMANCE
       ============================================ -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- ============================================
       FONTS
       ============================================ -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- ============================================
       STYLESHEETS
       ============================================ -->
  <link rel="stylesheet" href="/assets/css/main.css">

  <!-- ============================================
       STRUCTURED DATA / SCHEMA MARKUP
       ============================================ -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "WebPage",
        "@id": "https://www.educareerindia.com/about#webpage",
        "url": "https://www.educareerindia.com/about",
        "name": "About Us - EDU Career India",
        "description": "Learn about EDU Career India's <?php echo $statExperience; ?>-year journey in education counseling. <?php echo number_format($statStudents); ?>+ students guided with <?php echo $statSuccess; ?>% success rate.",
        "isPartOf": {
          "@id": "https://www.educareerindia.com/#website"
        },
        "about": {
          "@id": "https://www.educareerindia.com/#organization"
        },
        "datePublished": "2024-01-01T00:00:00+05:30",
        "dateModified": "2025-11-21T00:00:00+05:30"
      },
      {
        "@type": "BreadcrumbList",
        "@id": "https://www.educareerindia.com/about#breadcrumb",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "https://www.educareerindia.com/"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "About Us",
            "item": "https://www.educareerindia.com/about"
          }
        ]
      },
      {
        "@type": "AboutPage",
        "name": "About EDU Career India",
        "description": "Information about EDU Career India, our mission, values, and commitment to student success in education counseling."
      }
    ]
  }
  </script>
</head>
<body>
  <!-- ============================================
       HEADER / NAVIGATION
       ============================================ -->
  <header class="header" role="banner">
    <nav class="navbar container" role="navigation" aria-label="Main navigation">
      <a href="/" class="logo" aria-label="EDU Career India - Home">
        EDU <span>Career</span> India
      </a>

      <ul class="nav-menu" role="menubar">
        <li role="none"><a href="/" class="nav-link" role="menuitem">Home</a></li>
        <li role="none"><a href="/about.php" class="nav-link active" role="menuitem" aria-current="page">About Us</a></li>
        <li role="none"><a href="/courses.php" class="nav-link" role="menuitem">Courses</a></li>
        <li role="none"><a href="/universities.php" class="nav-link" role="menuitem">Universities</a></li>
        <li role="none"><a href="/contact.php" class="nav-link" role="menuitem">Contact</a></li>
      </ul>

      <div class="mobile-toggle" aria-label="Toggle navigation menu" role="button" tabindex="0">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <!-- ============================================
       MAIN CONTENT
       ============================================ -->
  <main id="main-content" role="main">

    <!-- BREADCRUMB NAVIGATION -->
    <nav aria-label="Breadcrumb" style="padding-top: 100px; padding-bottom: 1rem; background-color: var(--light-color);">
      <div class="container">
        <ol style="list-style: none; display: flex; gap: 0.5rem; font-size: 0.9rem; color: var(--text-secondary);">
          <li><a href="/" style="color: var(--primary-color);">Home</a></li>
          <li aria-hidden="true">/</li>
          <li aria-current="page">About Us</li>
        </ol>
      </div>
    </nav>

    <!-- PAGE HERO -->
    <section class="section-sm" style="background-color: var(--light-color);" aria-labelledby="page-heading">
      <div class="container text-center">
        <h1 id="page-heading">Your Trusted Career Partner</h1>
        <p style="font-size: 1.25rem; color: var(--text-secondary); max-width: 700px; margin: 0 auto;">Building careers, shaping futures. For over <?php echo $statExperience; ?> years, we've been the bridge between aspiring students and their dream colleges.</p>
      </div>
    </section>

    <!-- MISSION STATEMENT -->
    <section class="section">
      <div class="container">
        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%); padding: 3rem; border-radius: 1rem; color: var(--white); text-align: center;">
          <h2 style="color: var(--white); font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">Your Dream, Our Mission</h2>
          <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.95); max-width: 800px; margin: 0 auto;">At EDU Career India, we don't just provide admission services – we partner with you in your educational journey, ensuring every step you take is towards a brighter, more successful future.</p>
        </div>
      </div>
    </section>

    <!-- ABOUT CONTENT -->
    <section class="section" aria-labelledby="about-heading">
      <div class="container">
        <div class="section-header">
          <h2 id="about-heading" class="section-title">Who We Are</h2>
        </div>

        <div style="max-width: 900px; margin: 0 auto;">
          <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 1.5rem;">EDU Career India was founded on a simple yet powerful belief: every student deserves access to quality education and expert guidance to achieve their career goals. What started as a small consultancy has grown into one of India's most trusted education counseling organizations, serving thousands of students across the nation.</p>

          <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 1.5rem;">We specialize in providing <strong>honest and effective career counseling</strong> along with <strong>direct admission services</strong> for prestigious institutions across India and abroad. Our expertise spans multiple domains including MBBS, B.Tech, B.Pharma, B.Sc Agriculture, and MBA programs.</p>

          <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 1.5rem;">Our commitment goes beyond just securing admissions. We take pride in understanding each student's unique academic profile, personal aspirations, and career objectives. This personalized approach ensures that students don't just get into any college – they get into the <em>right</em> college that aligns with their dreams and maximizes their potential.</p>

          <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 1.5rem;">With partnerships across <strong><?php echo number_format($statInstitutions); ?>+ premier institutions</strong> and a track record of <strong><?php echo number_format($statStudents); ?>+ successful admissions</strong> with a <strong><?php echo $statSuccess; ?>% success rate</strong>, we have established ourselves as the go-to destination for students seeking quality education counseling and admission guidance.</p>
        </div>
      </div>
    </section>

    <!-- OUR VALUES -->
    <section class="section" style="background-color: var(--light-color);" aria-labelledby="values-heading">
      <div class="container">
        <div class="section-header">
          <h2 id="values-heading" class="section-title">Our Core Values</h2>
          <p class="section-subtitle">The principles that guide everything we do</p>
        </div>

        <div class="features-grid">
          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">🤝</div>
            <h3>Honesty & Transparency</h3>
            <p>We believe in clear, honest communication. No hidden charges, no false promises – just transparent guidance you can trust throughout your admission journey.</p>
          </article>

          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">🎯</div>
            <h3>Student-Centric Approach</h3>
            <p>Your success is our success. Every decision we make, every recommendation we provide, is centered around what's best for your educational and career growth.</p>
          </article>

          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">💡</div>
            <h3>Expert Knowledge</h3>
            <p>Our team stays updated with the latest admission trends, policies, and opportunities, ensuring you receive accurate and timely guidance for informed decisions.</p>
          </article>

          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">⚖️</div>
            <h3>Integrity</h3>
            <p>We maintain the highest ethical standards in all our dealings. Your trust is our most valuable asset, and we work tirelessly to honor it every day.</p>
          </article>

          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">🌟</div>
            <h3>Excellence</h3>
            <p>We don't settle for good when great is possible. Our commitment to excellence drives us to deliver outstanding results for every student we serve.</p>
          </article>

          <article class="feature-card">
            <div class="feature-icon" aria-hidden="true">🔄</div>
            <h3>Continuous Support</h3>
            <p>Our relationship doesn't end with admission. We provide ongoing support and guidance to ensure your smooth transition into your chosen institution.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- OUR JOURNEY -->
    <section class="section" aria-labelledby="journey-heading">
      <div class="container">
        <div class="section-header">
          <h2 id="journey-heading" class="section-title">Our Journey</h2>
          <p class="section-subtitle"><?php echo $statExperience; ?>+ years of dedication, innovation, and student success</p>
        </div>

        <div style="max-width: 900px; margin: 0 auto;">
          <div style="display: grid; gap: 2rem;">
            <div style="padding: 2rem; background-color: var(--light-color); border-radius: 1rem; border-left: 4px solid var(--primary-color);">
              <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">2009 - The Beginning</h3>
              <p>EDU Career India was established with a vision to democratize access to quality education counseling. Started with a small team of passionate counselors committed to student success.</p>
            </div>

            <div style="padding: 2rem; background-color: var(--light-color); border-radius: 1rem; border-left: 4px solid var(--secondary-color);">
              <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">2012 - Expansion</h3>
              <p>Crossed the milestone of 1000 successful admissions. Expanded our network to include premier institutions across South India and established partnerships with top colleges.</p>
            </div>

            <div style="padding: 2rem; background-color: var(--light-color); border-radius: 1rem; border-left: 4px solid var(--accent-color);">
              <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">2016 - Going National</h3>
              <p>Extended our services pan-India, partnering with 100+ institutions across all major states. Launched dedicated teams for MBBS, Engineering, Pharmacy, and Management counseling.</p>
            </div>

            <div style="padding: 2rem; background-color: var(--light-color); border-radius: 1rem; border-left: 4px solid var(--primary-color);">
              <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">2020 - International Reach</h3>
              <p>Began facilitating international admissions, helping Indian students secure seats in universities across USA, UK, Australia, Canada, and Dubai. Digital transformation during pandemic.</p>
            </div>

            <div style="padding: 2rem; background-color: var(--light-color); border-radius: 1rem; border-left: 4px solid var(--secondary-color);">
              <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">2025 - Excellence Continues</h3>
              <p>Today, with <?php echo number_format($statStudents); ?>+ successful admissions, <?php echo number_format($statInstitutions); ?>+ partner institutions, and a <?php echo $statSuccess; ?>% success rate, we continue to be the most trusted name in education counseling. Our journey of excellence continues!</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="section" style="background-color: var(--light-color);" aria-labelledby="why-us-heading">
      <div class="container">
        <div class="section-header">
          <h2 id="why-us-heading" class="section-title">Why Students Choose Us</h2>
          <p class="section-subtitle">What sets us apart in the education counseling landscape</p>
        </div>

        <div style="max-width: 900px; margin: 0 auto;">
          <div style="display: grid; gap: 1.5rem;">
            <div style="display: flex; gap: 1rem; align-items: start;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--primary-color); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">1</div>
              <div>
                <h3 style="margin-bottom: 0.5rem;">Personalized Counseling</h3>
                <p>We don't believe in one-size-fits-all solutions. Every student receives individualized attention and customized guidance based on their unique profile, interests, and goals.</p>
              </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: start;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--primary-color); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">2</div>
              <div>
                <h3 style="margin-bottom: 0.5rem;">Extensive Network</h3>
                <p>Our partnerships with <?php echo number_format($statInstitutions); ?>+ institutions give you access to a wide range of options – from government colleges to private universities, from India to abroad.</p>
              </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: start;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--primary-color); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">3</div>
              <div>
                <h3 style="margin-bottom: 0.5rem;">Proven Track Record</h3>
                <p>Numbers speak louder than words. With <?php echo number_format($statStudents); ?>+ successful admissions and a <?php echo $statSuccess; ?>% success rate, we have consistently delivered results that matter.</p>
              </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: start;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--primary-color); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">4</div>
              <div>
                <h3 style="margin-bottom: 0.5rem;">Complete Documentation Support</h3>
                <p>From filling application forms to arranging documents, from submission to follow-up – we handle all the paperwork so you can focus on your preparation.</p>
              </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: start;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background-color: var(--primary-color); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">5</div>
              <div>
                <h3 style="margin-bottom: 0.5rem;">Post-Admission Assistance</h3>
                <p>Our support continues even after admission. We help with hostel arrangements, course registration, and any challenges you might face during your initial days at college.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA SECTION -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%); color: var(--white);" aria-labelledby="cta-heading">
      <div class="container text-center">
        <h2 id="cta-heading" style="color: var(--white); font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">Ready to Begin Your Journey?</h2>
        <p style="font-size: 1.25rem; margin-bottom: 2rem; color: rgba(255, 255, 255, 0.9);">Join thousands of successful students who trusted us with their educational dreams</p>
        <a href="/contact.php" class="btn btn-secondary" style="font-size: 1.125rem; padding: 1.25rem 2.5rem;">Book Free Consultation</a>
      </div>
    </section>

  </main>

  <!-- ============================================
       FOOTER
       ============================================ -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-column">
          <h4>About EDU Career India</h4>
          <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 1rem;">Your trusted partner for career counseling and direct admissions to top colleges across India and abroad. Your Dream, Our Mission.</p>
          <p style="color: rgba(255, 255, 255, 0.8);">📞 <a href="tel:+91XXXXXXXXXX" style="color: rgba(255, 255, 255, 0.8);">+91-XXXXXXXXXX</a></p>
          <p style="color: rgba(255, 255, 255, 0.8);">✉️ <a href="mailto:info@educareerindia.com" style="color: rgba(255, 255, 255, 0.8);">info@educareerindia.com</a></p>
        </div>

        <div class="footer-column">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about.php">About Us</a></li>
            <li><a href="/courses.php">Courses</a></li>
            <li><a href="/universities.php">Universities</a></li>
            <li><a href="/contact.php">Contact Us</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Our Courses</h4>
          <ul>
            <li><a href="/courses.php#mbbs">MBBS Admission</a></li>
            <li><a href="/courses.php#btech">B.Tech Engineering</a></li>
            <li><a href="/courses.php#bpharma">B.Pharma Programs</a></li>
            <li><a href="/courses.php#agriculture">B.Sc Agriculture</a></li>
            <li><a href="/courses.php#mba">MBA & PGDM</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Study Destinations</h4>
          <ul>
            <li><a href="/universities.php#india">India</a></li>
            <li><a href="/universities.php#usa">United States</a></li>
            <li><a href="/universities.php#uk">United Kingdom</a></li>
            <li><a href="/universities.php#australia">Australia</a></li>
            <li><a href="/universities.php#canada">Canada</a></li>
            <li><a href="/universities.php#dubai">Dubai</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 EDU Career India. All rights reserved. | <a href="/privacy-policy.html" style="color: rgba(255, 255, 255, 0.7);">Privacy Policy</a> | <a href="/terms-conditions.html" style="color: rgba(255, 255, 255, 0.7);">Terms & Conditions</a></p>
      </div>
    </div>
  </footer>

  <!-- ============================================
       JAVASCRIPT
       ============================================ -->
  <script src="/assets/js/main.js" defer></script>
</body>
</html>
