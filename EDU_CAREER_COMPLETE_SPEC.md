# EDU CAREER INDIA - Complete Website Redesign Specification

## PROJECT OVERVIEW
Client: EDU Career India
Tagline: "Your Dream, Our Mission"
Industry: Education & Career Counseling

### Core Services
- Direct admission for MBBS, B.Tech, B.Pharma, B.Sc Agriculture, MBA
- Career counseling for students across India & Abroad
- Partner with 200+ institutions
- 5000+ students counseled | 95% success rate | 15+ years experience

---

## TECHNOLOGY STACK

### Frontend
- HTML5, CSS3, Vanilla JavaScript (ES6+), PHP 8.0+
- GSAP 3.12+ with ScrollTrigger
- Lenis Smooth Scroll  
- Three.js r158+ (for 3D globe)
- SplitType.js (text animation)

### Backend
- PHP 8.0+ with PDO
- MySQL 8.0+
- PHPMailer for emails
- Session-based authentication

---

## CRITICAL ANIMATION REQUIREMENTS

### GSAP Best Practices

**OPACITY MANAGEMENT - CRITICAL**
```javascript
// ❌ WRONG - Element may never reach 100% opacity
gsap.from('.element', {
  opacity: 0,
  y: 50
});

// ✅ CORRECT - Always use fromTo with explicit end state
gsap.fromTo('.element',
  { opacity: 0, y: 50 },
  { 
    opacity: 1, 
    y: 0,
    duration: 1,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.element',
      start: 'top 80%',
      once: true
    }
  }
);
```

### Text Animation - Word Breaking Prevention

```javascript
// Split text into words (NOT characters to prevent mid-word breaking)
const splitText = new SplitType('.animate-text', {
  types: 'words',
  tagName: 'span'
});

// Prevent word wrapping issues
splitText.words.forEach(word => {
  word.style.display = 'inline-block';
  word.style.whiteSpace = 'nowrap'; // CRITICAL - prevents mid-word breaks
});

// Animate with blur effect
gsap.fromTo('.animate-text .word',
  { opacity: 0, filter: 'blur(10px)', y: 20 },
  {
    opacity: 1,
    filter: 'blur(0px)',
    y: 0,
    duration: 0.8,
    stagger: 0.05,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.animate-text',
      start: 'top 85%',
      once: true
    }
  }
);

// Recalculate on resize
let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    SplitType.revert('.animate-text');
    initTextAnimation();
  }, 250);
});
```

### Image Animations

**Reveal Effect**
```javascript
gsap.timeline({
  scrollTrigger: {
    trigger: '.image-reveal',
    start: 'top 80%',
    once: true
  }
})
.fromTo('.image-reveal-overlay',
  { scaleX: 0, transformOrigin: 'left' },
  { scaleX: 1, duration: 0.8, ease: 'power2.inOut' }
)
.fromTo('.image-reveal-img',
  { scale: 1.3, opacity: 0 },
  { scale: 1, opacity: 1, duration: 1, ease: 'power2.out' },
  0.3
)
.to('.image-reveal-overlay',
  { scaleX: 0, transformOrigin: 'right', duration: 0.8, ease: 'power2.inOut' },
  0.8
);
```

**Parallax Effect**
```javascript
gsap.to('.parallax-image img', {
  yPercent: 30,
  ease: 'none',
  scrollTrigger: {
    trigger: '.parallax-image',
    start: 'top bottom',
    end: 'bottom top',
    scrub: 1
  }
});
```

### Lenis Smooth Scroll Setup

```javascript
import Lenis from '@studio-freight/lenis';

const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  smooth: true,
  mouseMultiplier: 1,
  smoothTouch: false
});

// Sync with GSAP
lenis.on('scroll', ScrollTrigger.update);

gsap.ticker.add((time) => {
  lenis.raf(time * 1000);
});

gsap.ticker.lagSmoothing(0);
```

---

## THREE.JS ROTATING GLOBE

**Purpose:** Display service locations (India, USA, UK, Australia, Canada, Dubai)

```javascript
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

class InteractiveGlobe {
  constructor(container) {
    this.container = container;
    this.scene = new THREE.Scene();
    this.camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 1000);
    this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    this.init();
  }
  
  init() {
    // Renderer setup
    this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    this.container.appendChild(this.renderer.domElement);
    
    // Camera
    this.camera.position.z = 5;
    
    // Create globe with Earth texture
    const geometry = new THREE.SphereGeometry(2, 64, 64);
    const textureLoader = new THREE.TextureLoader();
    const earthTexture = textureLoader.load('/assets/images/earth-map.jpg');
    
    const material = new THREE.MeshPhongMaterial({
      map: earthTexture,
      bumpMap: earthTexture,
      bumpScale: 0.05,
      specular: new THREE.Color(0x333333),
      shininess: 5
    });
    
    this.globe = new THREE.Mesh(geometry, material);
    this.scene.add(this.globe);
    
    // Add location markers
    this.addLocationMarkers();
    
    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    this.scene.add(ambientLight);
    
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 3, 5);
    this.scene.add(directionalLight);
    
    // Controls
    this.controls = new OrbitControls(this.camera, this.renderer.domElement);
    this.controls.enableDamping = true;
    this.controls.enableZoom = false;
    this.controls.autoRotate = true;
    this.controls.autoRotateSpeed = 0.5;
    
    this.animate();
    window.addEventListener('resize', () => this.onResize());
  }
  
  addLocationMarkers() {
    const locations = [
      { name: 'India', lat: 20.5937, lng: 78.9629, color: '#f59e0b' },
      { name: 'USA', lat: 37.0902, lng: -95.7129, color: '#3b82f6' },
      { name: 'UK', lat: 55.3781, lng: -3.4360, color: '#10b981' },
      { name: 'Australia', lat: -25.2744, lng: 133.7751, color: '#ef4444' },
      { name: 'Canada', lat: 56.1304, lng: -106.3468, color: '#8b5cf6' },
      { name: 'Dubai', lat: 25.2048, lng: 55.2708, color: '#ec4899' }
    ];
    
    locations.forEach(loc => {
      const marker = this.createMarker(loc.lat, loc.lng, loc.color);
      this.globe.add(marker);
      this.pulseMarker(marker);
    });
  }
  
  createMarker(lat, lng, color) {
    // Convert lat/lng to 3D coordinates
    const phi = (90 - lat) * (Math.PI / 180);
    const theta = (lng + 180) * (Math.PI / 180);
    
    const x = -(2 * Math.sin(phi) * Math.cos(theta));
    const z = (2 * Math.sin(phi) * Math.sin(theta));
    const y = (2 * Math.cos(phi));
    
    // Create marker
    const markerGeometry = new THREE.SphereGeometry(0.05, 16, 16);
    const markerMaterial = new THREE.MeshBasicMaterial({ color: color });
    const marker = new THREE.Mesh(markerGeometry, markerMaterial);
    marker.position.set(x, y, z);
    
    // Add glow ring
    const ringGeometry = new THREE.RingGeometry(0.06, 0.08, 32);
    const ringMaterial = new THREE.MeshBasicMaterial({
      color: color,
      transparent: true,
      opacity: 0.5,
      side: THREE.DoubleSide
    });
    const ring = new THREE.Mesh(ringGeometry, ringMaterial);
    ring.lookAt(0, 0, 0);
    marker.add(ring);
    
    return marker;
  }
  
  pulseMarker(marker) {
    gsap.to(marker.scale, {
      x: 1.5, y: 1.5, z: 1.5,
      duration: 1,
      repeat: -1,
      yoyo: true,
      ease: 'power1.inOut'
    });
  }
  
  animate() {
    requestAnimationFrame(() => this.animate());
    this.controls.update();
    this.renderer.render(this.scene, this.camera);
  }
  
  onResize() {
    this.camera.aspect = this.container.clientWidth / this.container.clientHeight;
    this.camera.updateProjectionMatrix();
    this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
  }
}

// Initialize
const globeContainer = document.querySelector('#globe-container');
if (globeContainer) {
  new InteractiveGlobe(globeContainer);
}
```

---

## BACKEND CMS ARCHITECTURE

### File Structure
```
/admin/
├── index.php (Dashboard)
├── login.php
├── logout.php
├── pages/
│   ├── edit-home.php
│   ├── edit-about.php
│   ├── edit-courses.php
│   ├── edit-success.php
│   └── edit-contact.php
├── media/
│   ├── upload.php
│   └── gallery.php
├── seo/
│   ├── meta-tags.php
│   └── robots.php
├── settings/
│   ├── smtp.php
│   └── general.php
└── includes/
    ├── config.php
    ├── db.php
    ├── auth.php
    └── functions.php
```

### Authentication System

```php
// /admin/includes/auth.php

// Secure session config
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();

function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    // Check if locked
    if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
        return ['success' => false, 'message' => 'Account locked. Try again later.'];
    }
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Reset login attempts
        $stmt = $pdo->prepare("UPDATE admin_users SET login_attempts = 0, last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Set session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        return ['success' => true];
    } else {
        // Increment attempts
        $attempts = $user['login_attempts'] + 1;
        $locked_until = null;
        
        if ($attempts >= 5) {
            $locked_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        }
        
        $stmt = $pdo->prepare("UPDATE admin_users SET login_attempts = ?, locked_until = ? WHERE id = ?");
        $stmt->execute([$attempts, $locked_until, $user['id']]);
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
}

function requireLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
```

### CSRF Protection

```php
// Generate token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Add to forms
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

// Check in handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('CSRF validation failed');
    }
}
```

### Content Management

```php
// Get page content
function getPageContent($page, $section, $key = null) {
    global $pdo;
    
    $query = "SELECT * FROM page_content WHERE page_name = ? AND section_name = ?";
    $params = [$page, $section];
    
    if ($key !== null) {
        $query .= " AND content_key = ?";
        $params[] = $key;
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    return $key !== null ? $stmt->fetch()['content_value'] ?? '' : $stmt->fetchAll();
}

// Secure file upload
function secureFileUpload($file) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File too large'];
    }
    
    // Validate MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'error' => 'Invalid file type'];
    }
    
    // Generate unique filename
    $newFilename = uniqid() . '-' . time() . '.' . $ext;
    $uploadPath = __DIR__ . '/../../uploads/' . $newFilename;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Optimize image
        optimizeImage($uploadPath, $ext);
        
        return ['success' => true, 'filename' => $newFilename];
    }
    
    return ['success' => false, 'error' => 'Upload failed'];
}
```

---

## DATABASE SCHEMA

```sql
-- Admin users
CREATE TABLE admin_users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMP NULL,
  login_attempts INT DEFAULT 0,
  locked_until TIMESTAMP NULL
);

-- Page content
CREATE TABLE page_content (
  id INT PRIMARY KEY AUTO_INCREMENT,
  page_name VARCHAR(50) NOT NULL,
  section_name VARCHAR(100) NOT NULL,
  content_type ENUM('text', 'html', 'image', 'url') NOT NULL,
  content_key VARCHAR(100) NOT NULL,
  content_value TEXT,
  display_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SEO meta tags
CREATE TABLE seo_meta (
  id INT PRIMARY KEY AUTO_INCREMENT,
  page_name VARCHAR(50) NOT NULL UNIQUE,
  meta_title VARCHAR(100),
  meta_description VARCHAR(200),
  focus_keywords TEXT,
  canonical_url VARCHAR(255),
  og_image VARCHAR(255),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE testimonials (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_name VARCHAR(100) NOT NULL,
  course VARCHAR(100),
  college VARCHAR(200),
  batch_year VARCHAR(20),
  testimonial_text TEXT NOT NULL,
  rating INT DEFAULT 5,
  display_order INT DEFAULT 0,
  is_featured BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Statistics
CREATE TABLE site_statistics (
  id INT PRIMARY KEY AUTO_INCREMENT,
  stat_key VARCHAR(50) NOT NULL UNIQUE,
  stat_value INT NOT NULL,
  stat_label VARCHAR(100),
  stat_icon VARCHAR(100),
  display_order INT DEFAULT 0
);

-- Contact submissions
CREATE TABLE contact_submissions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  course VARCHAR(100),
  city VARCHAR(100),
  message TEXT,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_read BOOLEAN DEFAULT FALSE
);

-- Colleges
CREATE TABLE colleges (
  id INT PRIMARY KEY AUTO_INCREMENT,
  state VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  college_name VARCHAR(255) NOT NULL,
  course_type ENUM('btech', 'mba', 'mbbs', 'bpharma', 'agriculture') NOT NULL,
  fee_amount DECIMAL(10,2),
  fee_period VARCHAR(50),
  program_details TEXT,
  accreditation VARCHAR(100),
  display_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE
);

-- Settings
CREATE TABLE site_settings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT,
  setting_category VARCHAR(50),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## SEO IMPLEMENTATION

### Meta Tags Template

```html
<!-- Homepage -->
<title>EDU Career India - Direct Admission MBBS, B.Tech, B.Pharma, MBA | Career Counseling</title>
<meta name="description" content="Expert career counseling & direct admission services for MBBS, B.Tech, B.Pharma, Agriculture, MBA. 5000+ students placed in top colleges across India & abroad.">
<meta name="keywords" content="career counseling India, direct admission MBBS, engineering college admission, study abroad consultants">
<link rel="canonical" href="https://www.educareerindia.com/">

<!-- Open Graph -->
<meta property="og:title" content="EDU Career India - Your Trusted Education Partner">
<meta property="og:description" content="Expert career counseling and direct admissions for professional courses">
<meta property="og:image" content="/assets/images/og-image.jpg">
<meta property="og:url" content="https://www.educareerindia.com/">
```

### Schema Markup

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "EDU Career India",
  "description": "Career counseling and direct admission services",
  "url": "https://www.educareerindia.com",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "123, Education Plaza, MG Road",
    "addressLocality": "Bangalore",
    "addressRegion": "Karnataka",
    "postalCode": "560001",
    "addressCountry": "IN"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91-9876543210",
    "contactType": "Customer Service"
  }
}
</script>
```

### Robots.txt

```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /includes/

Sitemap: https://www.educareerindia.com/sitemap.xml
```

---

## ENHANCED CONTENT STRUCTURE

### Homepage Hero
```
Title: "Your Gateway to Top Universities in India & Abroad"
Subtitle: "Expert career counseling and direct admission guidance for MBBS, B.Tech, B.Pharma, and Agriculture programs. Your dream college is just a consultation away."
CTA: "Start Your Journey" / "Book Free Consultation"
```

### Services Overview
- **MBBS:** Direct admission in top medical colleges, NEET counseling, management quota
- **B.Tech:** Engineering admission in premier institutes, all branches, JEE guidance
- **B.Pharma:** Pharmacy college admission, AICTE approved programs
- **Agriculture:** B.Sc Agriculture admission, modern farming education
- **MBA/PGDM:** Top B-schools, CAT/MAT guidance, executive programs

### Key Statistics
- 5000+ Students Counseled
- 95% Success Rate
- 200+ Partner Colleges
- 15+ Years Experience

### Testimonials Content
```
"EDU Career India helped me secure a direct seat in RVCE, Bangalore. Their guidance was precise and highly professional. Highly recommended!"
- Priya Sharma, B.Tech CSE, RVCE Bangalore

"Getting into a top MBBS program seemed impossible, but the team made the entire process seamless. Thank you for making my dream a reality."
- Rahul Verma, MBBS, JSS Medical College, Mysore
```

---

## PAGE STRUCTURE

### All Pages Include:
1. **Hero Section** - Full viewport with animated text
2. **Main Content** - Split layouts, image reveals, parallax effects
3. **CTA Section** - Prominent call-to-action before footer
4. **Footer** - 4 columns (About, Links, Courses, Contact)

### Homepage Sections:
1. Hero with particle background
2. Stats counter section
3. Why Choose Us (4 feature cards)
4. Services overview (5 courses)
5. Global presence (Three.js globe)
6. Success stories preview
7. CTA section

### Courses Page:
- Alternating image-text layouts for each course
- Detailed info: eligibility, duration, career prospects
- Comparison table (optional)
- Admission process flow

### Universities Page:
- Filter panel (state, city, course type, fee range)
- State-wise accordion with college tables
- Sortable columns, highlight on hover

### Contact Page:
- Contact form with validation
- Contact information cards
- Google Maps embed
- FAQ accordion

---

## PERFORMANCE OPTIMIZATION

### Image Optimization
```php
function optimizeImage($path, $ext) {
    $maxWidth = 1920;
    $quality = 85;
    
    list($width, $height) = getimagesize($path);
    
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = floor($height * ($maxWidth / $width));
    } else {
        $newWidth = $width;
        $newHeight = $height;
    }
    
    $source = imagecreatefromjpeg($path);
    $optimized = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagejpeg($optimized, $path, $quality);
    
    imagedestroy($source);
    imagedestroy($optimized);
}
```

### Lazy Loading
```html
<img src="/assets/images/college.jpg" alt="College" loading="lazy">
```

### Caching
```apache
# .htaccess
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## SECURITY CHECKLIST

```
☐ Use password_hash() for passwords
☐ Implement CSRF protection on all forms
☐ Use prepared statements (PDO) for all queries
☐ Sanitize all user inputs
☐ Validate file uploads (type, size, MIME)
☐ Set secure session cookies (httponly, secure)
☐ Implement brute force protection (login attempts)
☐ Use HTTPS throughout site
☐ Disable directory listing
☐ Keep PHP/MySQL updated
```

---

## RESPONSIVE BREAKPOINTS

```css
/* Mobile First */
@media (max-width: 575px) { /* Mobile */ }
@media (min-width: 576px) and (max-width: 767px) { /* Large Mobile */ }
@media (min-width: 768px) and (max-width: 991px) { /* Tablet */ }
@media (min-width: 992px) and (max-width: 1199px) { /* Laptop */ }
@media (min-width: 1200px) { /* Desktop */ }
```

---

## TESTING CHECKLIST

### Functional
☐ All pages load without errors
☐ Navigation works on all pages
☐ Forms validate and submit correctly
☐ Animations trigger on scroll
☐ Images lazy load properly
☐ Smooth scroll works
☐ Three.js globe renders
☐ Admin login/logout works
☐ Content CRUD operations work

### Performance
☐ Lighthouse score > 90
☐ Page load < 3 seconds
☐ Images optimized
☐ CSS/JS minified
☐ Caching enabled

### Security
☐ SQL injection prevention
☐ XSS protection
☐ CSRF tokens working
☐ File upload validation
☐ Session security

### SEO
☐ Meta tags on all pages
☐ Proper heading hierarchy
☐ Alt tags on images
☐ robots.txt accessible
☐ Sitemap generated
☐ Schema markup present

---

## DEPLOYMENT

### Pre-Deployment
☐ Set DEV_MODE = false
☐ Update database credentials
☐ Configure SMTP settings
☐ Test on staging environment
☐ Backup database
☐ Generate sitemap
☐ Create strong admin password

### Post-Deployment
☐ Verify HTTPS works
☐ Test contact form
☐ Submit sitemap to Google
☐ Set up Google Analytics
☐ Configure cron for backups
☐ Monitor error logs

---

## TIMELINE ESTIMATE

- **Design & Planning:** 3-5 days
- **Frontend Development:** 10-14 days  
- **Backend Development:** 7-10 days
- **Integration & Testing:** 5-7 days
- **Content Population:** 3-5 days
- **Deployment:** 2-3 days

**Total:** 30-44 days (6-9 weeks)

---

## KEY SUCCESS FACTORS

1. **Animation Quality:** Ensure smooth 60fps animations
2. **Text Breaking:** Prevent mid-word breaks in animations
3. **Opacity Management:** Always use fromTo with explicit end states
4. **Responsive Testing:** Test at all breakpoints
5. **Security First:** Never compromise on security measures
6. **Performance:** Optimize images, lazy load, cache properly
7. **SEO Foundation:** Build SEO into structure from day one

---

## DELIVERABLES

1. Complete website (frontend + backend)
2. Admin panel with CMS
3. Admin user manual
4. Source code (organized, commented)
5. Database backup
6. Training session
7. 30 days post-launch support

---

**END OF SPECIFICATION**

This document provides complete guidance for implementing EDU Career India's modern website with GSAP animations, Three.js globe, smooth scrolling, and a simple PHP backend CMS.
