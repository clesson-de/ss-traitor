# ss-traitor
A simple extension for Silverstripe CMS, which logs the author of each record when it is created or updated. Originated from the need to build this extension again and again.

## Overview

The Traitor extension stores author and editor of a data object. While this is already done for versioned data objects by the Versioned extension, only the timestamps are stored for unversioned data objects. To close this gap, this extension was developed.

Note: Originator and author are not output by default (e.g. in a grid or form). The intention of the extension is only to store the data and make it available.

### Installation

Install the package via composer.

```
composer require clesson-de/traitor
```
The module is not enabled by default.

Create a new file (e.g. ```traitor.yml```) in the ```app/_config/``` directory and insert the following paragraph. Alternatively, you can add the section to an existing config file (e.g. ```app/_config/mysite.yml```).

```
---
Name: traitor config
---

Page:
  extensions:
    - Clesson\Traitor\Extensions\TraitorExtension
```
If the extension is not to be applied to all data objects, repeat the paragraph for each desired data object:

```
Page:
  extensions:
    - Clesson\Traitor\Extensions\TraitorExtension

Example\Namespace\MyDataObject:
  extensions:
    - Clesson\Traitor\Extensions\TraitorExtension
```

If you prefer to configure it in PHP, you can also add the extension directly to the classes you want to extend:
```
    use Clesson\Traitor\Extensions\TraitorExtension;

    private static $extensions = [
        TraitorExtension::class,
        ...
    ];
```

Please note that you can only assign this extension to subclasses of DataObject. If you assign the extension for DataObject, you will get an error message.

After creating the configuration, a ```dev/build``` must be executed.

### Configuration

A configuration is not mandatory but possible. By default, the first and last name of the author (```Title```) are stored. If the e-mail address or any other property of the member object should be stored instead, this field can be set in the configuration:

```
Clesson\Traitor\Extensions\TraitorExtension:
  traitor_field: 'Email'
```

## Contributing

Contributions are welcome! Create an issue, explaining a bug or propose development
ideas. Find more information on
[contributing](https://docs.silverstripe.org/en/contributing/) in the
Silverstripe CMS developer documentation.

## Reporting Issues

Please [create an issue](https://github.com/clesson-de/ss-traitor/issues/new)
for any bugs you've found, or features you're missing.
