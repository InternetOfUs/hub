# WeNet HUB

## Introduction

The WeNet HUB component is responsible for allowing WeNet users to manage their profile and application.
It also provides developers with the toots for managing applications and associated platform.

In order to use a WeNet application a Wenet user should have been created.
This is possibile via the WeNet HUB. Any WeNet application will redirect its users to the HUB if a WeNet user can not be identified.

Once registered and authenticated, a user can take advantage of the HUB for:

* managing his profile information (this include general demographic details);
* browsing available WeNet applications;
* enabling the WeNet applications of interest in order to start using them;
* managing enabled applications.

The WeNet HUB is also the place where new Wenet applications can be created and configured.
Any user can create his own WeNet application by becoming a *developer*.
This can be done by enabling the *developer mode* in the user account settings.
A few profile information will need to be set in order to complete this process.

Once developer, a user has access to a dedicated section of the HUB where it is possibile to configure applications from a technical point of view.
A WeNet application requires all the configuration to be completed in order to be publicly discoverable by other users.
In particular, some technical details need to be defined:

* at least one platform through which the application is going to be available (currently the Telegram platform is supported);
* the *callback url* where the messages generated from the WeNet platform are going to be forwarded so that the application can present them to its users.

In the application configuration section, the developer has also the possibility of knowing how many users have enabled the application and have been trying it out.


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

* `BASE_URL`
* `API_BASE_URL`
* `COMP_AUTH_KEY` - Apikey for cross-component authentication
* `PROFILE_CACHE_TIME_SECS` cache time for the user profile information (default to 60 seconds)
* `SERVICE_API_BASE_URL`
* `INCENTIVE_SERVER_BASE_URL`
* `REDIS_HOST`
* `REDIS_PORT` - Optional, default to *6379*
* `REDIS_DB` - Optional, default to *1* for development mode and to *0* for production mode
* `KONG_URL`
* `KONG_PROVISION_KEY`
* `EMAIL_FROM`
* `EMAIL_PASSWORD`
* `EMAIL_HOST`
* `EMAIL_PORT`
* `SENTRY_DSN` - The DSN of the Sentry project to connect. The Sentry integration is optional.
* `SENTRY_RELEASE` - The _release_ value that should be associated to the created Sentry events (this is optional also when Sentry integration is enabled).
* `SENTRY_ENVIRONMENT` - The _environment_ value that should be associated to the created Sentry events (this is optional also when Sentry integration is enabled).


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
