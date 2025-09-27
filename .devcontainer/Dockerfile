FROM mcr.microsoft.com/devcontainers/php:1-8.3-bullseye

USER root

# SO deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip curl libpq-dev libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libssl-dev ca-certificates \
 && update-ca-certificates \
 && rm -rf /var/lib/apt/lists/*

# --- INICIO DEL CAMBIO CLAVE: Configuración de Docker-in-Docker (Dind) ---

# 1. Instala el demonio de Docker (docker.io) y sudo (necesario para algunas instalaciones)
RUN apt-get update && apt-get install -y \
    docker.io \
    sudo \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# 2. Instala el binario clásico de Docker Compose (V1) para compatibilidad
# Usamos el binario con guion para evitar el error "docker: 'compose' is not a docker command."
RUN curl -SL https://github.com/docker/compose/releases/download/v2.24.5/docker-compose-linux-x86_64 -o /usr/local/bin/docker-compose \
    && chmod +x /usr/local/bin/docker-compose \
    && ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose

# 3. Agrega el usuario 'vscode' al grupo 'docker'.
# Esto es CRUCIAL para que el usuario pueda interactuar con el socket /var/run/docker.sock montado.
RUN usermod -aG docker vscode

# --- FIN DEL CAMBIO CLAVE ---

# PHP ext
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" bcmath intl pdo pdo_mysql pdo_pgsql zip gd exif pcntl sockets

# Node 20 + pnpm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get update && apt-get install -y nodejs \
 && npm i -g pnpm@9

# Config PHP
COPY .devcontainer/php.ini /usr/local/etc/php/conf.d/zz-dev.ini
COPY .devcontainer/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

# Unifica ruta de trabajo con devcontainer.json
WORKDIR /workspaces

# Permisos
RUN chown -R vscode:vscode /workspaces

USER vscode
