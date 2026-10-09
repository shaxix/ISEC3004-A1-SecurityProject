CSRF DEMO - HOW TO RUN THIS PROJECT!!!


# Prequisites check 
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


# Loading the Website 

To make things simple place all files (index.php, login.php, profile.php and update.php) in your downloads folder.
In terminal, type:
	sudo mkdir /var/www/html/csrf_demo
	sudo cp -r /home/"YOURUSERNAME"/Downloads/* /var/www/html/csrf_demo/

Server has been initiated now you can proceed.
In your browser, type:
	http://localhost/csrf_demo/login.php

You should see a login page with a username and password input field.
URL is http://localhost/csrf_demo/index.php
Login details for the two users are as followed:

Username: Tidus
Password: 1234

Username: Yuna
Password: 1234

If you'd like to create your own account you may do so by clicking the register button.
Then fill in the username and password details.

After a successful login, you should be able to see a blog page showing cat images with a comments section below.
You can add a comment by writing in the input field. This is where the stored CSRF attack will take place.

On the top left of the blog page there should be a profile button with a small profile picture.
Clicking on it will lead you to the profile page in http://localhost/CSRF_demo/profile.php
There you should see the profile information of the logged in user as shown below:

My profile

Username    .....
Email       ..............
User ID     ....

Change email address
................

Change email
Go to blog      Logout

# Creating the stored CSRF attack

In your browser, type:
	http://localhost/csrf_demo/login.php

Log in with any user (Tidus 1234 or Yuna 1234).

Go to profile on the top right and check email details (should see tidus@example.com or yuna@example.com)

In the blog page place in the comments:
<img src="http://localhost/csrf_demo/update.php/?user_id=1234&new_email=New@New.com">
This is a stored CSRF attack placed within an image field.

Go back to the profile page and you should see the email has changed to New@New.com.
The attacker has successfully changed your email.

# IN CASE OF ANY ERRORS 

If you see a blank page or "403 Forbidden" when loading the website.
In terminal, type:
	sudo chown -R www-data:www-data /var/www/html/csrf_demo
	sudo chown -R 755 /var/www/html/csrf_demo

_________________________________________________________________________

# For Detection basic setup in new VM
0. To install Wazuh-manager
	sudo apt update
	sudo apt install -y curl gnupg apt-transport-https
	curl -fsSL https://packages.wazuh.com/key/GPG-KEY-WAZUH | sudo gpg --no-default-keyring --keyring gnupg-ring:/usr/share/keyrings/wazuh.gpg --import
	sudo chmod 644 /usr/share/keyrings/wazuh.gpg
	echo "deb [signed-by=/usr/share/keyrings/wazuh.gpg] https://packages.wazuh.com/4.x/apt/ stable main" | sudo tee /etc/apt/sources.list.d/wazuh.list
	sudo apt update
	sudo apt install -y wazuh-manager
	sudo systemctl daemon-reload
	sudo systemctl enable --now wazuh-manager

1. Create alert file
   	sudo touch /var/log/csrf-alerts.txt
	sudo chown www-data:www-data /var/log/csrf-alerts.txt
	sudo chmod 640 /var/log/csrf-alerts.txt

2. Tell Wazuh to collect the file
   	sudo nano /var/ossec/etc/ossec.conf
	**Add the following lines in the conf file at the end before </ossec_config>:
   	<localfile>
  		<location>/var/log/csrf-alerts.txt</location>
  		<log_format>json</log_format>
	</localfile>

3. Add Wazuh alert rule
	sudo nano /var/ossec/etc/rules/local_rules.xml
	**Add the following at the end
    <group name="blog,csrf,">
  		<rule id="100100" level="7">
    		<decoded_as>json</decoded_as>
    		<field name="alert" type="pcre2">^POSSIBLE CSRF$</field>
    		<description>Possible CSRF: email update through URL</description>
 		</rule>
	</group>
	**Then restart the wazuh-manager
   	sudo systemctl restart wazuh-manager
   	
4. Watch alerts as the admin
   	sudo tail -f /var/ossec/logs/alerts/alerts.log

5. Alert Log can be accessed in:
   	/var/ossec/logs/alerts/alerts.log
