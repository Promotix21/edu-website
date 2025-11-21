<?php
/**
 * EDU Career India - Homepage
 * Modern animated design with GSAP, Three.js, and Lenis smooth scroll
 */

// Include main configuration
require_once __DIR__ . '/config.php';

// Get hero slider slides from database
try {
    $stmt = $pdo->query("SELECT * FROM hero_slider WHERE is_active = 1 ORDER BY display_order ASC");
    $heroSlides = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback to default slide if table doesn't exist yet
    $heroTitle = getContent('home', 'hero', 'title', 'Your Gateway to Top Universities in India & Abroad');
    $heroSubtitle = getContent('home', 'hero', 'subtitle', 'Expert career counseling and direct admission guidance for MBBS, B.Tech, B.Pharma, Agriculture, and MBA programs.');
    $heroSlides = [[
        'id' => 1,
        'title' => $heroTitle,
        'subtitle' => $heroSubtitle,
        'image_url' => '/assets/images/hero-banner-home.jpg',
        'button_text' => 'Book Free Consultation',
        'button_link' => '/contact',
        'is_active' => 1
    ]];
}

// Get service images from database (with fallbacks)
$serviceImages = [
    'mbbs' => getContent('home', 'services', 'mbbs_image') ?: '/assets/images/service-mbbs.jpg',
    'btech' => getContent('home', 'services', 'btech_image') ?: '/assets/images/service-btech.jpg',
    'bpharma' => getContent('home', 'services', 'bpharma_image') ?: '/assets/images/service-bpharma.jpg',
    'agriculture' => getContent('home', 'services', 'agriculture_image') ?: '/assets/images/service-agriculture.jpg',
    'mba' => getContent('home', 'services', 'mba_image') ?: '/assets/images/service-mba.jpg'
];

// Get logo from settings
$siteLogoUrl = getContent('settings', 'branding', 'logo_url');
$siteName = getContent('settings', 'branding', 'site_name') ?: 'EDU Career India';

// Get statistics
$statStudents = getStat('students_counseled', 5000);
$statSuccess = getStat('success_rate', 95);
$statInstitutions = getStat('partner_institutions', 200);
$statExperience = getStat('years_experience', 15);
?>
<!DOCTYPE html>
<html lang="en" class="home">
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

  <!-- FONTS - Modern Professional Typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- STYLESHEETS -->
  <link rel="stylesheet" href="/assets/css/modern.css">

  <!-- ANIMATION LIBRARIES -->
  <!-- GSAP 3.12+ with ScrollTrigger -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

  <!-- SplitType.js for text animations -->
  <script src="https://unpkg.com/split-type@0.3.4/umd/index.js"></script>

  <!-- Lenis Smooth Scroll -->
  <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>

  <!-- Three.js for 3D Globe -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r158/three.min.js"></script>

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
</head>
<body class="home">

  <!-- HEADER -->
  <header class="header" role="banner">
    <nav class="navbar container" role="navigation">
      <?php if ($siteLogoUrl): ?>
        <a href="/" class="logo logo-image">
          <img src="<?php echo htmlspecialchars($siteLogoUrl); ?>" alt="<?php echo htmlspecialchars($siteName); ?>" class="site-logo-img">
        </a>
      <?php else: ?>
        <a href="/" class="logo">
          <span class="logo-edu">EDU</span>
          <span class="logo-career">Career</span>
          <span class="logo-india">India</span>
        </a>
      <?php endif; ?>
      <ul class="nav-menu" role="menubar">
        <li><a href="/" class="nav-link active" aria-current="page">Home</a></li>
        <li><a href="/about" class="nav-link">About Us</a></li>
        <li><a href="/courses" class="nav-link">Courses</a></li>
        <li><a href="/universities" class="nav-link">Universities</a></li>
        <li><a href="/contact" class="nav-link">Contact</a></li>
      </ul>
      <div class="mobile-toggle" role="button" tabindex="0" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </div>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main id="main-content" role="main">

    <!-- MODERN HERO SECTION WITH SLIDER -->
    <section class="hero-modern">
      <div class="hero-slider">
        <?php
        $slideIndex = 0;
        foreach ($heroSlides as $slide):
          $isActive = ($slideIndex === 0) ? 'active' : '';
          $slideIndex++;
        ?>
          <div class="hero-slide <?php echo $isActive; ?>" style="background-image: url('<?php echo htmlspecialchars($slide['image_url']); ?>');">
            <div class="hero-overlay"></div>
            <div class="container">
              <div class="hero-content">
                <h1 class="hero-title animate-text"><?php echo htmlspecialchars($slide['title']); ?></h1>
                <p class="hero-subtitle animate-text"><?php echo htmlspecialchars($slide['subtitle']); ?></p>
                <div class="hero-cta">
                  <a href="<?php echo htmlspecialchars($slide['button_link']); ?>" class="btn btn-primary btn-glow">
                    <?php echo htmlspecialchars($slide['button_text']); ?>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Slider Controls -->
      <div class="hero-slider-controls">
        <button class="slider-btn prev" aria-label="Previous slide">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
        <div class="slider-dots"></div>
        <button class="slider-btn next" aria-label="Next slide">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>

      <!-- Scroll Indicator -->
      <div class="scroll-indicator">
        <span>Scroll to explore</span>
        <div class="scroll-line"></div>
      </div>
    </section>

    <!-- ANIMATED STATISTICS SECTION -->
    <section class="stats-section">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-card" data-stat-target="<?php echo $statStudents; ?>">
            <div class="stat-icon">
              <img src="/assets/images/stat-icon-students.png" alt="Students" loading="lazy">
            </div>
            <div class="stat-number" data-target="<?php echo $statStudents; ?>">0</div>
            <div class="stat-suffix">+</div>
            <div class="stat-label">Students Counseled</div>
          </div>

          <div class="stat-card" data-stat-target="<?php echo $statSuccess; ?>">
            <div class="stat-icon">
              <img src="/assets/images/stat-icon-success.png" alt="Success Rate" loading="lazy">
            </div>
            <div class="stat-number" data-target="<?php echo $statSuccess; ?>">0</div>
            <div class="stat-suffix">%</div>
            <div class="stat-label">Success Rate</div>
          </div>

          <div class="stat-card" data-stat-target="<?php echo $statInstitutions; ?>">
            <div class="stat-icon">
              <img src="/assets/images/stat-icon-institutions.png" alt="Partner Institutions" loading="lazy">
            </div>
            <div class="stat-number" data-target="<?php echo $statInstitutions; ?>">0</div>
            <div class="stat-suffix">+</div>
            <div class="stat-label">Partner Institutions</div>
          </div>

          <div class="stat-card" data-stat-target="<?php echo $statExperience; ?>">
            <div class="stat-icon">
              <img src="/assets/images/stat-icon-experience.png" alt="Years Experience" loading="lazy">
            </div>
            <div class="stat-number" data-target="<?php echo $statExperience; ?>">0</div>
            <div class="stat-suffix">+</div>
            <div class="stat-label">Years of Excellence</div>
          </div>
        </div>
      </div>
    </section>

    <!-- THREE.JS GLOBE SECTION -->
    <section class="globe-section">
      <div class="container">
        <div class="section-header text-center">
          <h2 class="section-title animate-text">Global Education Network</h2>
          <p class="section-subtitle animate-text">Partner institutions across 6 countries offering world-class education</p>
        </div>
        <div id="globe-container" class="globe-container"></div>
        <div class="globe-locations">
          <div class="location-tag" data-country="india">
            <span class="location-flag">🇮🇳</span> India
          </div>
          <div class="location-tag" data-country="usa">
            <span class="location-flag">🇺🇸</span> USA
          </div>
          <div class="location-tag" data-country="uk">
            <span class="location-flag">🇬🇧</span> UK
          </div>
          <div class="location-tag" data-country="australia">
            <span class="location-flag">🇦🇺</span> Australia
          </div>
          <div class="location-tag" data-country="canada">
            <span class="location-flag">🇨🇦</span> Canada
          </div>
          <div class="location-tag" data-country="dubai">
            <span class="location-flag">🇦🇪</span> UAE
          </div>
        </div>
      </div>
    </section>

    <!-- WHY CHOOSE US SECTION -->
    <section class="features-section">
      <div class="container">
        <div class="section-header text-center">
          <h2 class="section-title animate-text">Why Choose EDU Career India?</h2>
          <p class="section-subtitle animate-text">Discover what makes us the most trusted education consultancy</p>
        </div>

        <div class="features-grid-modern">
          <article class="feature-card-modern">
            <div class="feature-icon-modern">
              <img src="/assets/images/icon-expert-counselors.png" alt="Expert Counselors" loading="lazy">
            </div>
            <h3 class="animate-text">Expert Guidance</h3>
            <p>Personalized counseling tailored to your profile and goals.</p>
          </article>

          <article class="feature-card-modern">
            <div class="feature-icon-modern">
              <img src="/assets/images/icon-proven-track-record.png" alt="Proven Track Record" loading="lazy">
            </div>
            <h3 class="animate-text">Proven Success</h3>
            <p><?php echo number_format($statStudents); ?>+ students placed with <?php echo $statSuccess; ?>% success rate.</p>
          </article>

          <article class="feature-card-modern">
            <div class="feature-icon-modern">
              <img src="/assets/images/icon-global-reach.png" alt="Global Reach" loading="lazy">
            </div>
            <h3 class="animate-text">Global Network</h3>
            <p>Premium institutions across 6 countries worldwide.</p>
          </article>

          <article class="feature-card-modern">
            <div class="feature-icon-modern">
              <img src="/assets/images/icon-end-to-end-support.png" alt="End-to-End Support" loading="lazy">
            </div>
            <h3 class="animate-text">Complete Support</h3>
            <p>End-to-end assistance from selection to admission.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- SERVICES/COURSES SECTION -->
    <section class="services-section">
      <div class="container">
        <div class="section-header text-center">
          <h2 class="section-title animate-text">Programs We Offer</h2>
          <p class="section-subtitle animate-text">Specialized counseling and direct admission services</p>
        </div>

        <div class="services-grid">
          <!-- MBBS -->
          <article class="service-card">
            <div class="service-image">
              <img src="<?php echo htmlspecialchars($serviceImages['mbbs']); ?>" alt="MBBS Programs" loading="lazy">
              <div class="service-overlay">
                <a href="/courses#mbbs" class="service-link">Explore MBBS</a>
              </div>
            </div>
            <div class="service-content">
              <div class="service-icon">
                <img src="/assets/images/service-icon-mbbs.png" alt="MBBS Icon" loading="lazy">
              </div>
              <h3 class="animate-text">MBBS Programs</h3>
              <p>Direct admission to premier medical colleges in India and abroad.</p>
              <ul class="service-features">
                <li>NEET Counseling Support</li>
                <li>Management Quota</li>
                <li>Top Medical Institutions</li>
              </ul>
            </div>
          </article>

          <!-- B.Tech -->
          <article class="service-card">
            <div class="service-image">
              <img src="<?php echo htmlspecialchars($serviceImages['btech']); ?>" alt="B.Tech Engineering" loading="lazy">
              <div class="service-overlay">
                <a href="/courses#btech" class="service-link">Explore B.Tech</a>
              </div>
            </div>
            <div class="service-content">
              <div class="service-icon">
                <img src="/assets/images/service-icon-engineering.png" alt="Engineering Icon" loading="lazy">
              </div>
              <h3 class="animate-text">B.Tech Engineering</h3>
              <p>Secure seats in prestigious engineering colleges across all streams.</p>
              <ul class="service-features">
                <li>All Engineering Streams</li>
                <li>AICTE Approved Colleges</li>
                <li>Placement Assistance</li>
              </ul>
            </div>
          </article>

          <!-- B.Pharma -->
          <article class="service-card">
            <div class="service-image">
              <img src="<?php echo htmlspecialchars($serviceImages['bpharma']); ?>" alt="B.Pharma Programs" loading="lazy">
              <div class="service-overlay">
                <a href="/courses#bpharma" class="service-link">Explore B.Pharma</a>
              </div>
            </div>
            <div class="service-content">
              <div class="service-icon">
                <img src="/assets/images/service-icon-pharmacy.png" alt="Pharmacy Icon" loading="lazy">
              </div>
              <h3 class="animate-text">B.Pharma Programs</h3>
              <p>AICTE approved pharmacy colleges with excellent placement records.</p>
              <ul class="service-features">
                <li>AICTE Approved Colleges</li>
                <li>Industry Collaborations</li>
                <li>Research Opportunities</li>
              </ul>
            </div>
          </article>

          <!-- Agriculture -->
          <article class="service-card">
            <div class="service-image">
              <img src="<?php echo htmlspecialchars($serviceImages['agriculture']); ?>" alt="B.Sc Agriculture" loading="lazy">
              <div class="service-overlay">
                <a href="/courses#agriculture" class="service-link">Explore Agriculture</a>
              </div>
            </div>
            <div class="service-content">
              <div class="service-icon">
                <img src="/assets/images/service-icon-agriculture.png" alt="Agriculture Icon" loading="lazy">
              </div>
              <h3 class="animate-text">B.Sc Agriculture</h3>
              <p>Gateway to agricultural sciences at premier universities nationwide.</p>
              <ul class="service-features">
                <li>Top Agricultural Universities</li>
                <li>Modern Farming Technology</li>
                <li>Government Scholarships</li>
              </ul>
            </div>
          </article>

          <!-- MBA -->
          <article class="service-card">
            <div class="service-image">
              <img src="<?php echo htmlspecialchars($serviceImages['mba']); ?>" alt="MBA & PGDM" loading="lazy">
              <div class="service-overlay">
                <a href="/courses#mba" class="service-link">Explore MBA</a>
              </div>
            </div>
            <div class="service-content">
              <div class="service-icon">
                <img src="/assets/images/service-icon-mba.png" alt="MBA Icon" loading="lazy">
              </div>
              <h3 class="animate-text">MBA & PGDM</h3>
              <p>Admission to India's premier B-schools with excellent career ROI.</p>
              <ul class="service-features">
                <li>Top B-Schools</li>
                <li>All Specializations</li>
                <li>Career Placement Support</li>
              </ul>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS CAROUSEL -->
    <section class="testimonials-section">
      <div class="container">
        <div class="section-header text-center">
          <h2 class="section-title animate-text">Success Stories</h2>
          <p class="section-subtitle animate-text">Hear from students who achieved their dreams with our guidance</p>
        </div>

        <div class="testimonials-carousel">
          <div class="testimonial-track">
            <!-- Testimonial 1 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-priya.jpg" alt="Priya Sharma" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"EDU Career India helped me secure a direct seat in RVCE, Bangalore. Their guidance was precise and highly professional. Highly recommended!"</p>
                <h4 class="testimonial-name">Priya Sharma</h4>
                <p class="testimonial-course">B.Tech CSE, RVCE Bangalore</p>
              </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-rahul.jpg" alt="Rahul Verma" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Getting into a top MBBS program seemed impossible, but the team made the entire process seamless. Thank you for making my dream a reality!"</p>
                <h4 class="testimonial-name">Rahul Verma</h4>
                <p class="testimonial-course">MBBS, JSS Medical College, Mysore</p>
              </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-anjali.jpg" alt="Anjali Patel" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"I was confused about which MBA college to choose. EDU Career India guided me in selecting the right specialization. Excellent service!"</p>
                <h4 class="testimonial-name">Anjali Patel</h4>
                <p class="testimonial-course">MBA Marketing, SIBM Pune</p>
              </div>
            </div>

            <!-- Testimonial 4 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-arjun.jpg" alt="Arjun Singh" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"The counselors understood my profile perfectly and helped me get into my dream pharmacy college. Forever grateful!"</p>
                <h4 class="testimonial-name">Arjun Singh</h4>
                <p class="testimonial-course">B.Pharma, Manipal College</p>
              </div>
            </div>

            <!-- Testimonial 5 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-sneha.jpg" alt="Sneha Reddy" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Study abroad counseling was exceptional. Got admission to my dream university in Canada with scholarship!"</p>
                <h4 class="testimonial-name">Sneha Reddy</h4>
                <p class="testimonial-course">MS Computer Science, University of Toronto</p>
              </div>
            </div>

            <!-- Testimonial 6 -->
            <div class="testimonial-card">
              <div class="testimonial-image">
                <img src="/assets/images/testimonial-vikram.jpg" alt="Vikram Kumar" loading="lazy">
              </div>
              <div class="testimonial-content">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"Professional, transparent, and result-oriented. Got admission to a top agricultural university within weeks!"</p>
                <h4 class="testimonial-name">Vikram Kumar</h4>
                <p class="testimonial-course">B.Sc Agriculture, Punjab Agricultural University</p>
              </div>
            </div>
          </div>

          <!-- Carousel Controls -->
          <div class="carousel-controls">
            <button class="carousel-btn prev" aria-label="Previous testimonial">‹</button>
            <button class="carousel-btn next" aria-label="Next testimonial">›</button>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section" style="background-image: url('/assets/images/cta-background.jpg');">
      <div class="cta-overlay"></div>
      <div class="container">
        <div class="cta-content">
          <h2 class="cta-title animate-text">Ready to Start Your Journey?</h2>
          <p class="cta-subtitle animate-text">Book a free consultation with our expert counselors today</p>
          <a href="/contact" class="btn btn-gold btn-glow btn-large">Get Started Today</a>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-column">
          <div class="footer-logo">
            <span class="logo-edu">EDU</span>
            <span class="logo-career">Career</span>
            <span class="logo-india">India</span>
          </div>
          <p class="footer-tagline">Your Dream, Our Mission</p>
          <p class="footer-text">Your trusted partner for career counseling and direct admissions.</p>
          <div class="footer-contact">
            <p>📞 +91-XXXXXXXXXX</p>
            <p>✉️ info@educareerindia.com</p>
          </div>
        </div>

        <div class="footer-column">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/courses">Courses</a></li>
            <li><a href="/universities">Universities</a></li>
            <li><a href="/contact">Contact Us</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Our Courses</h4>
          <ul>
            <li><a href="/courses#mbbs">MBBS Admission</a></li>
            <li><a href="/courses#btech">B.Tech Engineering</a></li>
            <li><a href="/courses#bpharma">B.Pharma Programs</a></li>
            <li><a href="/courses#agriculture">B.Sc Agriculture</a></li>
            <li><a href="/courses#mba">MBA & PGDM</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Study Destinations</h4>
          <ul>
            <li><a href="/universities#india">India</a></li>
            <li><a href="/universities#usa">United States</a></li>
            <li><a href="/universities#uk">United Kingdom</a></li>
            <li><a href="/universities#australia">Australia</a></li>
            <li><a href="/universities#canada">Canada</a></li>
            <li><a href="/universities#dubai">Dubai</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 EDU Career India. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JAVASCRIPT -->
  <script src="/assets/js/header-scroll.js" defer></script>
  <script src="/assets/js/lenis-scroll.js" defer></script>
  <script src="/assets/js/animations.js" defer></script>
  <script src="/assets/js/globe.js" defer></script>
  <script src="/assets/js/sliders.js" defer></script>
  <script src="/assets/js/main.js" defer></script>

</body>
</html>
