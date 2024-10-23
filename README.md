## Project stack
* Nginx latest
* Postgresql latest
* PHP 8.3
* Symfony 7.1.*
* Kafka latest
* Swagger (OpenAPI 3.1)

## Project deployment
1. make init
2. make up

You can add a domain to /etc/hosts for convenience

[//]: # (Наша зона ответственности - корзина, заказ, статус доставки этого заказа, пользователи)

[//]: # ()
[//]: # ()
[//]: # (Сущности:)

[//]: # (cart)

[//]: # ()
[//]: # (orders)

[//]: # (order_statuses)

[//]: # ()
[//]: # (deliveries)

[//]: # (delivery_statuses)

[//]: # ()
[//]: # (users)

[//]: # ()
[//]: # (roles &#40;admin, user&#41;)

[//]: # (role_user)

[//]: # ()
[//]: # (Данные тянутся из внешнего сервиса)

[//]: # ()
[//]: # (## Роли и права)

[//]: # (Неавторизованный пользователь может только лишь просматривать список товаров)

[//]: # (Авторизованный пользователь может заполнять корзину и делать заказ)

[//]: # (Администратор - имеет все возможные доступы)

[//]: # ()
[//]: # ()
[//]: # ()
[//]: # (## ENDPOINTS)

[//]: # ()
[//]: # (POST /api/users)

[//]: # ()
[//]: # (POST /api/orders)

[//]: # (GET /api/orders)

[//]: # (GET /api/reports/)

