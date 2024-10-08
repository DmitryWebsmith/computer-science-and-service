## Тестовое задание для кандидата в стажеры Junior веб-программист (PHP / Laravel / Vue)

На выполнение дается 2 рабочих дня.

Задание: Сделать форму подачи заявки с созданием лида (под лидами понимают пользователей, которые проявили интерес к товару или услуге. В нашем случае, заполнили форму) на Laravel:

- регистрация: подтверждение регистрации аккаунта должно приходить на почту, в качестве почтового сервера можно использовать любой почтовый сервис предоставляющий бесплатную услугу smtp

- авторизация: пользователь может авторизоваться и  выйти из своей учетной записи

- страница профиля: должны быть включены все необходимые функции Laravel (вход, регистрация, смена пароля, подтверждение почты, выход) для реализации проекта, все остальные функции должны быть отключены

На главной странице проекта, должна быть форма обращения клиента и возможность регистрации/авторизации

Форма обращения клиента (видят неавторизованные пользователи) должна включать следующие поля: имя, фамилия, номер телефона, e-mail, текст обращения, кнопка “отправить”.

После логина, авторизованный пользователь видит страницу со списком лидов, информацию о общем количестве лидов, а также информацию о количестве лидов в каждом из статусов.

В списке отображается  id лида, Имя, Фамилия, e-mail, номер телефона, дата создания лида,  выпадающий список выбора статуса для каждого лида (статус должен применятся при выборе) по умолчанию у всех лидов статус “Новый”, кнопка удаления лида.

Статусы лидов: новый, в работе, завершен

Для статусов необходимо сделать отдельную модель которую необходимо связать с лидами

Должна быть возможность редактирования данных лида (отображаются поля, с заполненными данными по лиду, если были внесены изменения кнопка “Сохранить” становится активной)

По итогу выполнения данного задания, ожидаем от кандидата ссылку на репозиторий проекта. Инструкция по развертыванию проекта должна быть в README.md.

Задания без инструкции, оцениваться не будут.

При оценке тестового задания также будут учитываться, внешний вид и умение писать документацию. 

## Инструкция по локальному развертыванию приложения

1. Загрузите файлы
2. Установите необходимые зависимости командами
```angular2html
npm install
composer install
```
3. Измените имя файла .env.dev на .env и внесите необходимые изменения (настройка базы данных и smtp сервера)
4. Выполните миграцию
```angular2html
php artisan migrate
```
5. Выполните команды
```angular2html
php artisan serv
npm run build
```
