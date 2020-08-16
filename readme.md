# MakeWP CLient
 - [Instalation](#installation)
 - [Insights](#insights)
 - [Dynamic Usage](#usage)
 
 ## Instalation
  You can install MakeWP Client in two ways, via composer or manually.
 
### 1. Composer Installation
 Add dependency in your project (theme/plugin):
```bash 
composer require makewpdev/client
```
Now add autoload.php in your file if you haven't done already.
```php
require_once __DIR__ . '/vendor/autoload.php';
```

### 2. Manual Installation
Clone the repository in your project.

```
cd /path/to/your/project/folder
git clone https://github.com/makewpdev/client.git makewpdev
```

Now include the dependencies in your plugin/theme.
```php
require_once __DIR__ . '/makewpdev/src/Client.php';
```

## Insights
MakeWP can be used in both themes and plugins.

The `makewpdev\Client` class has three parameters:
```php
$client = new Makewpdev\Client( $hash, $name, $file );
```
- **hash** (string, required) - The unique identifier for a plugin or theme.
- **name** (string, required) - The name of the plugin or theme.
- **file** (string, required) - The main file path of the plugin. For theme, path to functions.php

## Usage Example
Please refer to the **installation** step before start using the class.

You can obtain the hash for your plugin for the [MakeWP Dashboard](https://dashboard.makewp.dev). The 3rd parameter must have to be the main file of the plugin.

```php
/**
 * Initialize the tracker
 *
 * @return void
 */
if(!function_exists('makeWP_init_tracker_plugin_name')){
function makeWP_init_tracker_plugin_name() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( 'a4a8da5b-b419-4656-98e9-4a42e9044891', 'Akismet', __FILE__ );

    // Active insights
    $client->insights()->init();

    // Active automatic updater
    $client->updater();

    // Active license page and checker
    $args = array(
        'type'       => 'options',
        'menu_title' => 'Akismet',
        'page_title' => 'Akismet License Settings',
        'menu_slug'  => 'akismet_settings',
    );
    $client->license()->add_settings_page( $args );
}

makeWP_init_tracker_plugin_name();
}

```

Makesure to:
- change `plugin_name` with your plugin slug
- call this function direcly, never use any action hook to call this function

> For plugins example code that needs to be used on your main plugin file.
> For themes example code that needs to be used on your themes `functions.php` file.
