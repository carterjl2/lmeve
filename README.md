ABOUT:

This project was started at the request of Aideron Technologies CEO in 2013. This software is basically an advanced prototype.
Code beauty was not a priority, moreover this is not in objective PHP, just plain-old structural PHP.
I had plans to refactor entire project into CodeIgniter framework, but this plan is currently on hold.

More information: http://pozniak.pl/wp/?tag=lmeve

This app requires EVE Online corporation API keys to function. It doesn't use CREST, which came after I retired from EVE Online.

IMPORTANT:

There is no installer currently, so there is some setup work to get LMeve to run

1. Initial setup

* Go to ./config/ directory, copy config-dist.php to config.php and set it up according to your host
* After setting up new SALT value in config.php, generate admin password hash by using php ./bin/passwd.php
* copy the password hash to clipboard
* Download current Types icons from EVE Online Toolkit page: http://community.eveonline.com/community/fansites/toolkit/
* unpack all PNG icon files to ./wwwroot/ccp_img/

2. Database setup

* Import /data/schema.sql file before using LMeve. Remember to set the db config options in ./config/config.php
* Import latest static data dump (can be in other db schema for clarity, for example lmeve db in `lmeve` and static data in `sde_rubicon`)
You can download latest static data from Steve Ronuken's website: https://www.fuzzwork.co.uk/dump/
* If necessary, change table names to lowercase using script ./data/rename-lowercase.sql
* Access the database using phpmyadmin or other tool, go to `lmusers` table
* Edit record for user 'admin'
* Paste the password hash from clipboard in 'pass' field
then you can login to application using admin/admin
password should be be changed in 'Settings' later.
* Add API key to `cfgapikeys` table. keyID goes to `keyID`, vCode goes to `vCode`

3. API Poller setup

* Set up API poller in cron to run every 15 minutes

  */15 * * * * 	[path-to-php]/php [path-to-lmeve]/bin/poller.php
  
CREDITS and COPYRIGHTS

LMeve by Lukasz "Lukas Rox" Pozniak
LMframework v3 by 2005-2013 Lukasz Pozniak
rixxjavix.css skin by Rixx Javix
All Eve Related Materials are Property Of CCP Games