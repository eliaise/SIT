# Copy the job script
cp ./archival_task.sh ./
cp ./delete.sh ./

# Allow it to execute
chmod +x archival_task.sh
chmod +x delete.sh

# Set up a cronjob
crontab -e

# Copy the following
0 * * * * bash /home/<user>/archival_task.sh
1 * * * * bash /home/<user>/delete.sh

# the delete.sh is to clean the uploads folder in the event that archival task gets stuck due to the reverse shell