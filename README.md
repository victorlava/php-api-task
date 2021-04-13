# Secure Information Storage REST API

### Quickstart
* Start docker containers ```make start```
* Stop docker containers ```make stop```
* Initialize project ```make init```
* Run test suite ```make tests```

### Project setup

* Add `secure-storage.localhost` to your `/etc/hosts`: `127.0.0.1 secure-storage.localhost`

* Run `make init` to initialize project

* Open in browser: http://secure-storage.localhost:8000/item Should get `Full authentication is required to access this resource.` error, because first you need to make `login` call (see `postman_collection.json` or `SecurityController` for more info).

### Run tests

make tests

### API credentials

* User: john
* Password: maxsecure

### Postman requests collection

You can import all available API calls to Postman using `postman_collection.json` file

# API Documentation

## User Resource
### Login
For login we are using cookie-based authentication. The session is kept both on server and client-side.
```
POST /login
```
#### Request Example:
```
{
    "username": "john",
    "password": "maxsecure"
}
```
#### Properties:

+ ``username`` **string** (maximum length: 255)
+ ``password`` **string** (maximum length: 255)


#### Response Example:

```
{
    "username": "john",
    "roles": [
        "ROLE_USER"
    ]
}
```
### Logout
```
POST /logout
```

#### Request Example:
```
{
    "username": "john",
    "password": "maxsecure"
}
```

#### Properties:

+ ``email`` **string** (maximum length: 255)
+ ``password`` **string** (maximum length: 255)

#### Status Codes:

| Code    | Description     | Response Body |
|---------|-----------------|---------------|
| ``200`` | login request was successful | username, roles |
| ``400`` | username or password not provided, broken request | error |
| ``401`` | Invalid credentials | error |

## Item Resource
### List Items
Lists a collection of items owned by the authenticated user. Must contain cookie in the request header. User is identified by the PHPSESSID in Set-Cookie header.
```
GET /item
```
Response Example:
```
[
    {
        "id": 2,
        "data": "new item secret",
        "created_at": {
            "date": "2021-04-12 11:33:02.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated_at": {
            "date": "2021-04-12 11:33:02.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        }
    }
]
```
#### Properties:

+ ``id`` **int** 
+ ``data`` **string** 
+ ``created_at`` **DateTime Object**
+ ``updated_at`` **DateTime Object**
+ ``date`` **string**
+ ``timezone_type`` **int**
+ ``timezone`` **string**      

### Create Item
Creates an item for authenticated user. Uses form-data.
```
POST /item?data=message
```
#### Properties:

+ ``data`` **string**

### Patch Item
Updates a secret message for authenticated user. Must specify the message's ```id```. Uses form-data.
```
PATCH /item?id=1&data=message
```
#### Properties:

+ ``id`` **int**
+ ``data`` **string**

### Delete Item
Delete a secret message for authenticated user. Must specify the message's ``id``.
```
DELETE /item/{id}
```
#### Properties:

+ ``id`` **int**

#### Status Codes:

| Code    | Description     | Response Body |
|---------|-----------------|---------------|
| ``200`` | request successful | item collection or empty array |

