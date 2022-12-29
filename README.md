## Setup

1. Rename .env.example to .env and fill in database credentials
2. Execute commands:

````
composer install

php artisan key:generate
php artisan migrate
````

## Run

```
php artisan serve
````

## Usage

````
Categories:

GET /api/category/all:
    curl -X GET http://localhost:8000/api/category/all -H 'Content-Type: application/json' -d '{ "page": 1, "perPage": 10 }'

GET /api/category/1:
    curl -X GET http://localhost:8000/api/category/1

POST /category/lot:
    name - required

    curl -X POST http://localhost:8000/api/category -H 'Content-Type: application/json' -d '{ "name": "Category" }'

PUT /api/category/1:
    name - required

    curl -X PUT http://localhost:8000/api/category/1 -H 'Content-Type: application/json' -d '{ "name": "Category 1" }'

DELETE /api/category/1:
    curl -X DELETE http://localhost:8000/api/category/1


Lots:

GET /api/lot/all:
    category_ids - get lots in these categories

    curl -X GET http://localhost:8000/api/lot/all -H 'Content-Type: application/json' -d '{ "category_ids": [1, 2], "page": 1, "perPage": 10 }'

GET /api/lot/1:
    curl -X GET http://localhost:8000/api/lot/1

POST /api/lot:
    name, description, category_ids - required

    curl -X POST http://localhost:8000/api/lot -H 'Content-Type: application/json' -d '{ "name": "Lot", "description": "Description", "category_ids": [1, 2] }'

PUT /api/lot/1:
    curl -X PUT http://localhost:8000/api/lot/1 -H 'Content-Type: application/json' -d '{ "name": "Lot 1", "description": "Description 1", "category_ids": [1, 3] }'

DELETE /api/lot/1:
    curl -X DELETE http://localhost:8000/api/lot/1
````
