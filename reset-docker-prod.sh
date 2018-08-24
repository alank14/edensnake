cd /var/www/edensnake
sudo git pull
cd /var/www/edensnake/temptation
docker rm -f `docker ps -qa`
docker build -t edensnake .
docker run -p 8080:8000 -e SERVER_URI=edensnake.com:8080 --restart=always -d edensnake
