Вітаю всіх, хто це читає! 

Це робота по ТЗ від Panda Team. 
Безпосередньо ТЗ знаходиться в корені проекту і пидписане як ТЗ.txt

Проект розгорнутий на технології контейнеризації Docker. 
Для того, щоб мати можливість ознайомитися з ним в повній мірі, потрібно ознайомитися повністю з цим документом і слідувати інструкції по діяльності. 

Спочатку проект скачується на машину, де є Docker з підтримкою версії: version: '3.8'
Готуються контейнери шляхов введення в термінал кореня проекту команди: docker-compose build
Орієнтовний час збирання контейнера ≈ 8 хв

Наступний крок — підготовка самого проекта
Для цього вводиться команда: docker-compose up -d
Якщо це повторна спроба, спочатку треба вимкнути контейнери: docker-compose down
Орієнтовний час підготовки проекта ≈ 8-9 хв
Це пов'язано з використанням бібліотек і підготовки самого сайту (міграції, генерації ключа підтвердження, тощо)
Збиратися проект буде за адресою http://localhost
Для його роботи потрібно, щоб були вільні порти 80 і 9000
Допоки проект буде збиратися, за адресою буде видна помилка 502

Автоматизовано збираються контейнери php, nginx та db. 
В планах ще були автоматизовані контейнери черг та автоматизоване тестування, але це перше комерційне завдання на Docker, для освоєння технології на потрібному рівні не вистачило часу
Автоматизоване тестування в зародковому стані, черги готові, хоча потребують ручного запуска
Для активації черг, коли зникне 502 помилка з сайту, в терміналі корінної директорії потрібно ввести в порядку черги команди:
docker exec -it panda-app bash
php artisan queue:table
php artisan migrate
php artisan queue:work
УВАГА! Роботи будуть працювати, допоки відкритий термінал. Швидко, але вимагає ручного втручання
Схема реалізована за допомогою механізму налаштування черг на основі взаємодії з БД QUEUE_CONNECTION=database
Альтернативи — робота з redis та supervisor. Варіант, з яким є досвід — робота з supervisor
Але для нього є тільки досвід запуску на сервері з рут-правами, налаштувати через контейнери не встиг через відсутність релевантного досвіду
Такий варіант дозволяє працювати фоном, без потреби тримати відкритим термінал.
Найкраще рішення для великих проектів. 

Тепер, коли проект готовий до використання, можна приступити до реєстрації користувачів і оцінки функціоналу.
В якості відправної точки і для демонстрації навичок для CRUD генерації вибрав Craftable
Концептуально панель користувача являє собою місце, де можна створити заявки по оголошенням, які цікавлять
Їх можна створювати, редагувати, видаляти
Є розмежування в панелі по заявкам, тобто, користувач бачить тільки свої власні заявки
Але до них прив'язана гнучка система налаштування ідентифікаторів. 
Наприклад, якщо користувач створив посилання 1, а потім змінив його на посилання 2, яке створив користувач 2,
то дублюватися дані не будуть, а просто відбудеться переорієнтація зв'зків.
Завдяки такій схемі, потенційно, зменшується кількість записів в БД, її розмір і збільшується місткість проекта

Коли створюється підписка на стеження, задається завдання в чергу. Відстеження буде активовуватися кожен раз, коли буде створюватися нова підписка
І всім попереднім підписникам в цей момент буде приходити інформаційне повідомлення
Концептуально схема не сама найкраща, але, орієнтовно, 2/3 всього часу на виконання ТЗ пішло на налаштування контейнеризації, тож роль такого механізму продемонструвати вміння створювати черги, хоч вони і запускаються вручну
Конкретики по нагадуванню в ТЗ не було. Черги працюють потоком, тож, відправка листа буде відбуватися буквально за декілька секунд

Є один момент — повільне провантажування сторінок в контейнерах. Зазвичай працюю на OpenServer та з інструментом php artisan serve
Вони і швидші для мене, і менше вантажать комп'ютер
Docker в цьому плані дуже грузний, особливо, для слабкої техніки. З 16 Гб оперативної пам'яті обробка завантаження сторінок триває ± 30 секунд
Можливо, дається відсутність комерційного досвіду роботи з ним. Можливо, як пишуть в відгуках, інструмент сам по собі тяжкий
В цілому, з 21 року зосередився практично повністю як кодер, в налаштуванні середовищ приймав мінімальну участь

Хоча трохи забіг наперед. Порядок роботи з сайтом:
1) Зареєструватися за адресою: http://localhost/admin/register
Хоча в якості інструменту CRUD був використаний Craftable, штатно, в ньому не передбачений механізм реєстрації через форму
Тому, це самописний механізм. 
2) Відправляється лист з посиланням на підтвердження, а користувач перенаправляється на форму входу
3) Після того, як був введений вибраний логін і пароль, відбувається перенаправлення на вітальну сторінку
4) Зліва в меню доступний тільки один варіант — власне, самі підписки
5) Є сторінка з загальним списком користувача, де можна проводити фільтрацію по пошті, адресі, налаштовувати відображення кількості заявок на сторінці. Є кнопка створення нових заявок
6) При створенні заявок вимагається вказати тільки два поля — сторінка, яка цікавить, і пошта, на яку треба буде висилати інформацію
Спочатку думав в поле заповняти пошту користувача за умовчанням, але з іншої сторони, коли можеш підписати кого завгодно, чи свій окремий профіль — цн більш гнучко, хоч і не сек'юрно
Взагалі, думаючи про можливу бізнес-логіку, уявляв сервіс посередник, який дозволяє відстежувати цікаві пропозиції
І можна передбачити відправку в мессенджери, соціальні мережі, а не тільки не пошту
7) Після того, як користувач натиснув на кнопку "Зберегти", дані заносяться в БД
Передбачена мінімальна валідація, а також обробники помилок, на випадок, якщо користувач надасть неправильне посилання або воно втратить актуальність
8) Запускається механізм стеження, який відправляє повідомлення на пошту. Зараз просто відправляє ціну, актуальну в певний час

Під кінець, напевне, варто ще сказати про фабрику запитів, яка взаємодіє з Olx. Просто хвалюся

З ускладнень була реалізована часткова контейнеризація, хоча й тільки 3 з 5 запланованих
Також було реалізоване підтвердження пошти користувачів
В цілому, тести для мене не проблема, хоча з їх комерційним досвідом теж не склалося
Але я звик запускати програми в дещо інших середовищах і капітальна робота з Docker-ом затребувала багато часу, але, в цілому, результатом задоволений, оскільки складнощі були тільки з тим,
чим раніше займався DevOps. Хоча й для себе відкрив нові моменти, незважаючи на свій досвід

Дякую за увагу!