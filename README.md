### Clone the project code
```bash
git clone {{REPOSITORY}} .
``
#### Pull the Laradock code as submodule
```bash
git submodule add https://github.com/Laradock/laradock.git
git submodule update --init --recursive
```
#### Remove all the existing containers, images, volumes
```bash
sudo docker rm $(sudo docker stop $(sudo docker ps -a -q)) && sudo docker rmi $(sudo docker images -q) && sudo docker volume rm $(sudo docker volume ls -q)
# https://docs.docker.com/config/pruning/
docker system prune
```

####  Stop the services which use :80, :443, :3306 ports, because these ports will be required to run docker containers
```bash
sudo service mysql stop && sudo service apache2 stop && sudo service nginx stop
```

### copy db & config files
```bash
cp -av ./laradock.config/. ./laradock
cp weather.local/.env.example weather.local/.env
```

#### Setup local hosts
```bash
sudo echo "127.0.0.1 weather.local www.weather.local" | sudo tee -a /etc/hosts
```

#### Build & run the containers in the detach mode
```bash

docker-compose --env-file laradock/.env -f laradock/docker-compose-dev.yml up -d nginx workspace php-fpm mariadb

docker-compose --env-file laradock/.env -f laradock/docker-compose-dev.yml restart nginx
docker-compose --env-file laradock/.env -f laradock/docker-compose-dev.yml stop 

a2ensite weather.local.conf
service nginx reload
```

#### Login inside the 'workspace' container in order to install files
```bash
docker exec -it weather-workspace-1 bash
docker exec weather-workspace-1 bash -c "cd weather.local && composer install -n && php artisan migrate && php artisan db:seed && php artisan test"
docker exec weather-workspace-1 bash -c "cd weather.local && chmod 777 ./storage -R && chmod 777 ./bootstrap/cache -R"

```

#### How to stop containers
```bash
docker-compose -f laradock/docker-compose-dev.yml stop
```
## in order to set up cron job please add the command "* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1" to the "crontab -e"

#### swagger url - http://weather.local/api/documentation
