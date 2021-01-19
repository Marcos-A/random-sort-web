# random-sort-web
Upload a CSV file without header and it will return a text file with the content from the first column randomly sorted and numbered.

## Installation
Clone the repository into your web root folder.

Make sure `uploads/` and `script/tmp/` directories are web writable.

## Requirements
```
sudo apt install apache2
sudo apt install php7.4-cli
sudo apt install php libapache2-mod-php
sudo apt-get install python3
sudo apt install python3-pip
pip3 install pandas
```

## Apache configuration: VirtualHost
```
<VirtualHost *:80>
	ServerAdmin admin@random-sort.local
	ServerName www.random-sort.local
	ServerAlias random-sort.local
	DocumentRoot /var/www/html/random-sort.local/public
	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## How to run
Start the server with:
`sudo systemctl start apache2`
Visit localhost:80 from your browser.
