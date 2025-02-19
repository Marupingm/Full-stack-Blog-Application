# PHP Blog Application

A full-featured blog application built with PHP, MySQL, and Bootstrap.

## Features

- 🔐 User Authentication (Login/Signup)
- 📝 Create, Read, Update, Delete (CRUD) Blog Posts
- 💬 Comments System
- 👥 User Management
- 🎨 Responsive Bootstrap UI
- 📱 Mobile-Friendly Design
- 🔍 SEO-Friendly URLs
- 📊 Admin Panel
- 📄 Pagination

## Requirements

- PHP 8.2 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for SEO-friendly URLs)

## Installation

1. Clone the repository to your web server's directory:
```bash
git clone https://github.com/yourusername/blog.git
```

2. Create a MySQL database and import the schema:
```bash
mysql -u root -p < database.sql
```

3. Configure the database connection in `db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'blog');
```

4. Make sure the `assets` directory and all PHP files are accessible by your web server.

5. Set up URL rewriting (optional, for SEO-friendly URLs):
Create a `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Default Admin Account

- Username: admin
- Password: admin123

## Directory Structure

```
/blog
│── /assets/          # CSS, JS, images
│── db.php            # Database connection
│── index.php         # Homepage
│── post.php          # Single post view
│── create.php        # Create new post
│── edit.php          # Edit post
│── delete.php        # Delete post
│── auth.php          # Authentication
│── admin.php         # Admin panel
│── database.sql      # Database schema
│── README.md         # Documentation
```

## Usage

1. Visit the homepage at `http://your-domain.com/blog/`
2. Register a new account or log in
3. Create, edit, and manage blog posts
4. Comment on posts
5. Access the admin panel (admin users only)

## Security Features

- Password hashing using PHP's password_hash()
- Prepared statements for SQL queries
- XSS protection with htmlspecialchars()
- CSRF protection
- Input validation and sanitization
- Secure session handling

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainer. 