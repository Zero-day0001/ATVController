FROM php:7.4-fpm-buster

RUN rm -rf /var/www/html/*
WORKDIR /var/www/html/

# Install Node
RUN apt-get update && apt-get -y install curl gnupg wget
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get update && apt-get install -y nodejs

# Install PHP modules
RUN rm /etc/apt/preferences.d/no-debian-php \
    && apt -y install lsb-release apt-transport-https ca-certificates \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get -y install php7.4-fpm php7.4-common php7.4-mbstring php7.4-xmlrpc php7.4-soap php7.4-gd php7.4-xml php7.4-intl php7.4-mysql php7.4-cli php7.4-ldap php7.4-zip php7.4-curl php7.4-cgi

# Install ADB & MYSQL Client
RUN apt-get -y install android-tools-adb android-tools-fastboot \
                       default-mysql-client

# Install Node depdencies
COPY package.json /var/www/html/
COPY package-lock.json /var/www/html/
ADD https://raw.githubusercontent.com/Zero-day0001/node-php-fix/main/main.js /var/www/html/ATVController/node_modules/node-php/
RUN npm install

# Install ATVController
COPY . /var/www/html/
RUN chmod +x /var/www/html/public/scripts/*.sh

CMD node ATVController.js