FROM php:5.6-cli
MAINTAINER Israel Gayoso <igayoso@gmail.com>

# Install packages
RUN apt-get update && apt-get install -y \
      libmcrypt-dev git \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install sockets

# Install protobuf
RUN cd /tmp \
    && git clone https://github.com/allegro/php-protobuf.git \
    && cd php-protobuf \
    && phpize \
    && ./configure && make && make install \
    && echo "extension=protobuf.so" > /usr/local/etc/php/conf.d/protobuf.ini

# Install curve25519
RUN cd /tmp \
    && git clone https://github.com/mgp25/curve25519-php.git \
    && cd curve25519-php \
    && phpize \
    && ./configure && make && make install \
    && cp /tmp/curve25519-php/modules/curve25519.so /usr/local/lib/php/extensions/no-debug-non-zts-20131226/ \ 
    && echo "extension=curve25519.so" > /usr/local/etc/php/conf.d/curve25519.ini

# Create directory and add script
RUN mkdir /whatsbot
ADD run.sh /run.sh

# Run start command
CMD [ "/run.sh" ]
