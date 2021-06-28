# GitSchool:Training #
GitSchool:Training - это платформа управления обучением, в которой присутствуют практически все функции полноценной LMS-системы. Тут есть два вида аккаунтов: администратор и рядовой пользователь.

Проект выполнен с использованием фреймворка Symfony 5. База данных - MySQL. Для взаимодействия с БД использована Doctrine orm. Для реализации графического интерфейса был использован шаблонизатор Twig, также СSS + Bootstrap и JavaScript.
---
### Реализованный функционал: ###

1. Возможность регистрации пользователей в системе, входа/выхода в систему. Присутствует хеширование паролей c помощью bcrypt. Подтвержение регистрации по email.
2. Возможность управления пользователями (их блокировка, удаление, повышение) посредством таблицы пользователей при наличии аккаунта администратора. 
3. Возможность управления обучающими курсами и уроками (их создание, редактирование, удаление) при наличии аккаунта администратора.
4. Возможность оформления подписки на курс, подтверждение подписки по email (при наличии аккаунта рядового пользователя).
5. Возможность фильтрации и поиска интересующих курсов по названию или по тематике. Поиск и фильтрацию могут осуществлять неавторизированные пользователи.
6. Возможность загрузки видео-файлов на сервер через пользовательский интерфейс (при создании уроков).
7. Возможность оставить заявку для обратной связи на главной странице (могут неавторизированные пользователи).
8. Возможность просматривать личный кабинет пользователя, редактировать информацию о себе. В личном кабинете пользоватею отображаются курсы, на которые он оформил подписку. Тут же он может просматривать уроки и обучаться :)
***
Видеодемонстрация проекта есть по ссылке:
https://vk.com/is_i_re?z=video-132605326_456239019%2Fvideos166038533
