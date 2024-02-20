FROM php:8.1-fpm

ARG USER
ARG USER_ID
ARG GROUP_ID

# Set DNS and Proxy for the entire container
RUN echo 'nameserver 8.8.8.8' > /etc/resolv.conf && \
    echo 'nameserver 8.8.4.4' >> /etc/resolv.conf && \
    echo 'Acquire::http::Proxy "http://172.16.2.254:3128";' > /etc/apt/apt.conf.d/proxy && \
    echo 'Acquire::https::Proxy "http://172.16.2.254:3128";' >> /etc/apt/apt.conf.d/proxy && \
    echo 'http_proxy="http://172.16.2.254:3128"' >> /etc/environment && \
    echo 'https_proxy="http://172.16.2.254:3128"' >> /etc/environment && \
    echo 'export http_proxy="http://172.16.2.254:3128"' >> /etc/bash.bashrc && \
    echo 'export https_proxy="http://172.16.2.254:3128"' >> /etc/bash.bashrc

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    vim \
    libicu-dev

# Set environment variables for curl
RUN echo 'export http_proxy="http://172.16.2.254:3128"' >> /etc/bash.bashrc && \
    echo 'export https_proxy="http://172.16.2.254:3128"' >> /etc/bash.bashrc

# Use curl with proxy settings
RUN curl -sL --proxy http://172.16.2.254:3128 https://deb.nodesource.com/setup_18.x | bash \
    && apt-get install -y nodejs

RUN curl -sS --proxy http://172.16.2.254:3128 https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql intl

# Set proxy for pecl
RUN echo 'http_proxy = "http://172.16.2.254:3128"' >> /etc/pear.conf \
    && echo 'https_proxy = "http://172.16.2.254:3128"' >> /etc/pear.conf

RUN pear config-set http_proxy http://172.16.2.254:3128 

# Check if the group exists before creating it
# Check if the group exists before creating it
RUN getent group ${GROUP_ID} || groupadd -g ${GROUP_ID} appgroup
# Add the user to the existing group
# Check if the user exists before creating it
# RUN id -u $USER > /dev/null 2>&1 || useradd -ms /bin/bash -g ${GROUP_ID} -u 1337 $USER



# Check if the user exists before creating it
RUN id -u $USER > /dev/null 2>&1 || useradd -ms /bin/bash -g ${GROUP_ID} -u ${USER_ID} $USER

# Modify the UID of the user (if needed)
# RUN usermod -u ${USER_ID} $USER

# Switch to the user
USER $USER