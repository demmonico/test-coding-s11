# S11 Test coding task

### Structure

```
codebase/               app codebase files
 
infra/                  infra related stuff
|-- common/             common infra stuff (will be used for all envs)
|   |-- nginx/          proxy's infra stuff
|   |   |-- conf/       proxy's config files
|   |       |-- ...
|   |   |-- Dockerfile  proxy's Dockerfile
|   |-- php/            PHP's config files
|-- local/              local infra stuff (used only for local env)
    |-- mysql/          db's infra stuff
        |-- conf/       DB conf files
        |-- data/       [CONTENT GIT IGNORED] contains DB files
        |-- init/       DB init SQL script and runner
        |-- .env        DB env vars
        |-- Dockerfile  DB's Dockerfile
    |-- .env.local.dist        db's infra stuff
 
task/                   contains task related files
|-- importer.sh         runner to import users data into DB (one-time action) 
|-- README.md           task issue 
|-- users.csv           users.csv data 
 
postman/                contains postman collection for useful manual testing enpoints
 
docker-compose.yml      project's compose file
 
Dockerfile              app's Dockerfile. Placed here, cause it should has access to the codebase folder
 
README.md
```

### Install

- Clone repo
```shell script
git clone ... & cd ...
```

- Ensure that you have Docker locally

- Copy `.env.local` file (and modify if need it)
```shell script
cp infra/local/.env.local.dist infra/local/.env.local
``` 

- Run/build docker containers
```shell script
docker-compose up --build
```

- Install composer deps
```shell script
docker exec -ti s11_app_php_1 composer install
```

- Init DB structure
```shell script
docker exec -ti s11_app_mysql_1 /init/runner.sh
```

- Import users data. Under the hood it calls [special API endpoint](#add-user)
```shell script
./task/runner.sh http://localhost:8080
```

As a result you will get Nginx + PHP + MySQL working containers having imported DB structure and users data.


### Usage

##### Add user

Add single user (`username` and `phone`):
```shell script
curl --location --request PUT 'localhost:8080/user/<USERNAME>' \
    --header 'Content-Type: application/x-www-form-urlencoded' \
    --data-urlencode 'phone=<PHONE +49 0000000>'
```

##### List users

```shell script
curl --location --request GET 'localhost:8080/user/'
```
will return all exists users in json: 
```json
{
    "users": [{
            "id":"1",
            "name":"situatedantiviral",
            "phone":"+4930958186884",
            "modified":null,
            "created": {
                "date":"2020-11-05 00:42:05.000000",
                "timezone_type":3,
                "timezone":"UTC"
            }
        },
        ...
    ]
}
```

##### Setup duty

```shell script
curl --location --request POST 'localhost:8080/duty' \
    --header 'Content-Type: application/x-www-form-urlencoded' \
    --data-urlencode 'usernames=<USERNAME_1>,<USERNAME_2>,...' \
    --data-urlencode 'started=2020-11-05' \
    --data-urlencode 'ended=2020-11-06' \
    --data-urlencode 'comment=this one is optional'
```

Successful call will return `200` HTTP and json list of created duties:
```json
{
    "duties": [
        {
            "id":"4",
            "started": {
                "date":"2020-11-05 00:00:00.000000",
                "timezone_type":3,
                "timezone":"UTC"
            },
            "ended": {
                "date":"2020-11-06 23:59:59.000000",
                "timezone_type":3,
                "timezone":"UTC"
            },
            "created":{
                "date":"2020-11-05 01:00:59.355129",
                "timezone_type":3,
                "timezone":"UTC"
            },
            "user":{
                "id":"8",
                "name":"compoundused",
                "phone":"+4930038443806",
                "modified":null,
                "created":{
                    "date":"2020-11-05 00:42:11.000000",
                    "timezone_type":3,
                    "timezone":"UTC"
                }
            },
            "user_contact":"+4930038443806",
            "comment":"test three"
        },
        ...
    ]
}
```

Validation fail will return `400` HTTP and json with error:
```json
{
    "error": "Validation error: Object(App\\Entity\\Duty).userContact:\n    User contact should be Germany valid +49dddddddd, \"+39069059389\" provided (code de1e3db3-5ed4-4941-aae4-59f3667cc3a3)\n"
}
```

Application error will return`500` HTTP and json with error as well.


###### Flow

- validating new duties input
- cleaning up current active duties (I've choose replacing strategy as most easy one)
- inserting `unset` to history
- inserting new one
- inserting `set` to history
- sending notification about duty setting up (use dummy stub)

###### Requirements

There are follow rules:
- minimum 2 users on call
- only users with valid mobile numbers can be set on call. Valid are:
    - non-empty
    - German number (pattern/format `\+49[0-9]{8,11}`)

There are allowed users to have empty or non-matched phone pattern at the `users` table.
It's not allowed to set up such users to the duty, but `users` table have them. 
It's done intentionally, cause:
- there are no pre-requirements about this
- usually, users are separately managed (but still managed, not injected by input!) in such types of systems - e.g. OpsGenie
- theoretically you can easily add one more notification channel (e.g. Slack) and allow system to notify user by any available channel. 
So it's more extendable.

### Run tests

I'm using `phpspec` to provide a unit-tests. I prefer them since it's more readable than `phpunit`. 
I didn't cover code fully, only the most important from the business logic places, because of lack of the time, 
but it could be extended in the future.

```shell script
docker exec -ti s11_app_php_1 vendor/bin/phpspec run
```

Output:

```shell script
$ docker exec -ti s11_app_php_1 vendor/bin/phpspec run
                                      100%                                       8
3 specs
8 examples (8 passed)
311ms
```
