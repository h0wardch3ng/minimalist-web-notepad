Minimalist Web Notepad Developers Mod
======================

This mod adds few features useful to developers.

 * multiple output modes: plain/base64/md5/mtime
 * multiple output mime: html/js/css/json (embed as web source)
 * post content from cli (with append mode)

Example: 
```
# upload data from cli
dmesg | curl --data-binary @- http://mininopad.url/dmesg

# append data from cli
dmesg | curl --data-binary @- http://mininopad.url/dmesg/append

# get plain text (curl user-agent)
curl http://mininopad.url/dmesg

# base64 encoded text
curl http://mininopad.url/dmesg/base64

# md5 hash of the text
curl http://mininopad.url/dmesg/md5

# view as html
curl http://mininopad.url/dmesg/html
``` 

Installation
------------

At the top of `index.php` file, change `$base_url` variable to point to your
site.

Make sure the web server is allowed to write to the `_tmp` directory.

### On Apache

You may need to enable mod_rewrite and set up `.htaccess` files in your site configuration.
See [How To Set Up mod_rewrite for Apache](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04).

### On Nginx

To enable URL rewriting, put something like this in your configuration file:

If notepad is in the root directory:
```
location / {
    rewrite ^/([a-zA-Z0-9_-]+)/?([a-z0-9]*)?$ /index.php?note=$1&mode=$2?;
}
```

If notepad is in a subdirectory:
```
location ~* ^/notes/([a-zA-Z0-9_-]+)/?([a-z0-9]*)?$ {
    try_files $uri /notes/index.php?note=$1&mode=$2?;
}
```

### On Caddy

```
my.notepad.domain {
  root /srv/notepad
  gzip
  status 403 /_tmp
  rewrite / {
    regexp ^/([a-zA-Z0-9_-]+)/?([a-z0-9]*)?$
    to     /index.php?note={1}&mode={2}&{query}
  }

  fastcgi / 127.0.0.1:9001 php
}
```

Screenshots
-----------

![Chrome](https://orga.cat/sites/default/files/images/chrome.png)

![Edge](https://orga.cat/sites/default/files/images/edge.png)

![Chrome Android](https://orga.cat/sites/default/files/images/android_chrome_dark.png)

![Firefox Android](https://orga.cat/sites/default/files/images/android_firefox.png)


Copyright and license
---------------------

Copyright 2012 Pere Orga <pere@orga.cat>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License at:

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
