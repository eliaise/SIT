# Web server
1. Change the IP in all of the conf files and in the hosts file to your own VM IP
2. Run the setup.sh script
3. Copy the contents of the dumpNew.sql into the mysql console
4. Run the query below in the mysql console:
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';

**Note: if you decided to use a different password, modify the file in ./public/helpers/mysql.php**

5. Run the commands listed in hardening.txt