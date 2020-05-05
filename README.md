# WeNet HUB

## Introduction

The WeNet HUB component is responsible for allowing WeNet users to manage their profile and application.
I also provide developers with the toots for managing applications and associated platform.


## Setup & configuration

## Usage

Environment variables required for this project

* SERVICE_API_BASE_URL
* SERVICE_API_APIKEY


## CI/CD

This project includes the support for Gitlab CI/CD pipelines.
In order to take advantage of the Gitlab CI/CD integration, the following CI/CD variables should be setup in the Gitlab repository configuration.

* DEPLOYMENT_SERVER_IP - the ip of the server hosting the deployment instances
* DEPLOYMENT_TEST_DIR - the directly with the docker configuration of the test instance
* GITLAB_SSH_KEY - the ssh key allowing the connection to the server
* REGISTRY_USERNAME - the username used for authorizing with the registry
* REGISTRY_PASSWORD - the password used for authorizing with the registry
