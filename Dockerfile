# =============================================================================
# Dockerfile - MedCare (Laravel 12 + Vue 3 + Inertia.js)
# =============================================================================
# Este Dockerfile cria uma imagem com PHP 8.3, extensões necessárias,
# Composer e Node.js 20 para rodar o projeto Laravel completo.
# =============================================================================

FROM php:8.3-fpm

# -------------------------------------------------------------------
# 1. Instalar dependências do sistema e extensões PHP necessárias
# -------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libsqlite3-dev \
    zip \
    unzip \
    sqlite3 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        xml \
        bcmath \
        zip \
        gd \
        intl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------------------------
# 2. Instalar Composer (gerenciador de dependências PHP)
# -------------------------------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------------------------------------------------
# 3. Instalar Node.js 20 (para Vite, TailwindCSS, Vue)
# -------------------------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -------------------------------------------------------------------
# 4. Definir diretório de trabalho
# -------------------------------------------------------------------
WORKDIR /var/www/html

# -------------------------------------------------------------------
# 5. Copiar arquivos de dependências primeiro (cache de camadas Docker)
# -------------------------------------------------------------------
COPY composer.json composer.lock ./

# -------------------------------------------------------------------
# 6. Instalar dependências PHP
# -------------------------------------------------------------------
RUN composer install --no-scripts --no-autoloader --prefer-dist

# -------------------------------------------------------------------
# 7. Copiar package.json e instalar dependências Node
# -------------------------------------------------------------------
COPY package.json package-lock.json ./
RUN npm install

# -------------------------------------------------------------------
# 8. Copiar o restante do projeto
# -------------------------------------------------------------------
COPY . .

# -------------------------------------------------------------------
# 9. Finalizar instalação do Composer (autoload, scripts)
# -------------------------------------------------------------------
RUN composer dump-autoload --optimize

# -------------------------------------------------------------------
# 10. Build dos assets (Vite + Vue + Tailwind)
# -------------------------------------------------------------------
RUN npm run build

# -------------------------------------------------------------------
# 11. Configurar permissões para o Laravel
# -------------------------------------------------------------------
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# -------------------------------------------------------------------
# 12. Criar banco SQLite se não existir
# -------------------------------------------------------------------
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown www-data:www-data database/database.sqlite

# -------------------------------------------------------------------
# 13. Copiar e configurar o script de inicialização
# -------------------------------------------------------------------
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# -------------------------------------------------------------------
# 14. Expor porta do PHP-FPM
# -------------------------------------------------------------------
EXPOSE 9000

# O entrypoint cuida do setup (migrations, permissões, etc.)
# antes de iniciar o PHP-FPM
ENTRYPOINT ["entrypoint.sh"]
