# Hiring Challenge

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d?action=collection%2Ffork&collection-url=entityId%3D1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d%26entityType%3Dcollection%26workspaceId%3D884cf7ff-ca99-4231-944e-d47ac4babda5)

## Api doc
[Api documentation](https://documenter.getpostman.com/view/1954140/2s8Z6yYZHS)

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



## To-do list

## Improvements


[x] Create sequence diagram
[] Swagger
[] Postman docs
[] Unit tests
[x] Logs
[] Organize postman collection
[x] Fix main branch
[x] Run code lint
[] Makefile
[] Review readme


## Features

[x] External request->csv_data_api:send csv file
[x] csv_data_api->Database:save csv file

[x] Cron job->charge_api:Post request
[x] charge_api->Database:Get csv_data
[x] Database->charge_api:Return csv_data
[x] charge_api->Database:Create charges 
[x] charge_api->Database:Create charge payment data (boleto) for each charge

[!] Cron job->charge_api:Trigger e-mail sending
[x] Database<-charge_api:Get boleto list
[x] Database->charge_api:Return boleto list
[x] charge_api->Customers:Send e-mails containing boleto data to each customer

[x] External request->charge_api:Send a payment webhook
[x] charge_api->database:Save payment data (paid amount, paid by, and charge status)
[x] charge_api->database:Update charge status
