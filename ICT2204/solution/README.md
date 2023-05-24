# Intended Solution
1. SQL injection to find usernames and passwords.
2. Identify the internal subdomain of the company.
3. Login with one of the usernames and passwords.
4. Upload PHP script disguised as an image.
5. Identify that there is a cronjob running in the background that is archiving all uploaded files.
6. Move from 'www-data' user to another user using the cronjob to secure a better foothold.
7. Identify a vulnerable service running locally.
8. Establish SSH forwarding and exploit to privilege escalate.


