Setup mysql steps
			1. Login to terminal as root user
						$	su -
		-Enter root password:
			2. start mysql as the root user for mysql
						-#	mysql -u root
			3. create a new mysql user to have access to the database(this is a seperate username and password than the debian username)
					none]>	CREATE USER 'projectuser'@'localhost';
					none]>	SELECT User,Host FROM mysql.user;
					none]>	use mysql
					mysql]>	update user set password=PASSWORD('^LkJMb') where User='projectuser';
					mysql]>	FLUSH PRIVILEGES;
					mysql]> quit
					BYE
			4. Grant the new user all permission to change all databases
					-#		mysql
					none]>	GRANT ALL ON *.* TO 'projectuser'@'localhost';
					none]>	SHOW GRANTS FOR 'projectuser'@'localhost';
					none]>	FLUSH PRIVILEGES;
					none]>	quit
					BYE
					-#		exit
					logout
			5. Create the 'maindb' database for the project website to access
					-$		mysql -u projectuser -p
		Enter Password:
					none]>	CREATE DATABASE maindb;
					none]>	show databases;
					none]>	exit
					BYE
			6. Change directory (cd) to the project website folder to before running further commands
					-$		cd /var/www/html/
			7. Import the the database.sql into the empty 'maindb' database. This builds the database structure.
	   /var/www/html/$		sudo mysql -u projectuser -p maindb < sql/database.sql
			8. Import the the dummy.sql into 'maindb' database. This adds the data in the dummy.sql file into the database tables
	   /var/www/html/$		sudo mysql -u projectuser -p maindb < sql/dummy.sql
			9. Open browser and navigate to localhost or the host debian IP address	`1