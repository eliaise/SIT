#!/bin/bash
curr_dt=$(date '+%Y-%m-%d_%H00')
rm -- /var/www/internal.ductuscarry.sitict.net/uploads/* 2>/dev/null
echo "$curr_dt - Uploaded files have been deleted." >> /var/log/hourly_archival.log
