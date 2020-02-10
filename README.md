# Message Management Docker

## Dependencies
*   ``Docker`` 19.03.5+
*   ``Docker Compose`` 1.23.1+
*   ``Port 8000`` For Web API
*   ``Port 3306`` For database

## Building the environment

Clone this repository

```bash
https://github.com/michelpl/message-management-docker.git
```

Build using docker-compose
```bash
docker-compose build .
```

Running services
```bash
docker-compose up -d
```

Running Laravel migrations
```bash
docker-compose exec web php artisan migrate:fresh
```

Creating Passport credentials
```bash
docker-compose exec web php artisan passport:install
```

## The Message Management Web App will be  avaliable on
```bash
http://localhost:8080/
```

## The Message Management API will be  avaliable on
```bash
http://localhost:8000/api/V1
```

## Webapp repository
[Github Repository](https://github.com/michelpl/message-management-web)

## Api repository
[Github Repository](https://github.com/michelpl/message-management-api)
