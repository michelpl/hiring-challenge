# Message Management Docker

![Using](https://i.imgur.com/ATippjY.gif)

## Dependencies

*   ``Docker`` 19.03.5+
*   ``Docker Compose`` 1.23.1+

## Make sure the following ports are avaliable in your server

*   ``Port 8080`` For Web App
*   ``Port 8000`` For Backend API
*   ``Port 3306`` For database

## Building the environment

Clone this repository

```bash
https://github.com/michelpl/message-management-docker.git
```

Enter in repository folder

```bash
cd message-management-docker
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

## Api doc
[API doc](https://documenter.getpostman.com/view/1954140/SWTK2suW)

## Webapp repository
[Github Repository](https://github.com/michelpl/message-management-web)

## Api repository
[Github Repository](https://github.com/michelpl/message-management-api)

## Next steps

- Split code in smallest components
- Improve or use another pagination component
- Add a burger menu for mobile
- Add unit tests
