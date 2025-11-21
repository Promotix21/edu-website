<?php
/**
 * EDU Career India - Homepage
 * Dynamic content from database
 */

// Include main configuration
require_once __DIR__ . '/config.php';

// Get dynamic content (with fallbacks to original content)
$heroTitle = getContent('home', 'hero', 'title', 'Your Gateway to Top Universities in India & Abroad');
$heroSubtitle = getContent('home', 'hero', 'subtitle', 'Expert career counseling and direct admission guidance for MBBS, B.Tech, B.Pharma, Agriculture, and MBA programs. Your dream college is just a consultation away.');
$heroImage = getContent('home', 'hero', 'image', '');

// Get statistics
$statStudents = getStat('students_counseled', 5000);
$statSuccess = getStat('success_rate', 95);
$statInstitutions = getStat('partner_institutions', 200);
$statExperience = getStat('years_experience', 15);

// Get service images
$mbbsImage = getContent('home', 'services', 'mbbs_image', '');
$btechImage = getContent('home', 'services', 'btech_image', '');
$bpharmaImage = getContent('home', 'services', 'bpharma_image', '');
$agricultureImage = getContent('home', 'services', 'agriculture_image', '');
$mbaImage = getContent('home', 'services', 'mba_image', '');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO META TAGS -->
  <title>EDU Career India - Direct Admission MBBS, B.Tech, B.Pharma, MBA | Expert Career Counseling</title>
  <meta name="description" content="Leading education consultancy providing direct admission to top MBBS, B.Tech, B.Pharma & MBA colleges across India & abroad. <?php echo $statStudents; ?>+ students placed | <?php echo $statSuccess; ?>% success rate | <?php echo $statExperience; ?>+ years experience.">
  <meta name="keywords" content="career counseling India, direct admission MBBS, B.Tech admission, B.Pharma colleges, MBA admission, study abroad consultants, engineering college admission, medical college admission, education consultancy India">
  <meta name="author" content="EDU Career India">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

  <!-- CANONICAL URL -->
  <link rel="canonical" href="https://www.educareerindia.com/">

  <!-- OPEN GRAPH -->
  <meta property="og:locale" content="en_IN">
  <meta property="og:type" content="website">
  <meta property="og:title" content="EDU Career India - Your Trusted Education Partner">
  <meta property="og:description" content="Expert career counseling & direct admissions for MBBS, B.Tech, B.Pharma, MBA. <?php echo $statStudents; ?>+ students placed in top colleges across India & abroad.">
  <meta property="og:url" content="https://www.educareerindia.com/">
  <meta property="og:site_name" content="EDU Career India">
  <meta property="og:image" content="https://www.educareerindia.com/assets/images/og-image.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="EDU Career India - Expert Career Counseling & Direct Admissions">
  <meta name="twitter:description" content="Get direct admission to top MBBS, B.Tech, B.Pharma & MBA colleges. <?php echo $statStudents; ?>+ students counseled with <?php echo $statSuccess; ?>% success rate.">
  <meta name="twitter:image" content="https://www.educareerindia.com/assets/images/og-image.jpg">

  <!-- FAVICON -->
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">

  <!-- FONTS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="/assets/css/main.css">

  <!-- STRUCTURED DATA -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "EducationalOrganization",
        "@id": "https://www.educareerindia.com/#organization",
        "name": "EDU Career India",
        "url": "https://www.educareerindia.com",
        "description": "Leading education consultancy providing expert career counseling and direct admission services for MBBS, B.Tech, B.Pharma, B.Sc Agriculture, and MBA programs.",
        "slogan": "Your Dream, Our Mission"
      },
      {
        "@type": "WebSite",
        "@id": "https://www.educareerindia.com/#website",
        "url": "https://www.educareerindia.com",
        "name": "EDU Career India"
      }
    ]
  }
  </script>

  <?php if ($heroImage): ?>
  <style>
    .hero {
      background-image: linear-gradient(rgba(30, 64, 175, 0.8), rgba(30, 64, 175, 0.8)), url('<?php echo htmlspecialchars($heroImage); ?>');
      background-size: cover;
      background-position: center;
    }
  </style>
  <?php endif; ?>
</head>
<body>
  <!-- HEADER -->
  <header class="header" role="banner">
    <nav class="navbar container" role="navigation">
      <a href="/" class="logo">EDU <span>Career</span> India</a>
      <ul class="nav-menu" role="menubar">
        <li><a href="/" class="nav-link active" aria-current="page">Home</a></li>
        <li><a href="/about.php" class="nav-link">About Us</a></li>
        <li><a href="/courses.php" class="nav-link">Courses</a></li>
        <li><a href="/universities.php" class="nav-link">Universities</a></li>
        <li><a href="/contact.php" class="nav-link">Contact</a></li>
      </ul>
      <div class="mobile-toggle" role="button" tabindex="0">
        <span></span><span></span><span></span>
      </div>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main id="main-content" role="main">

    <!-- HERO SECTION -->
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h1><?php echo htmlspecialchars($heroTitle); ?></h1>
          <p><?php echo htmlspecialchars($heroSubtitle); ?></p>
          <div class="hero-cta">
            <a href="/contact.php" class="btn btn-primary">Book Free Consultation</a>
            <a href="/courses.php" class="btn btn-outline">Explore Courses</a>
          </div>
        </div>
      </div>
    </section>

    <!-- STATISTICS SECTION -->
    <section class="stats">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Our Track Record Speaks</h2>
          <p class="section-subtitle">Numbers that reflect our commitment to student success and excellence</p>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number"><?php echo number_format($statStudents); ?>+</div>
            <div class="stat-label">Students Counseled</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $statSuccess; ?>%</div>
            <div class="stat-label">Success Rate</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $statInstitutions; ?>+</div>
            <div class="stat-label">Partner Institutions</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $statExperience; ?>+</div>
            <div class="stat-label">Years of Excellence</div>
          </div>
        </div>
      </div>
    </section>

    <!-- WHY CHOOSE US SECTION -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Why Choose EDU Career India?</h2>
          <p class="section-subtitle">Discover what makes us the most trusted education consultancy for thousands of students</p>
        </div>

        <div class="features-grid">
          <article class="feature-card">
            <div class="feature-icon">🎓</div>
            <h3>Expert Guidance</h3>
            <p>Our experienced counselors provide personalized guidance tailored to your academic profile and career aspirations.</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">🏆</div>
            <h3>Proven Track Record</h3>
            <p>With <?php echo number_format($statStudents); ?>+ successful admissions and a <?php echo $statSuccess; ?>% success rate, we consistently deliver results.</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">🌍</div>
            <h3>Global Reach</h3>
            <p>Access to premium institutions across India, USA, UK, Australia, Canada, and Dubai.</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">💼</div>
            <h3>End-to-End Support</h3>
            <p>From college selection to admission completion, we handle everything under one roof.</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">✅</div>
            <h3>Transparent Process</h3>
            <p>No hidden charges, no false promises. Just honest communication and transparent dealings.</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Quick Admissions</h3>
            <p>Streamlined process ensures faster admissions to your dream colleges.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="section" style="background-color: var(--light-color);">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Programs We Offer</h2>
          <p class="section-subtitle">Specialized counseling and direct admission services</p>
        </div>

        <div class="features-grid">
          <article class="feature-card">
            <?php if ($mbbsImage): ?>
              <img src="<?php echo htmlspecialchars($mbbsImage); ?>" alt="MBBS Program" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php endif; ?>
            <div class="feature-icon">🩺</div>
            <h3>MBBS Programs</h3>
            <p>Direct admission to top medical colleges across India. Expert guidance for NEET counseling and management quota.</p>
            <a href="/courses.php#mbbs" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Learn More</a>
          </article>

          <article class="feature-card">
            <?php if ($btechImage): ?>
              <img src="<?php echo htmlspecialchars($btechImage); ?>" alt="B.Tech Engineering" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php endif; ?>
            <div class="feature-icon">⚙️</div>
            <h3>B.Tech Engineering</h3>
            <p>Secure your seat in prestigious engineering colleges. All branches available across top institutions.</p>
            <a href="/courses.php#btech" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Learn More</a>
          </article>

          <article class="feature-card">
            <?php if ($bpharmaImage): ?>
              <img src="<?php echo htmlspecialchars($bpharmaImage); ?>" alt="B.Pharma Programs" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php endif; ?>
            <div class="feature-icon">💊</div>
            <h3>B.Pharma Programs</h3>
            <p>Admission to AICTE approved pharmacy colleges with excellent placement records.</p>
            <a href="/courses.php#bpharma" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Learn More</a>
          </article>

          <article class="feature-card">
            <?php if ($agricultureImage): ?>
              <img src="<?php echo htmlspecialchars($agricultureImage); ?>" alt="B.Sc Agriculture" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php endif; ?>
            <div class="feature-icon">🌾</div>
            <h3>B.Sc Agriculture</h3>
            <p>Gateway to agricultural sciences education at top universities.</p>
            <a href="/courses.php#agriculture" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Learn More</a>
          </article>

          <article class="feature-card">
            <?php if ($mbaImage): ?>
              <img src="<?php echo htmlspecialchars($mbaImage); ?>" alt="MBA & PGDM" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem;">
            <?php endif; ?>
            <div class="feature-icon">📊</div>
            <h3>MBA & PGDM</h3>
            <p>Admission to India's leading B-schools with excellent ROI and placements.</p>
            <a href="/courses.php#mba" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Learn More</a>
          </article>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS PREVIEW -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">Success Stories</h2>
          <p class="section-subtitle">Hear from students who achieved their dreams with our guidance</p>
        </div>

        <div class="features-grid">
          <article class="feature-card">
            <p style="font-style: italic; margin-bottom: 1rem;">"EDU Career India helped me secure a direct seat in RVCE, Bangalore. Their guidance was precise and highly professional. Highly recommended!"</p>
            <h4 style="color: var(--primary-color);">Priya Sharma</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">B.Tech CSE, RVCE Bangalore</p>
          </article>

          <article class="feature-card">
            <p style="font-style: italic; margin-bottom: 1rem;">"Getting into a top MBBS program seemed impossible, but the team made the entire process seamless. Thank you for making my dream a reality!"</p>
            <h4 style="color: var(--primary-color);">Rahul Verma</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">MBBS, JSS Medical College, Mysore</p>
          </article>

          <article class="feature-card">
            <p style="font-style: italic; margin-bottom: 1rem;">"I was confused about which MBA college to choose. EDU Career India guided me in selecting the right specialization. Excellent service!"</p>
            <h4 style="color: var(--primary-color);">Anjali Patel</h4>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">MBA Marketing, SIBM Pune</p>
          </article>
        </div>
      </div>
    </section>

    <!-- CTA SECTION -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%); color: var(--white);">
      <div class="container text-center">
        <h2 style="color: var(--white); font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1rem;">Ready to Start Your Journey?</h2>
        <p style="font-size: 1.25rem; margin-bottom: 2rem; color: rgba(255, 255, 255, 0.9);">Book a free consultation with our expert counselors</p>
        <a href="/contact.php" class="btn btn-secondary" style="font-size: 1.125rem; padding: 1.25rem 2.5rem;">Get Started Today</a>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-column">
          <h4>About EDU Career India</h4>
          <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 1rem;">Your trusted partner for career counseling and direct admissions. Your Dream, Our Mission.</p>
          <p style="color: rgba(255, 255, 255, 0.8);">📞 +91-XXXXXXXXXX</p>
          <p style="color: rgba(255, 255, 255, 0.8);">✉️ info@educareerindia.com</p>
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
        <p>&copy; 2025 EDU Career India. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="/assets/js/main.js" defer></script>
</body>
</html>
