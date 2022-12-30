# Hiring Challenge

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d?action=collection%2Ffork&collection-url=entityId%3D1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d%26entityType%3Dcollection%26workspaceId%3D884cf7ff-ca99-4231-944e-d47ac4babda5)

## Api doc
[Api documentation](https://documenter.getpostman.com/view/1954140/2s8Z6yYZHS)

## Sequence diagram

![image](https://user-images.githubusercontent.com/6605776/210117293-618adc93-f112-4d6f-bb22-dff6fa2f807d.png)


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

Run de follwing command

```bash
make run
```
Expected output:

![image](https://user-images.githubusercontent.com/6605776/210116184-9ca95dce-9989-46fa-ad81-94361dc99400.png)

## Building the environment (manually)

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
docker-compose exec webapi php artisan migrate
```

Running Scheduled Tasks Manually
```bash
docker-compose exec webapi php artisan schedule:run 
```

## The Hiring Challenge API will be avaliable on
```bash
http://localhost:8000/api/V1
```

## Consuming the API

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d?action=collection%2Ffork&collection-url=entityId%3D1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d%26entityType%3Dcollection%26workspaceId%3D884cf7ff-ca99-4231-944e-d47ac4babda5)

## Development environment

In the project folder, run the following commands

```bash
$ find src/ -type d -exec chmod 775 {} \;
$ find src/ -type f -exec chmod 664 {} \;
$ chown -R www-data:$USER src
```

## Running tests

```bash
make test
```

or

```bash
docker exec -it webapi php artisan test
```
