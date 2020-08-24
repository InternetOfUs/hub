# WeNet HUB

## Introduction

The WeNet HUB component is responsible for allowing WeNet users to manage their profile and application.
It also provides developers with the toots for managing applications and associated platform.


## Setup & configuration

This project is based on a _Yii advanced template_.
In order to set it up it is necessary to:

1. install Composer

```bash
curl -sS https://getcomposer.org/installer | php
```

2. install required packages

```bash
php composer.phar install
```

3. initialise the project and select the preferred environment (development or production)

```bash
php init
```

4. run the database migrations

```bash
php yii migrate/up
```

You are all set and ready to go!

Custom environment configurations can be accessed in the `frontend/config/params-local.php` and `frontend/config/main-local.php`. These files are not tracked in git and their content can be freely changed accordingly to the local custom configurations.

Optionally, configurations can also be set by setting the value of the following environment variables:

* BASE_URL
* API_BASE_URL
* SERVICE_API_BASE_URL
* SERVICE_API_APIKEY (optional, should be specified only when communicating directly with the service api component and not when using the project forwarder Kong)
* REDIS_HOST
* REDIS_PORT (default to *6379*)
* REDIS_DB (default to *1* for development mode and to *0* for production mode)
* KONG_URL
* KONG_PROVISION_KEY


## Usage

The project can be run by taking advantage of the built-in php server.

```bash
php yii serve --port=8888 --docroot @frontend/web
```

This will make the project available at `http://localhost:8888/`.


## CI/CD

This project includes the support for Gitlab CI/CD pipelines.
In order to take advantage of the Gitlab CI/CD integration, the following CI/CD variables should be setup in the Gitlab repository configuration.

* DEPLOYMENT_SERVER_IP - the ip of the server hosting the deployment instances
* DEPLOYMENT_TEST_DIR - the directly with the docker configuration of the test instance
* GITLAB_SSH_KEY - the ssh key allowing the connection to the server
* REGISTRY_USERNAME - the username used for authorising with the registry
* REGISTRY_PASSWORD - the password used for authorising with the registry
