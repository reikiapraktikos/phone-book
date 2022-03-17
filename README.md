# Phone book

## Set up
```console
make init
```

## Register
```console
POST 127.0.0.1:8000/api/register
{
    "email": "<email>",
    "password": "<password>"
}
```

## Login
```console
POST 127.0.0.1:8000/api/login
{
    "email": "<email>",
    "password": "<password>"
}
```

## Get records
```console
GET 127.0.0.1:8000/api/record
Authorization: Bearer <token>
```

## Create record
```console
POST 127.0.0.1:8000/api/record
Authorization: Bearer <token>
{
    "name": "<name>",
    "number": "<number>"
}
```

## Update record
```console
PUT 127.0.0.1:8000/api/record
Authorization: Bearer <token>
{
    "id": <id>,
    "name": "<name>",    (optional)
    "number": "<number>" (optional)
}
```

## Delete record
```console
DELETE 127.0.0.1:8000/api/record/<id>
Authorization: Bearer <token>
```

## Share record
```console
POST 127.0.0.1:8000/api/record/share
Authorization: Bearer <token>
{
    "id": <id>,
    "user_id": <user_id>
}
```

## Cancel record sharing
```console
POST 127.0.0.1:8000/api/record/cancel-sharing
Authorization: Bearer <token>
{
    "id": <id>,
    "user_id": <user_id>
}
```

## Improvements
- Input validation
- Refactor serialization (App\Controller\RecordController get)
- It is difficult to see which records are shared, so new fields should be added to the "Get records" response
- API limits, limit of records to be returned
