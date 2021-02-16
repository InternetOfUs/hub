# Changelog

## Version 1.*

### next

* Changed email configuration: now using built-in `swiftmailer`: this caused the email configuration parameters to change! The required email configuration environment variables are now:

    * `EMAIL_FROM`
    * `EMAIL_PASSWORD`
    * `EMAIL_HOST`
    * `EMAIL_PORT`

### 1.2.2

* fixed error for visualising badges
* now the badges are always visible


### 1.2.1

* added Spanish translations
* added html sanitisation of all string inputs
* added retrieve password link in oauth login page
* fixed the visualisation of the application description

### 1.2.0

* applied project template
* added email support
* added visualisation of badges for an app
* added logs support
* improved languages and nationalities lists for the user profile
* added Italian translations
* added Mongolian translations
* added smart management of the user language of the hub (redis)
* added possibility to set an app image (just an URL)

### 1.1.0

* improved developer section with new WeNet connector requirements: now OAtuh2 is the only required setting to configure for enabling an application to communicate with the platform
* added OAuth2 flow for allowing users to authenticate and authorise applications to access and use specific data
* allowing multiple developer management for an application
* added definition of application sources so that users know where to find/download/use an application

### 1.0.0

* user registration and authentication flow (via username/email and password)
* profile management, thanks to the integration with the service APIs
* developer mode activation (requires some profile details to be filled): required to be able to create and manage apps
* app discovery for browsing existing applications
* app platform enabling (currently Telegram is supported): triggering a dedicated event
