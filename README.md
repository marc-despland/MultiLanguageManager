[MultiLanguageManager description page](https://www.mediawiki.org/wiki/Extension:MultiLanguageManager)

#Test the extension with docker

* Run mysql server 
```
docker run -d --name mediawikidb --env-file docker/mediawiki/mediawiki.env mysql/mysql-server
```

* Build mediawiki container
```
docker build -t mediawiki ./docker/mediawiki/
```

* Run mediawiki
```
docker run -d --name mediawiki --env-file docker/mediawiki/mediawiki.env --link mediawikidb -v /home/mde/Projects/MultiLanguageManager:/var/www/html/mediawiki/MultiLanguageManager:Z mediawiki
```