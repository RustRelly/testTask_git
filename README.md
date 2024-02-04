Сервис реализует следующий функционал:

- Получение всех книг

Запрос:
```
GET /books
```

Ответ:
```
array - массив с данными о книгах. Один элемент массива:

id - unsigned int, идентификатор книги 
name - string, название книги
authors - array, авторы книги, структура массива:
[
  'id' - unsigned int, идентификатор автора
  'name' - string, имя автора
]
```
Пример:
```
[
  'id' => 1,
  'name' => 'Сказки',
  'authors' => [
                 [
                   'id' => 1,
                   'name' => 'Пушкин'
                 ]
               ]
]
```

- Создание книги

Запрос:
```
POST /books/create

[
  'name' - string, название книги
  'authors' - array, неповторяющиеся идентификаторы авторов
]
```

Пример параметров:
```
[
  'name' => 'Сказки',
  'authors' => [1, 2]
]
```

Ответ:
```
200, Книга успешно добавлена
```

Ошибки:
```
400, Ошибка в переданных параметрах
500, Ошибка сервера при попытке сохранения данных
[
  'error' - текст ошибки
]
```

- Выдать книгу

Запрос:
```
PATCH /books/check_out

[
  'name' - string, название книги
]
```

Пример параметров:
```
[
  'name' => 'Сказки'
]
```

Ответ:
```
200, Книга {name} с идентификатором {id} выдана
200, Доступной книги с таким названием не существует
```

Ошибки:
```
400, Ошибка в переданных параметрах
500, Ошибка сервера при попытке сохранения данных
```

- Списание книги

Запрос:
```
DELETE /books/{book}

{book} - идентификатор книги
```

Ответ:
```
200, Книга {book} успешно списана
200, Книга {book} не найдена
```

Ошибки:
```
500, Ошибка сервера при попытке сохранения данных
[
  'error' - текст ошибки
]
```

- Получение авторов

Запрос:
```
GET /authors
```

Ответ:
```
array - массив с данными об авторах. Один элемент массива:

id - unsigned int, идентификатор автора 
name - string, Имя автора
```
Пример:
```
[
  'id' => 1,
  'name' => 'Пушкин'
],
[
  'id' => 2,
  'name' => 'Гоголь'
]
```