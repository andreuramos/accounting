## Requisites

* Docker Compose

## Run

```
$ docker compose up
```

If it's your first run, first install dependencies and migrate database
```
$ make init-be
$ make migrate
```

Goto http://localhost:8080 to see the frontend

The API endpoint is in http://localhost:8080/api/ 
