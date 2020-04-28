# WeNet HUB

## Gitlab CI/CD

This project includes the support for Gitlab CI pipelines.
In order to take advantage of the CI integration, the following CI/CD variables should be setup in the repository configuration.

* DEPLOYMENT_SERVER_IP - the ip of the server hosting the deployment instances
* DEPLOYMENT_TEST_DIR - the directly with the docker configuration of the test instance
* GITLAB_SSH_KEY - the ssh key allowing the connection to the server
* REGISTRY_USERNAME - the username used for authorizing with the registry
* REGISTRY_PASSWORD - the password used for authorizing with the registry
