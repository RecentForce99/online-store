## Развёртывание проекта


1. sudo docker run --rm -v $(pwd):/app composer install --ignore-platform-req=ext-pcntl


Наша зона ответственности - корзина, заказ, статус доставки этого заказа, пользователи


Сущности:
cart

orders
order_statuses

deliveries
delivery_statuses

users

roles (admin, user)
role_user

Данные тянутся из внешнего сервиса

## Роли и права
Неавторизованный пользователь может только лишь просматривать список товаров
Авторизованный пользователь может заполнять корзину и делать заказ
Администратор - имеет все возможные доступы



## ENDPOINTS

POST /api/users

POST /api/orders
GET /api/orders
GET /api/reports/

Stack:
Orchid 16
Postgresql 16
PHP 8.3
Kafka
Swagger (OpenAPI 3.1)
