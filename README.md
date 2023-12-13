# Worksection SDK

This package provides Worksection SDK. Support admin token auth and OAuth2 Flow.

## Installation

```
composer require worksection/worksection-sdk
```


## Usage
To get access to Worksection API you can use admin token API ([how to get token](https://worksection.com/en/faq/api-start.html))
```php
use Worksection\SDK\EntityBuilder;
use Worksection\SDK\Exception\SdkException;

$sdk = EntityBuilder::getInstance('https://myaccount.worksection.com/');
$sdk->setAdminToken('0da9fa4321ghm887530cfb8w3m57d3f4');

try {
    $projectsEntity = $sdk->createProjectsEntity(); // Entity for using projects api methods
    $tasksEntity = $sdk->createTasksEntity();       // Entity for using tasks api methods
    $membersEntity = $sdk->createMembersEntity();   // Entity for using members api methods
    $commentsEntity = $sdk->createCommentsEntity()  // Entity for using comments api methods
    $tagsEntity = $sdk->createTagsEntity();         // Entity for using tags api methods
    $costsEntity = $sdk->createCostsEntity();       // Entity for using costs api methods
    $filesEntity = $sdk->createFilesEntity();       // Entity for using files api methods
    
    // Example of usage (returns data on all projects)
    $result = $projectsEntity->get_projects();
    
    // Example of usage (creates a project)
    $result = $projectsEntity->post_project('New Test Project', [
        'email_user_from' => 'myemail@gmail.com',
        'email_manager'   => 'myemail@gmail.com',
        'text'            => 'Description of project',
        'datestart'       => '29.12.2023',
        'dateend'         => '29.12.2024'
    ]);
    
    // Example of usage (closes the specified task (100500 id task in 100 id project)
    $result = $tasksEntity->complete_task(100, 100500);

} catch (SdkException $e) {
    // work with errors
}
```
To get access to Worksection API you can use Oauth2 flow and access token ([how it works](https://worksection.com/en/faq/oauth.html))
```php
use Worksection\SDK\EntityBuilder;
use Worksection\SDK\Exception\SdkException;

$sdk = EntityBuilder::getInstance('https://myaccount.worksection.com/');
$sdk->setAccessToken('eyJ0eXAiOiJKV...3v3tKTcdp8zg');

try {
    $projectsEntity = $sdk->createProjectsEntity(); // Entity for using projects api methods
    $tasksEntity = $sdk->createTasksEntity();       // Entity for using tasks api methods
    $membersEntity = $sdk->createMembersEntity();   // Entity for using members api methods
    $commentsEntity = $sdk->createCommentsEntity()  // Entity for using comments api methods
    $tagsEntity = $sdk->createTagsEntity();         // Entity for using tags api methods
    $costsEntity = $sdk->createCostsEntity();       // Entity for using costs api methods
    $filesEntity = $sdk->createFilesEntity();       // Entity for using files api methods
    
    // Example of usage (returns data on all open and closed account tasks/subtasks)
    $result = $tasksEntity->get_all_tasks();
    
    // Example of usage (create comments in task)
    $result = $commentsEntity->post_comment(
        'testemail@gmail.com',               // Email user from
        'Text of my comment, hello world!',  // Text of comment
        500,                                 // Project ID
        899                                  // Task ID
    );

} catch (SdkException $e) {
    // work with errors
}
```
To update access token using refresh token you can use method:
```php
use Worksection\SDK\EntityBuilder;
use Worksection\SDK\Exception\SdkException;

// Example usage
$tokensData = EntityBuilder::refreshToken(
    '5ba135c31b89r688256h984722891861',                                 // client id
    '71aef25ea193128c9186dd89bf4537f0b0cb2d4f09a2a1b7ed05c98b25fbc1',   // client secret
    'def4720027cbe...89ce27148ad6'                                      // refresh token
);
/**
* Response in tokensData example:
  {
    "token_type": "Bearer",
    "expires_in": 86400,
    "access_token": "eyJ0...cdp7zg",
    "refresh_token": "def50200...8448rd2"
  }
 */
```
Also you can use setAutoRefreshToken method for auto refreshing (only first request, when access token has expired):
```php
use Worksection\SDK\EntityBuilder;
use Worksection\SDK\Exception\SdkException;

// Example usage
$sdk = EntityBuilder::getInstance('https://myaccount.worksection.com/');
$sdk->setAccessToken('eyJ0eXAiOiJKV...3v3tKTcdp8zg');

// If access token has expired while the request is being used
// SDK will use the specified refresh token to update access token
// then execute the request and return data with new tokens
$sdk->setAutoRefreshToken(
    '5ba135c31b89r688256h984722891861',                                 // client id
    '71aef25ea193128c9186dd89bf4537f0b0cb2d4f09a2a1b7ed05c98b25fbc1',   // client secret
    'def4720027cbe...89ce27148ad6'                                      // refresh token
);

$projectsEntity = $sdk->createProjectsEntity();
$result = $projectsEntity->get_projects();
/**
* Response example (autorefresh enable):
  {
    "status": "ok",
    "data": [
        {
            "id": "262",
            "name": "",
            "page": "/project/262/",
            "status": "archive",
            "company": "Management",
            "user_from": {
                "id": "111",
                "email": "info1@gmail.com",
                "name": "dev"
            }
        },
        {
            "id": "203",
            "name": "",
            "page": "/project/203/",
            "status": "archive",
            "company": "Design",
            "user_from": {
                "id": "222",
                "email": "info2@gmail.com",
                "name": "Smith John"
            }
        }
     ],
    "access_token": "eyJ0...cdp7zg",
    "refresh_token": "def50200...8448rd2"
  }
 */
```

## Available Entities and Methods

**[ProjectsEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/ProjectsEntity.php)** - [api docs](https://worksection.com/en/faq/api-projects.html), methods:
```php
- get_projects(string $filter = '', string $extra = '')
- close_project(int $projectId)
- post_project(string $title, array $optional = [])
- update_project(int $projectId, array $optional = [])
- get_project_groups()
- add_project_group(string $title)
- get_project(int $projectId, string $extra = '')
- add_project_members(int $projectId, string $members)
- delete_project_members(int $projectId, string $members)
- activate_project(int $projectId)
```

**[TasksEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/TasksEntity.php)** - [api docs](https://worksection.com/en/faq/api-task.html), methods:
```php
- get_all_tasks(string $filter = '', string $extra = '')
- get_task(int $taskId, string $filter = '', string $extra = '')
- get_tasks(int $projectId, string $filter = '', string $extra = '')
- post_task(string $title, int $projectId, array $optional = [])
- complete_task(int $taskId)
- reopen_task(int $taskId)
- update_task(int $taskId, array $optional = [])
- search_tasks(int $projectId, int $taskId = 0, string $emailUserFrom = '', string $emailUserTo = '', string $filter = '', string $status = '')
- get_events(string $period, int $projectId = 0)
```

**[MembersEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/MembersEntity.php)** - [api docs](https://worksection.com/en/faq/api-user.html), methods:
```php
- get_users()
- get_contacts()
- add_user(string $email, array $optional = [])
- add_contact(string $email, string $name, array $optional = [])
- subscribe(int $projectId, int $taskId, string $emailUser)
- unsubscribe(int $projectId, int $taskId, string $emailUser)
- add_user_group(string $title, int $client = null)
- add_contact_group(string $title)
- get_user_groups()
- get_contact_groups()
```

**[CommentsEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/CommentsEntity.php)** - [api docs](https://worksection.com/en/faq/api-comments.html), methods:
```php
- get_comments(int $taskId, string $extra = '')
- post_comment(string $text, int $taskId, array $optional = [])
```

**[TagsEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/TagsEntity.php)** - [api docs](https://worksection.com/en/faq/api-tags.html), methods:
```php
- get_tag_groups()
- get_project_tags()
- get_tags(string $group = '')
- add_tag_groups(string $type, string $access, string $title)
- add_tags(string $group, string $title)
- update_tags(int $projectId, int $taskId, int $subtaskId = 0, array $optional = [])
- add_project_tags(string $group, string $title)
- update_project_tags(int $projectId, int $taskId, int $subtaskId = 0, array $optional = [])
```

**[CostsEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/CostsEntity.php)** - [api docs](https://worksection.com/en/faq/api-costs.html), methods:
```php
- get_costs(int $projectId = 0, int $taskId = 0, int $subtaskId = 0, array $optional = [])
- get_costs_total(int $projectId = 0, int $taskId = 0, int $subtaskId = 0, array $optional = [])
- add_costs(string $emailUserFrom, int $projectId, int $taskId, int $subtaskId = 0, array $optional = [])
- update_costs(int $id, int $projectId, int $taskId, int $subtaskId = 0, array $optional = [])
- delete_costs(int $id, int $projectId, int $taskId, int $subtaskId = 0)
- get_timers()
- stop_timer(int $timer)
```

**[FilesEntity](https://github.com/vadymskk/worksection-sdk/blob/develop/src/Entity/FilesEntity.php)** - [api docs](https://worksection.com/en/faq/api-files.html), methods:
```php
- download(int $fileId)
- get_files(int $projectId, int $taskId = 0)
```


## License

The MIT License (MIT).
