# ss-traitor
A simple extension for Silverstripe CMS, which logs the author of each record when it is created or updated.

## Overview

The Traitor extension stores author and editor of a data object. While this is already done for versioned data objects by the Versioned extension, only the timestamps are stored for unversioned data objects. To close this gap, this extension was developed.

Note: Originator and author are not output by default (e.g. in a grid or form). The intention of the extension is only to store the data and make it available.

### Installation

Install the package via composer.

```
composer require clesson-de/traitor
```
The module is not enabled by default.

Create a new file (e.g. traitor.yml) in the app/_config/ directory and insert the following paragraph. If the extension is not to be applied to all data objects, repeat the paragraph for each desired data object.
After creating the configuration, a dev/build must be executed.

```
---
Name: config-traitor
---

SilverStripe\ORM\DataObject:
  extensions:
    - Clesson\Traitor\Extensions\TraitorExtension
```

### Configuration

A configuration is not mandatory but possible. By default, the first and last name of the author (Title) are stored. If the e-mail address or any other property of the member object should be stored instead, this field can be set in the configuration:

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

Please [create an issue](https://github.com/clesson-de/traitor/issues/new)
for any bugs you've found, or features you're missing.
