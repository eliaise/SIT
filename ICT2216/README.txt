Note that the docker-compose.yml in the root folder doesn't spawn the NGINX and PHP containers. 
They are spawned by Jenkins during the building process.
You can manually spawn them as well using the compose file in the practicaltest folder, but you will still need to manually copy the files in the src directory over.
This is due to a restriction with using DOOD that I have yet to find a fix for.

1. Docker installed (obviously)
2. NVD API key

# Setup
1. Find your docker group ID and modify groupadd command in ./jenkins/Dockerfile.
2. Run "git init --bare ./gitserver/repos/practical.git". The name is up to you but for demonstration purposes this is what I'll be going with.
3. Modify the lines marked with TODO in ./repository/Jenkinsfile.
    3.1. Replace repository.git with practical.git
    3.2. Replace testrun2 with practical (we will be using this name in SonarQube)
4. "docker volume create jenkins-data"
5. "docker compose up --build -d"
6. "docker exec -it jenkins-blueocean docker ps"
    6.1 If you get an error here about no permissions, you fucked up in step 1.

# Deployment
1. "git clone http://localhost:3000/practical.git"
2. "cp -r ./repository/* ./practical"
3. Commit and push.
4. Visit http://localhost:19000 and do your account setup (default credentials are admin:admin).
    4.1 If you see nothing, SonarQube likely has some permission issues with the newly created folders.
        4.1.1. "sudo chown -R $USER:$USER sonarqube"
5. Create a new project with the name "practical".
    5.1. "Create a local project"
    5.2. Under "Project display name", input "practical".
    5.3. Under "Main branch name", input "master".
    5.4. "Use the global setting"
    5.5. "Create project"
    5.6. "Locally"
    5.7. "Generate"
6. Save this token. We will use this in the Jenkins server later.
7. Visit http://localhost:8080 and do your account setup. Install recommended plugins.
8. We will now create a new Jenkins pipeline.
    8.1. "New item"
    8.2. Under "Enter an item name", input "practical" (I don't think this name matters).
    8.3. "OK"
    8.4. "Pipeline"
    8.5. Under "Definition", select "Pipeline script from SCM"
    8.6. Under "SCM", select "Git"
    8.7. Under "Repository URL", input "http://192.168.69.101:3000/practical.git"
    8.8. "Save"
9. Install 3 plugins: SonarQube Scanner, Warnings NG, OWASP Dependency Check
10. Configure 2 secret texts: the SonarQube access key in Step 6 and the NVD API key with the ID of "nvd_api_key". 
    10.1. Reminder that the NVD API key has to be with the ID of "nvd_api_key".
11. Follow lab instructions to configure the SonarQube Scanner and OWASP Dependency Check plugins.
    11.1. For OWASP Dependency Check, name it "owasp 11.1.0".
12. If all went well, you should be able to build.