# API/Service list

* [User](api-user/readme.md)
* Movie (unimplemented)


# Tester

See its [readme](client/readme.md)


# Quickstart

It's possible to bootstrap the whole ecosystem with Docker

```
docker-compose up --build
```

It will run User API in it's own container and also redis and postgres containers as User's dependencies.
User API will first run tests after bootstrap - You should see Codeception's output

Also it will start a tester/client as a console command in a separate container
You'll see it's REST communication demonstration in docker's output.

You can obviously inspect [docker-compose config](docker-compose.yml) for more architecture details