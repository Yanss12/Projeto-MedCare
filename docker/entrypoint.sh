#!/bin/bash
# =============================================================================
# Entrypoint - Script de inicialização do container Laravel
# =============================================================================
# Este script roda toda vez que o container "app" inicia.
# Ele garante que o Laravel esteja configurado corretamente:
#   1. Copia .env se não existir
#   2. Gera a APP_KEY
#   3. Cria o banco SQLite
#   4. Roda as migrations
#   5. Ajusta permissões
#   6. Inicia o PHP-FPM
# =============================================================================

set -e  # Para o script se qualquer comando falhar

echo "🚀 Iniciando setup do MedCare..."

# -----------------------------------------------------------
# 1. Instalar dependências PHP (caso o volume esteja vazio)
# -----------------------------------------------------------
if [ ! -f "vendor/autoload.php" ]; then
    echo "📦 Instalando dependências do Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "📦 Dependências PHP já instaladas."
fi

# -----------------------------------------------------------
# 2. Criar .env a partir do .env.example se não existir
# -----------------------------------------------------------
if [ ! -f ".env" ]; then
    echo "📝 Criando arquivo .env..."
    cp .env.example .env
fi

# -----------------------------------------------------------
# 3. Gerar APP_KEY se estiver vazia
# -----------------------------------------------------------
if grep -q "^APP_KEY=$" .env; then
    echo "🔑 Gerando APP_KEY..."
    php artisan key:generate --force
else
    echo "🔑 APP_KEY já configurada."
fi

# -----------------------------------------------------------
# 4. Criar banco SQLite se não existir
# -----------------------------------------------------------
if [ ! -f "database/database.sqlite" ]; then
    echo "🗄️  Criando banco de dados SQLite..."
    touch database/database.sqlite
fi

# -----------------------------------------------------------
# 5. Ajustar permissões
# -----------------------------------------------------------
echo "🔒 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache
chmod 660 database/database.sqlite  # Sem acesso para 'others' — contém PII/PHI

# -----------------------------------------------------------
# 6. Rodar migrations
# -----------------------------------------------------------
echo "🗂️  Rodando migrations..."
php artisan migrate --force

# -----------------------------------------------------------
# 7. Limpar e cachear configurações
# -----------------------------------------------------------
echo "⚡ Otimizando Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Setup concluído! Iniciando PHP-FPM..."

# -----------------------------------------------------------
# 8. Iniciar PHP-FPM (substitui este processo pelo PHP-FPM)
# -----------------------------------------------------------
exec php-fpm
