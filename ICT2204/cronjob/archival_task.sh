#!/bin/bash
# wget https://api.kanye.rest/text > /var/www/internal.ductuscarry.sitict.net/quotes_of_the_hour.txt
curr_dt=$(date '+%Y-%m-%d_%H00')
cd /var/www/internal.ductuscarry.sitict.net/uploads
tar -zcvf "/uploads/$curr_dt.tar.gz" --remove-files * 2>/dev/null
echo "$curr_dt - Uploaded files have been archived." > /var/log/hourly_archival.log