# Rodar as migrations
echo "Rodando as migrations..."
php artisan migrate --force

# Gerar a chave do Laravel (caso não tenha sido gerada ainda)
echo "Gerando a chave do Laravel..."
php artisan key:generate --force

# Iniciar o PHP-FPM
echo "Iniciando o PHP-FPM..."
php-fpm -D

# Iniciar o Nginx
echo "Iniciando o Nginx..."
nginx -g "daemon off;"
