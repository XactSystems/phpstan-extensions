# A collection of PHPStan extensions

This repo contains some useful PHPStan extension for detecting errors in your code. The currently list of rules is:
## `UnusedClassRule`
## `UnusedTraitRule`

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
### `UnusedClassRule`
This rule scans for class declarations and use statements. If a class is declared but not used within the scanned source files, an error is generated.

### Disabling the rule
You can disable scanning classes as follows:
```yaml
# phpstan.neon
parameters:
    unused_classes:
        classes: false
```

#### Excluding files
You can exclude directories and individual files from being scanned by this rule:

```yaml
# phpstan.neon
parameters:
    unused_classes:
        excludePaths:
            - 'src/Controller'
            - 'src/MyUnusedClass.php'
```

### `UnusedTraitRule`
This rule scans for trait declarations and use statements. If a trait is declared but not used within the scanned source files, an error is generated.

### Disabling the rule
You can disable scanning traits as follows:
```yaml
# phpstan.neon
parameters:
    unused_classes:
        traits: false
```

#### Excluding files
You can exclude directories and individual files from being scanned by this rule using the excludePaths parameter as shown above/
