Minimalist Web Notepad Developers Mod
======================

This mod adds few features useful to developers.

 * multiple output modes: plain/base64/md5/mtime
 * multiple output mime:HTML/JS/CSS/JSON (can be embed as web source / stub source)
 * easy to post content from cli (with append mode)

Example: 
```
dmesg | curl -d @- http://mininopad.url/dmesg
dmesg | curl -d @- http://mininopad.url/dmesg?mode=append
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
    rewrite ^/([a-zA-Z0-9_-]+)$ /index.php?note=$1?;
}
```

If notepad is in a subdirectory:
```
location ~* ^/notes/([a-zA-Z0-9_-]+)$ {
    try_files $uri /notes/index.php?note=$1?;
}
```

### On Caddy

```
my.notepad.domain {
  root /srv/notepad
  gzip
  status 403 /_tmp
  rewrite / {
    regexp ^/([a-zA-Z0-9_-]+)$
    to     /index.php?note={1}&{query}
  }

  # optional
  rewrite /B {
    regexp ^/([a-zA-Z0-9_-]+)$
    to     /index.php?note={1}&mode=base64&{query}
  }
  fastcgi / 127.0.0.1:9001 php
}
```

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
