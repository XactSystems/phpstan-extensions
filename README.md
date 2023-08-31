# A collection of PHPStan extensions

This repo contains some useful PHPStan extension for detecting errors in your code. The currently list of rules is:
  - ==UnusedClassRule==

## Install

```bash
composer require xactsystems/phpstan-extensions --dev
```


## Usage

With [PHPStan extension installer](https://github.com/phpstan/extension-installer), everything is ready to run.

Otherwise manually enable the extension:
```yaml
# phpstan.neon
include:
    'vendor/xactsystems/phpstan-extensions/config/extension.neon'
    
```

## Rules
### UnusedClassRule
This rule scans for class declarations and use statements. If a class is declared but not used within the scanned source files, an error is generated.

#### Excluding files
You can exclude directories and individual files from being scanned by this rule:

```yaml
# phpstan.neon
parameters:
    unused_classes:
        - 'src/Entity'
        - 'src/Controller'
        - 'src/Repositories'
        - 'src/MyUnusedClass.php'
```
