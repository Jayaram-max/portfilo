# Jayaram Portfolio Website

A modern, responsive portfolio website built with HTML, CSS, JavaScript, PHP, and MySQL. Features a clean design, contact form with backend processing, and an admin panel for message management.

## 🚀 Features

### Frontend
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Modern UI**: Clean, professional design with smooth animations
- **Interactive Elements**: Hover effects, smooth scrolling, and typing animation
- **Contact Form**: Fully functional with validation and AJAX submission

### Backend
- **PHP Backend**: Secure contact form processing
- **MySQL Database**: Stores messages, visitor data, and site settings
- **Admin Panel**: View and manage contact messages
- **Security Features**: CSRF protection, rate limiting, input sanitization
- **Email Notifications**: Automatic email alerts for new messages

## 📋 Requirements

- **Web Server**: Apache or Nginx
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **XAMPP/WAMP**: For local development (recommended)

## 🛠️ Installation

### Option 1: Using XAMPP (Recommended for beginners)

1. **Download and Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and start Apache and MySQL services

2. **Setup Project**
   ```bash
   # Copy project files to XAMPP htdocs folder
   # Usually: C:\xampp\htdocs\porfilo
   ```

3. **Run Setup Script**
   - Open browser and go to: `http://localhost/porfilo/setup.php`
   - Follow the setup instructions

### Option 2: Manual Setup

1. **Create Database**
   ```sql
   CREATE DATABASE jayaram_portfolio;
   ```

2. **Import Database Schema**
   ```bash
   mysql -u root -p jayaram_portfolio < database.sql
   ```

3. **Configure Database Connection**
   - Edit `config/database.php`
   - Update database credentials if needed

4. **Set File Permissions**
   ```bash
   chmod 755 config/
   chmod 755 api/
   chmod 755 admin/
   chmod 755 logs/
   ```

## 🔧 Configuration

### Database Configuration
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'jayaram_portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Email Configuration
Update email settings in `api/contact_handler.php`:
```php
$admin_email = 'your-email@example.com';
```

### Admin Panel
- **URL**: `http://localhost/porfilo/admin/`
- **Default Login**: 
  - Username: `admin`
  - Password: `admin123`
- **⚠️ Change default password in production!**

## 📁 Project Structure

```
porfilo/
├── index.html              # Main portfolio page
├── styles.css              # CSS styles
├── script.js               # JavaScript functionality
├── database.sql            # Database schema
├── setup.php               # Setup script
├── config/
│   ├── database.php        # Database configuration
│   └── security.php        # Security functions
├── api/
│   └── contact_handler.php # Contact form API
├── admin/
│   └── index.php           # Admin panel
└── logs/                   # Security logs (auto-created)
```

## 🎨 Customization

### Colors and Styling
Edit `styles.css` to customize:
- Primary color: `#007bff`
- Accent gray: `#6c757d`
- Background: `#ffffff`
- Text color: `#343a40`

### Content
- **Personal Info**: Update in `index.html`
- **Skills**: Modify in database or HTML
- **Projects**: Add/remove in database or HTML
- **Contact Info**: Update in `index.html` and database

### Database Content
Use the admin panel or directly edit the database:
- **Skills**: `skills` table
- **Projects**: `projects` table
- **Site Settings**: `site_settings` table

## 🔒 Security Features

- **CSRF Protection**: Prevents cross-site request forgery
- **Rate Limiting**: Prevents spam and brute force attacks
- **Input Sanitization**: Protects against XSS attacks
- **SQL Injection Prevention**: Uses prepared statements
- **Session Security**: Secure session management
- **File Upload Security**: Validates file types and sizes

## 📊 Admin Panel Features

- **Message Management**: View, mark as read/replied
- **Visitor Statistics**: Track website visitors
- **Email Integration**: Reply to messages directly
- **Security Logs**: Monitor security events

## 🚀 Deployment

### Production Checklist
- [ ] Change default admin password
- [ ] Update database credentials
- [ ] Enable HTTPS
- [ ] Set up regular database backups
- [ ] Configure email settings
- [ ] Test all functionality
- [ ] Set up monitoring

### Web Hosting
1. Upload files to web server
2. Create MySQL database
3. Import `database.sql`
4. Update `config/database.php`
5. Test contact form and admin panel

## 🐛 Troubleshooting

### Common Issues

**Contact form not working:**
- Check PHP error logs
- Verify database connection
- Ensure `api/contact_handler.php` is accessible

**Admin panel login issues:**
- Check database connection
- Verify admin user exists in database
- Check session configuration

**Database connection errors:**
- Verify MySQL is running
- Check database credentials
- Ensure database exists

### Debug Mode
Enable error reporting in `config/database.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For support or questions:
- Email: jayaram.be@email.com
- GitHub Issues: [Create an issue](https://github.com/yourusername/portfolio/issues)

## 🔄 Updates

### Version 1.0.0
- Initial release
- Basic portfolio functionality
- Contact form with PHP backend
- Admin panel
- Security features

---

**Made with ❤️ by Jayaram**
