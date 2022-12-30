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

## The Hiring Challenge API will be avaliable on
```bash
http://localhost:8000/api/V1
```

## Consuming the API

- Get postman collection

## Development environment

In the project folder, run the following commands

```bash
$ find src/ -type d -exec chmod 775 {} \;
$ find src/ -type f -exec chmod 664 {} \;
$ chown -R www-data:$USER src
```

## Api doc
[API doc](https://#)

## To-do list

## Improvements

[] Change hash to UUID
[] Create repositories
[x] Create sequence diagram
[] Swagger
[] Postman docs
[] Unit tests
[x] Logs
[] Create charges using a queue sytem
[] Check types
[] Organize postman collection
[] Fix main branch
[] Run code lint
[] Makefile
[] Review readme

## Features

[x] External request->csv_data_api:send csv file
[x] csv_data_api->Database:save csv file

[!] Cron job->charge_api:Post request
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
