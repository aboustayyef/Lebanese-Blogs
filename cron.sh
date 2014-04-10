# Edit this file to introduce tasks to be run by cron.
#  
# Each task to run has to be defined through a single line
# indicating with different fields when the task will be run
# and what command to run for the task
# 
# To define the time you can provide concrete values for
# minute (m), hour (h), day of month (dom), month (mon),
# and day of week (dow) or use '*' in these fields (for 'any').# 
# Notice that tasks will be started based on the cron's system
# daemon's notion of time and timezones.
# 
# Output of the crontab jobs (including errors) is sent through
# email to the user the crontab file belongs to (unless redirected).
# 
# For example, you can run a backup of all your user accounts
# at 5 a.m every week with:
# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/
# 
# For more information see the manual pages of crontab(5) and cron(8)
# 
# m h  dom mon dow   command
0,10,20,30,40,50 * * * * php /var/www/websites/lebaneseblogs.com/feeds_fetcher.php > /home/stayyef/latest_feed_fetch.log 2>&1
5 6-22 * * * php /var/www/websites/lebaneseblogs.com/articles_fetcher.php > /home/stayyef/latest_article_fetch.log  2>&1  #At the Top of each hour, check for new articles
# 8,18,28,38,48 * * * * php /var/www/websites/lebaneseblogs.com/image_cacher.php > /home/stayyef/latest_image_cash.log 2>&1
5,15,25,35,45,55 * * * * php /var/www/websites/lebaneseblogs.com/check_top_posts.php  > /home/stayyef/latest_top_posts_log.log 2>&1  #Checks top posts and updates facebook page
# 0 * * * * /etc/webmin/bandwidth/rotate.pl
0 1 * * * bash /backups/mysql_backup.sh > /backups/log.txt  #MySQL Databases Daily Backup
* 2 * * * s3cmd sync -r /var/backups/mysql/sqldump/ s3://MyWebsites-backups/sqldump/ #Backs up sql databases created by BackupNinja to Amazon S3
58 23 * * * php /var/www/websites/lebaneseblogs.com/worker_recent_posts_of_all_bloggers.php > /home/stayyef/latest_freshness_update.log 2>&1
