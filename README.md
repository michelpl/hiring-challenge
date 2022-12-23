# Hiring Challenge

![Using](https://#.gif)

## Dependencies

*   ``Docker`` 19.03.5+
*   ``Docker Compose`` 1.23.1+

## Make sure the following ports are avaliable in your server

*   ``Port 8000`` For Backend API
*   ``Port 3306`` For database

## Building the environment

Clone this repository

```bash
https://github.com/michelpl/hiring-challenge-docker.git
```

Enter in repository folder

```bash
cd hiring-challenge
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

## The Hiring Challenge API will be avaliable on
```bash
http://localhost:8000/api/V1
```

## Development environment

```bash
$ chown -R $USER:www-data source
```

## Api doc
[API doc](https://#)

## Next steps