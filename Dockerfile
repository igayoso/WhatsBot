FROM php:7-cli
MAINTAINER Israel Gayoso <igayoso@gmail.com>

# Install packages
RUN apt-get update && apt-get install -y \
      libmcrypt-dev \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install sockets

# Create directory and add script
RUN mkdir /whatsbot
ADD run.sh /run.sh

# Run start command
CMD [ "/run.sh" ]
