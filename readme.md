# Git ChangeLog

A simple service to parse the git log of an application to a readable changelog.

### Requirements
##### Mandatory Requirements
- [PHP](https://php.net) >= 5.6.4
- [Carbon](http://carbon.nesbot.com/) >= 1.18: Used to format the date of the git commits

##### Optional Requirements
- An existing [Laravel 5.3](https://laravel.com/docs/master/installation) project to use the global view variable `$gitVersion`

### Installation

1. To get started, install the Git ChangeLog service via the Composer package manager: 

    ```bash
    composer require epicarrow/git-changelog
    ```
    
2. Optional: If you are using Laravel and you want to use the global view variable `$gitVersion` add the following entry to your providers array in `config/app.php`:
    
    ```php
    'providers' => [
       ...
       ...
       EpicArrow\GitChangeLog\Providers\GitChangeLogServiceProvider::class
    ]
    ```
### Documentation
The following services are currently available:
```php 
EpicArrow\GitChangeLog\GitChangeLog::get([int $count = 10])
``` 
Fetches the latest unique git commits.

**Parameters:**
- `$count` (_int_): The number of results to retrieve.

**Return Values:**

The retrieved commits as an `array` of `EpicArrow\GitChangeLog\Models\Commit`s.

___

```php 
EpicArrow\GitChangeLog\GitChangeLog::version()
``` 
Gets the latest version of the git repository.

**Return Values:**

The retrieved latest version of the git repository as a `string` or `null` if no version exists.