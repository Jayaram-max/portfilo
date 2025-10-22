<?php
/**
 * Portfolio Setup Script
 * Run this once to set up the database and initial data
 */

// Database configuration
$host = 'localhost';
$dbname = 'jayaram_portfolio';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
    
    echo "<h2>Setting up Jayaram Portfolio Database...</h2>";
    echo "<p>✓ Database created successfully</p>";
    
    // Read and execute SQL file
    $sql = file_get_contents('database.sql');
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "<p>✓ Database tables created successfully</p>";
    echo "<p>✓ Sample data inserted successfully</p>";
    
    // Test database connection
    $test_conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $test_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✓ Database connection test successful</p>";
    
    // Check if files exist
    $required_files = [
        'config/database.php',
        'api/contact_handler.php',
        'admin/index.php'
    ];
    
    $all_files_exist = true;
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            echo "<p style='color: red;'>✗ Missing file: $file</p>";
            $all_files_exist = false;
        } else {
            echo "<p>✓ File exists: $file</p>";
        }
    }
    
    if ($all_files_exist) {
        echo "<h3 style='color: green;'>🎉 Setup Complete!</h3>";
        echo "<p><strong>Next Steps:</strong></p>";
        echo "<ul>";
        echo "<li>1. Open <a href='index.html'>index.html</a> to view your portfolio</li>";
        echo "<li>2. Test the contact form</li>";
        echo "<li>3. Access admin panel at <a href='admin/index.php'>admin/index.php</a></li>";
        echo "<li>4. Default admin login: username: admin, password: admin123</li>";
        echo "<li>5. Change the admin password in production!</li>";
        echo "</ul>";
        
        echo "<h3>Admin Panel Features:</h3>";
        echo "<ul>";
        echo "<li>View all contact form messages</li>";
        echo "<li>Mark messages as read/replied</li>";
        echo "<li>Reply to messages via email</li>";
        echo "<li>View visitor statistics</li>";
        echo "</ul>";
        
        echo "<h3>Security Notes:</h3>";
        echo "<ul>";
        echo "<li>Change default admin password</li>";
        echo "<li>Update database credentials in config/database.php</li>";
        echo "<li>Enable HTTPS in production</li>";
        echo "<li>Regular database backups recommended</li>";
        echo "</ul>";
        
    } else {
        echo "<h3 style='color: red;'>Setup Incomplete</h3>";
        echo "<p>Please ensure all required files are present and try again.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Setup Failed</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database credentials and try again.</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f8f9fa;
}
h2, h3 {
    color: #333;
}
p {
    margin: 10px 0;
}
ul {
    margin: 10px 0;
    padding-left: 20px;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>
