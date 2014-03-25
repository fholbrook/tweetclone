tweetclone
==========
This is a very basic twitter clone built to run on php, mysql, and apache.

Installation
=============
1. Clone the repo to your /var/www
2. Edit your vhost config to Allow all overides
3. Edit your vhost config, setting /var/www/web/ the root.

KNOWN ISSUE
===========
Known issue on line 43 of controllers.php

Currently the integer 1 is bound to the second value in the sql query. Instead of 1 the value should be an integer represeting the currently logged-in user's id. The id should be obtained from the security session.

Demo
======
http://flintholbrook.com

test@test.com
test123
