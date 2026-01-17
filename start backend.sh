# Установка проекта (если нет)
composer create-project symfony/skeleton backend-api
cd backend-api

# Установка зависимостей
composer require symfony/orm-pack
composer require symfony/serializer-pack
composer require nelmio/cors-bundle
composer require symfony/maker-bundle --dev

# Создание базы данных
php bin/console doctrine:database:create

# Создание сущности Task (если нет)
php bin/console make:entity Task

# Генерация миграции
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Запуск сервера
symfony server:start