# Manual deploy

## Database

Fill database with fake users:

```
php bin/console doctrine:fixtures:load
```

# Testing

Fill database with aforementioned user fixtures first!

```
php vendor/bin/codecept run api
```

TODO: tests should have a separate database though for this specific project it's senseless
TODO: tests should fill database with fixtures themselves
TODO: tests should run all migrations up and down at least once


# Documentation

Updating swagger / openapi file:

```
php vendor/bin/openapi --output api-user.yaml src
```

TODO: describe failure responses