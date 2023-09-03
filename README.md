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
---
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

### Excluding Services
By default, some known service and framework classes are excluded. There are a number of base classes from Symfony, Doctrine and PHPUnit that checked and, if matched, the class being analysed is ignored.

To disable this, set the *excludeFrameworks* property to false:
```yaml
# phpstan.neon
parameters:
    unused_classes:
        excludeFrameworks: false
```

This list will change as new frameworks and classes are added. Please look at the source code in src/Frameworks for a list of base classes that are excluded.

If you want add a custom list of base classes to ignore, use the *baseClassExcludes* property:
```yaml
# phpstan.neon
parameters:
    unused_classes:
        baseClassExcludes:
            - 'App\Service\MyAbstractService'
            - 'App\DI\MyDIClass'
```

Entries in *baseClassExcludes* are excluded regardless of the *excludeFrameworks* property value.

---
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
You can exclude directories and individual files from being scanned by this rule using the excludePaths parameter as shown above.
