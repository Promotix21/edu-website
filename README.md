# EDU Career India - Website

SEO-optimized education consultancy website with Docker support.

## Features

- ✅ SEO Optimized (Meta tags, Canonical URLs, Schema markup)
- ✅ Responsive Design (Mobile-first approach)
- ✅ Professional Pages (Home, About, Courses, Universities, Contact)
- ✅ Docker Support (Apache + MySQL + phpMyAdmin)
- ✅ Modern UI/UX with smooth animations
- ✅ Contact form with validation
- ✅ FAQ section with schema markup

## Quick Start with Docker

### Prerequisites
- Docker installed on your system
- Docker Compose installed

### Setup Instructions

1. **Start the containers**:
   ```bash
   docker-compose up -d
   ```

2. **Access the website**:
   - Website: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

3. **Database Credentials**:
   - Host: `db` (from within containers) or `localhost:3307` (from host)
   - Database: `educareer_db`
   - Username: `educareer_user`
   - Password: `educareer_pass_2025`
   - Root Password: `root_password_2025`

4. **Stop the containers**:
   ```bash
   docker-compose down
   ```

5. **View logs**:
   ```bash
   docker-compose logs -f web
   docker-compose logs -f db
   ```

## Project Structure

```
edu-website/
├── index.html              # Homepage
├── about.html              # About page
├── courses.html            # Courses page
├── universities.html       # Universities page
├── contact.html            # Contact page
├── robots.txt              # SEO robots file
├── sitemap.xml             # XML sitemap
├── assets/
│   ├── css/
│   │   └── main.css        # Main stylesheet
│   ├── js/
│   │   └── main.js         # Main JavaScript
│   └── images/             # Image assets
├── database/
│   └── init.sql            # Database initialization
├── admin/                  # Admin panel (to be developed)
├── uploads/                # File uploads directory
├── docker-compose.yml      # Docker services configuration
├── Dockerfile              # Web server image
└── README.md               # This file
```

## SEO Features

### Implemented:
- ✅ Proper meta titles and descriptions (50-60 & 150-160 chars)
- ✅ Canonical URLs on all pages
- ✅ Schema.org structured data (Organization, LocalBusiness, BreadcrumbList)
- ✅ Open Graph tags for social media
- ✅ Twitter Card tags
- ✅ XML Sitemap
- ✅ Robots.txt
- ✅ Semantic HTML5 markup
- ✅ Alt text for images
- ✅ Proper heading hierarchy (H1, H2, H3)
- ✅ Internal linking structure
- ✅ Mobile-responsive design
- ✅ Fast loading (optimized CSS/JS)

## Development

### Without Docker (Local Development)
If you want to run without Docker:
1. Set up a local Apache/PHP/MySQL environment (XAMPP, WAMP, MAMP, etc.)
2. Copy files to your web server document root
3. Import `database/init.sql` into MySQL
4. Update database credentials in PHP files (when created)

### Building Custom Changes
After making changes to Dockerfile:
```bash
docker-compose build --no-cache
docker-compose up -d
```

## Admin Panel
The admin panel for content management will be developed in `/admin` directory with:
- User authentication
- Content management (CRUD)
- SEO meta tags management
- Contact form submissions viewer
- Statistics dashboard

## Security Notes
⚠️ **Important for Production**:
1. Change all default passwords in `docker-compose.yml`
2. Update admin password in database
3. Use environment variables for sensitive data
4. Enable HTTPS/SSL
5. Configure firewall rules
6. Regular security updates

## Support
For issues or questions, contact: info@educareerindia.com

## License
Proprietary - EDU Career India © 2025
