# Contribute

## Language support

### Adding support for a new language

In order to add support for a new language, the following steps should be completed.

1. Browse in the [frontend/messages](frontend/messages) folder.
2. Duplicate the `en-US` folder and name it with the code of the new language that should be added.
3. Edit all the .php files by replacing the values on the right side of the arrows. Following are some hints to be aware of:
    * Files are created with pairs of `key => value` (eg. `'first_name' => 'First name'`),
    * In case of complex text, just have a look at the English translation (eg. `'explanation' => 'Do you want to create your own app? Become a developer and start building your own! Remember, to become developer you should set your first name, last name and birthdate in your profile.',`).
    * Be aware in contents could be inserted some HTML tags, please keep them in the same place in the sentence.
4. Modify the [frontend/messages/config.php file](frontend/messages/config.php) by adding the code of the new language in the `languages` array as a new string.
5. Browse to the file [frontend/controllers/BaseController.php](frontend/controllers/BaseController.php) and modify the `setLanguage` function, adding a new condition in the `else if block` with the root of the new language. For example in case of German language the code to be added should be:

```php
} else if(strpos($userLang, 'de', 0) !== false){
        Yii::$app->language = 'de-DE';
    }
```

6. Create a new pull request with your proposed changes. Please, make sure to detail your changes in the description.


### Edit translations for an already supported language

In order to propose changes to the translations of an existing language, the following steps should be completed.

1. Browse in the [frontend/messages](frontend/messages) folder and into the folder of the language you would like to propose changes for.
2. Open the desired .php file (we suggest using a simple text editor).
3. Identify the text you would like to change the translation for and apply your changes (being aware of changing values and not keys!).
4. Create a new pull request with your proposed changes. Please, make sure to detail your changes in the description.
