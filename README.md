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
	// Example of usage ()
	$result = $projectsEntity->get_project(320);

} catch (SdkException $e) {
	// work with errors
}

```

## License

The MIT License (MIT).
