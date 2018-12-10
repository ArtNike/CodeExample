# CodeExample
#### Приведенный код стоит начинать смотреть с MailController.php
#####Функция startSending(Request) класса MailController принимает HTTP POST запрос формата:
    Method: POST
    Headers: "Content type": "application/json"
    body:
    {
    "mailing": {
                "mass":[
                    "0": {
                        "project": int,
                        "users": [101, 58 ...]
                    },
                    "1": ...
                
                ]
                }
    } 

#####После валидации в функции getUsersWithoutProjects() модели User данные подготавливаются для удобной записи в Redis
    Redis используется как сервер очередей
#####Дальше данные записываются в Redis с использованием команды hset
    hset используется для записи данных связанныю с рассылкой писем и для того чтобы 
    данные не смешивались (т.к Redis используется еще и для других целей). Иначе говоря:
    hset помогает абстрагироваться от других записей в Redis.

###Response
#####Если все прошло успешно:
    
    Code: 200
    body:
    {
    "status": true
    }
#####Если данные пустые:
    
    Code: 400
    body:
    {
    "status": false
    }
#####Если а сервере возникла ошибка:
    
    Code: 500
    body:
    {
    "status": false
    }
 
