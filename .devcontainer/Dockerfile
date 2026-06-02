FROM mcr.microsoft.com/devcontainers/php:1-8.3-bookworm

USER root

# 1. Eliminar repositorio roto de Yarn y dependencias del SO limpias
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip curl sudo \
    libpq-dev libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libssl-dev ca-certificates \
    && update-ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# 2. Configuración y compilación de extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" bcmath intl pdo pdo_mysql pdo_pgsql zip gd exif pcntl sockets

# 3. Instalación de Node 20, PNPM y remoción inmediata de archivos temporales
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update && apt-get install -y --no-install-recommends nodejs \
    && npm i -g pnpm@9 \
    && npm cache clean --force \
    && rm -rf /var/lib/apt/lists/*

# 4. Archivos de configuración de PHP asignados al entorno
COPY ./.devcontainer/php.ini /usr/local/etc/php/conf.d/zz-dev.ini
COPY ./.devcontainer/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

WORKDIR /workspaces
RUN chown -R vscode:vscode /workspaces

USER vscode