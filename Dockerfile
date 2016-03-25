FROM php:latest

MAINTAINER Bert van Hoekelen

#Install unoconv
RUN apt-get update && apt-get install -y unoconv imagemagick && apt-get clean

# Install fonts
RUN echo "deb http://httpredir.debian.org/debian jessie non-free contrib" >> /etc/apt/sources.list
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y unar fonts-liberation msttcorefonts && apt-get clean
RUN wget http://download.microsoft.com/download/E/6/7/E675FFFC-2A6D-4AB0-B3EB-27C9F8C8F696/PowerPointViewer.exe && \
    unar PowerPointViewer.exe && \
    rm PowerPointViewer.exe && \
    unar PowerPointViewer/ppviewer.cab && \
    mkdir ~/.fonts/ && \
    cp ppviewer/*.TTF ppviewer/*.TTC ~/.fonts/ && \
    rm -Rf PowerPointViewer

RUN apt-get install libmagickwand-dev -y \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Copy app source
ADD [".", "/convert"]

# Change working directory
WORKDIR "/convert"

EXPOSE 3000

CMD ["php", "-S", "0.0.0.0:3000", "server.php"]