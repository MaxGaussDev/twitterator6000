# Twitterator 6000

Twitterator 6000 - experimental Symfony project

# Installation

Should be easy peasy. This is the 6 step program:
- Step 1 - clone the project 
- Step 2 - create empty MySQL database
- Step 3 - update composer (insert DB data)
- Step 4 - update database schema (project was built on Symfony 3.1)

```php bin/console doctrine:schema:update --force```

- Step 5 - go to apps.twitter.com and create application
- Step 6 - set up config file with consumer and application key from twitter app

```
#inside app/config/config.yml fill in the blanks:

tweeterator_parameters:
        oauth_access_token:
        oauth_access_token_secret:
        consumer_key:
        consumer_secret:
```
 
- Step 7 - go to http://host/web/ (or /web/app_dev.php/ for dev mode) there should be an error screen (DB has no data yet)

# How to use

Add new user, or view the profile of existing user, go to:
``` http://host/web/twitter_screen_name ``` 

This will add the user and the last 20 updates.

To search user posts go to ```http://host/web/twitter_screen_name``` and insert the search term, or select imported user, or both. 

# Troubleshooting

Make sure that cache and logs directories have permissions for writing files. Both are located in:```var/``` directory.
