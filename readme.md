# тестовое задание для RuNetSoft

При выполнении задания использовались мои достаточно древние наработки, которые сочинялись еще для php 4, и отностельно недавно были адаптированы для 7-ки. Они представляют собой минифреймворк, который позволяет значительно сократить собственный код. 

Процесс адаптации еще не доведен до конца, в частности, через composer пока не распространяется, однако работоспособное приложение на нем удается получить. 

Использовался загрузчик файлов на сервер, собственного изготовления.

## Файлы, непосредственно относящиеся к тестовому заданию

- `src/project/ItemImport.php` - собственно класс для работы с файлом импорта.
- `src/project/itemLoad.php` , `src/project/itemStore.php` - интерфейсы, для использования в транспортном классе
- `src/project/itemDatabaseStore.php` - транспортный класс, доступ к базе данных для ItemImport.

- `test.txt` - тестовый файл с товарами (15 штук)

Класс ItemImport используется главным контроллереом приложения - src/Main.php.

## парсинг названия товара

Парсинг производится регулярным выражением, которое строится в конструкторе класса ItemImport и используется в методе importLine. 

## структура базы данных

База для тестового задания состоит из 2-х таблиц, создание которых описано в файле `runetsoft.sql`. При проектировании базы  задачи оптимизации поиска и экономии места не ставились, как преждевременые. Этим объясняется отсутствие ключей для поиска и однообразие типов полей

- varchar(10) - для текстовых коротких полей,
- int - для числовых полей
- varchar(80) - для достаточно длинных текстовых полей
- varchar(255) - для оригинального названия товара.

Предполагается, что после достаточно представительного заполнения таблиц товарами - структуру таблиц можно оптимизировать штатными средствами базы данных

## примерное описание работы приложения

Тестовое приложение размещено на http://runetsoft.500mb.net . Оно позволяет загрузить тестовые файлы и просмотреть результаты их работы. Так как 500mb.net является бесплатным хостингом - он вставляет свои счетчики на страницы сайта, это нужно иметь ввиду при просмотре исходников страниц на сайте.

Все исходники приложения размещены на https://github.com/Ksnk/runetsoft_test.

Исходным файлом для заполнения базы данных являются текстовые файлы со списком товаров, которые можно загружать на сервер с помощью приложения. После загрузки файл анализируется и удаляется с сервера. Возможна многократная загрузка одного и того же файла и файлов, содержащих одинаковые названия товаров, при этом дублирования товаров в базе не будет.

Можно использовать достаточно длинные файлы. При использовании загрузчика, ограничения на длинну получаемого файла не определяются настройками сервера, а определены параметром 
`hugefile: 2000000,` (размер в байтах) в файле `js/main.js`. Загрузка длинного файла производится порциями, с отображением прогресса загрузки. При ограничении на длину файла 3кк и средней длины названия 60 символов, получится около 50к товаров на файл. Загрузка длинного файла производится порциями, примерно по 50к

Загружаемый файл, предположительно, содержит наименование товара на каждой строке. Файл может содержать пустые строки и пробельные символы в начале-конце наименования.
Собственно выковыривание атрибутов из названия товара производится регулярным выражением. Если строка удовлетворяет регулярке, считается, что товар корректен, после чего, то что получилось записывается в базу. В противном случае - сообщение об ошибке собирается в специальном массиве с ошибками.

Приложение выводит содержимое обоих таблиц в окно броузера. В случае, если загруженный файл содержит некорректные имена товаров - информация о них будет выведена на экран, в соответствующем элементе.

Имеется кнопка очистки всех товаров и пара селектов для выбора страницы просмотра и количества выводимых данных.

## исходный текст ТЗ
```
Надо создать рабочее приложение, с небольшим интерфейсом, при нажатии на кнопку должен срабатывать скрипт.
В базе хранятся товары (автомобильные шины), написать скрипт, который разобьет шины по характеристикам.  Ниже представлена схема, как из названия получаются характеристики. 
Nokian Hakkapeliitta R2 SUV 205/70 R15 100R TT  Летние
Nokian Hakkapeliitta R2 SUV 225/60 R17 99R XL Run Flat TT Зимние (шипованные)
1. __ - бренд *
2. __ - модель *
3. __ - ширина *
4. __ - высота *
5. __ - конструкция *
6. __ - диаметр *
7. __ - индекс нагрузки *
8. __ - индекс скорости *
9. __ - характеризующие аббревиатуры
10. __ - ранфлэт
11. __ - камерность
12. сезон *
* - отмечены обязательные характеристики, если их нет, то товар помечается как некорректный, остальные могут быть или нет.
Пояснения
1 Бренд из списка: Nokian, BFGoodrich, Pirelli, Toyo, Continental,  Hankook, Mitas
2 Наименование до характериистик п.3
3 Характеристики 3-8 всегда в такой последовательности и формате, только значения цифр и букв разные.
4 Характеризующая может быть, а может не быть
5 Технология Run Flat, может обозначаться следующим образом RunFlat, Run Flat, ROF, ZP, SSR, ZPS, HRS, RFT
6 Камерность, возможные варианты:  ТТ, TL, TL/TT
7 Сезон, варианты: Зимние (шипованные), Внедорожные, Летние, Зимние (нешипованные), Всесезонные
Пример списка товаров:
Toyo H08 195/75R16C 107/105S TL Летние
Pirelli Winter SnowControl serie 3 175/70R14 84T TL Зимние (нешипованные)
BFGoodrich Mud-Terrain T/A KM2 235/85R16 120/116Q TL Внедорожные
Pirelli Scorpion Ice & Snow 265/45R21 104H TL Зимние (нешипованные)
Pirelli Winter SottoZero Serie II 245/45R19 102V XL Run Flat * TL Зимние (нешипованные)
Nokian Hakkapeliitta R2 SUV/Е 245/70R16 111R XL TL Зимние (нешипованные)
Pirelli Winter Carving Edge 225/50R17 98T XL TL Зимние (шипованные)
Continental ContiCrossContact LX Sport 255/55R18 105H FR MO TL Всесезонные
BFGoodrich g-Force Stud 205/60R16 96Q XL TL Зимние (шипованные)
BFGoodrich Winter Slalom KSI 225/60R17 99S TL Зимние (нешипованные)
Continental ContiSportContact 5 245/45R18 96W SSR FR TL Летние
Continental ContiWinterContact TS 830 P 205/60R16 92H SSR * TL Зимние (нешипованные)
Continental ContiWinterContact TS 830 P 225/45R18 95V XL SSR FR * TL Зимние (нешипованные)
Hankook Winter I*Cept Evo2 W320 255/35R19 96V XL TL/TT Зимние (нешипованные)
Mitas Sport Force+ 120/65R17 56W TL Летние
Хранение организовать в базе так первая табличка с товарами, вторая с характеристиками. Пробегаемся по товарам из первой таблички и заполняем характеристиками из второй, если разобрать не удалось, то помечаем такой товар. 
Рекомендуется применить знания в ООП (интерфейсы, абстрактные классы)  и сделать код наиболее пригодным для поддержки и доработки. Например, в будущем может поменяться формат названия, добавиться новые товары для разбора или запись происходить не в таблицу, а в файл. Это реализовывать не нужно, но предусмотреть необходимо.
```