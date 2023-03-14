base flow is: 
welcome.php -> login-default.php -> config.php -> welcome.php -> logout.php

welcome.php should be substituted with private page source codes. when user first enters internal website, he should be brought to this page first, then redirected to the login page if the user is not logged in.

login-default.php references config.php, which contains the mysql db credentials to connect to the db. it also contains codes to check the credentials entered in the form against the database, and only allow the user to enter if both the username and password are entered correctly

logout.php is just to end the user session

