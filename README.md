CSRF DEMO - HOW TO RUN THIS PROJECT!!!


# Prequisites check ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Before running the program make sure you have Apache and mysql installed
If not, type in the terminal:
	sudo apt update
	sudo apt install -y apache2 php libapache2-mod-php php-mysql mysql-server

If so, type in the terminal to start:
	sudo systemctl start apache2
	sudo systemctl start mysql
	sudo systemctl enable apache2
	sudo systemctl enable mysql
	
To setup the initial database in SQL:
	sudo mysql
	CREATE DATABASE testDB;
	CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin';
	GRANT ALL PRIVILEGES ON testDB.* TO 'admin'@'localhost';
	FLUSH PRIVILEGES;
	EXIT;


# Loading the Website ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To make things simple place the files "update.php" and "index.php" in your downloads folder.
In terminal, type:
	sudo mkdir /var/www/html/csrf_demo
	sudo cp -r /home/"YOURUSERNAME"/Downloads/* /var/www/html/csrf_demo/

Server has been initiated now you can proceed.
In your browser, type:
	http://localhost/csrf_demo/update.php

You should see "Request to change email address" followed by the input fields.
IF you see "Error! 1051" at the top of the page. Reload the page and it should disappear (just means the tables haven't loaded in the index).

Now in the browser, type:
	http://localhost/csrf_demo/index.php
	
You should see a table of account details (User ID, Username and Email).

User ID    Username    Email
1234       Tidus       tidus@example.com
2222       Yuna        yuna@example.com
....       ....        ....

# IN CASE OF ANY ERRORS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you see a blank page or "403 Forbidden" when loading the website.
In terminal, type:
	sudo chown -R www-data:www-data /var/www/html/csrf_demo
	sudo chown -R 755 /var/www/html/csrf_demo

 

