# Human-Connection-Clock Frontend

This repository contains the Frontend part of the Human Connection Clock in form of a Wordpress plugin.

The purpose of the Human Connection Clock Frontend is to display the visual part of the Human Connection Clock, 
handle user interactions (especially registrations) and provide a basic administration surface for the Human Connection Clock through Wordpress.

The Human Connection Clock Frontend depends on the [Human Connection Clock API](https://github.com/Human-Connection/Human-Connection-Clock-API),
which acts as the Backend and takes care of storing and processing the data.

To find out more about the Human Connection Clock and Human Connection - the network behind it - visit https://human-connection.org/en/.

## Tech Stack

* PHP: The Frontend uses mainly [PHP](http://php.net/) as programming language
* Wordpress: The Frontend is running on [Wordpress](https://wordpress.org/download/) - the most used content management system
* MySQL: Wordpress requires a [MySQL](https://www.mysql.com) database 
* JavaScript / jQuery: We use [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) and [jQuery](https://jquery.com/) for the UX, user interactions and animations
* CSS: We use [CSS](https://developer.mozilla.org/en-US/docs/Learn/Getting_started_with_the_web/CSS_basics) to style the Frontend
* Composer: We use [Composer](https://getcomposer.org/) to manage (PHP) dependencies
* Webpack: We use [Webpack](https://webpack.js.org/) to transpile and bundle the JavaScript

## Project Structure & Components

**PROJECT STRUCTURE / DIRECTORIES**

* `coc/`: Main directory for the Human Connection Clock Frontend Wordpress plugin
* `coc/assets/`: Assets for the Frontend with subdirectories for css, images and js files
* `coc/classes/`: Contains the PHP classes for the Frontend with the main class ClockOfChange.php 
and core and shortcode classes in the respective subdirectories
* `coc/config`: Contains the Wordpress options for the plugin
* `coc/helper`: Contains helper classes
* `coc/vendor/`: Contains the dependencies (packages) that are managed by composer
* `coc/coc.php`: Main Wordpress plugin file

**WORDPRESS**

The Human Connection Clock Frontend is running as a Wordpress plugin. This is why we need a Wordpress system, 
in which we can use the Human Connection Clock Frontend as a plugin.

When using the Docker installation, we provide a full Wordpress system with the Docker container.
Otherwise you need to setup a Wordpress system manually. To setup Wordpress you need to have a webserver with PHP and a MySQL database. 
Then just follow the famous [Wordpress 5-minute installation](https://codex.wordpress.org/Installing_WordPress#Famous_5-Minute_Installation).


## Installation

**DOCKER INSTALLATION**

The Human Connection Clock Frontend comes bundled as a Docker Container, which enables you to run a Wordpress system 
with the Human Connection Clock Frontend plugin preinstalled and preconfigured out of the box.

Of course you need to have a recent version of [Docker](https://www.docker.com/get-started) installed. If you don't have Docker, follow the instructions of the link.
You can check the version like this:
```
$ docker -v
Docker version 18.09.1, build 4c52b90
``` 

To run the Docker installation, follow these steps:
1. First you need to clone the git repository of the Human Connection Clock Frontend. Head to a directory where you want the git repository to reside
and open the directory in the console. Then run `git clone https://github.com/Human-Connection/Human-Connection-Clock-Frontend.git` to clone the repository to this directory.
2. Go to the newly created Human-Connection-Clock-Frontend directory (`cd Human-Connection-Clock-Frontend` in the console)
3. Run `docker-compose up`. This will build the Docker container on first startup and run it. This can take a while, but after some time the system will be up and running.
4. Install Wordpress once at [http://localhost:8000](http://localhost:8000)
4. To activate and configure the Human Connection Clock Wordpress plugin, enter `docker-compose run --rm cli wp-init.sh` in the console. 
This also creates a sample page with all the available shortcodes.

Now you can use the Human Connection Clock Frontend in Wordpress at [http://localhost:8000](http://localhost:8000)

**LOCAL INSTALLATION & USAGE**

If you do not want to use the docker version, you can also install the Human Connection Clock Frontend locally. 
This requires a [Wordpress system](#project-structure-&-components) for the Human Connection Clock to run in as a plugin.

**Prequesites**

Before starting the installation you need to make sure you have a recent version of [Composer](https://getcomposer.org/) installed. 
E.g. we have the following versions:
```
$ composer --version
Composer version 1.8.4 2019-02-11 10:52:10

Composer: 1.8.4
OS: Windows 10
```

**Installation & Usage**

1. First you need to clone the git repository of the Human Connection Clock Frontend. Head to a directory where you want the git repository to reside
and open the directory in the console. Then run `git clone https://github.com/Human-Connection/Human-Connection-Clock-Frontend.git` to clone the repository to this directory.
2. Go to the newly created Human-Connection-Clock-Frontend directory (`cd Human-Connection-Clock-Frontend` in the console) and run `composer install`.
Now all the dependencies should install.
3. Now you need to copy the `coc/` directory into the Wordpress plugins directory `wp-content/plugins/`. 
Alternatively you can symlink the `coc/` directory into the Wordpress plugin directory, see [https://en.wikipedia.org/wiki/Symbolic_link](https://en.wikipedia.org/wiki/Symbolic_link)
4. Now the Human Connection Clock Frontend should show up in your Wordpress system and you just need to activate it in the Wordpress Admin Backend under Plugins.

Now you can use the Human Connection Clock Frontend in Wordpress.

## Usage

**USAGE IN WORDPRESS ADMIN BACKEND**

With the installation of the Human Connection Clock Frontend plugin in Wordpress, there will be two additional entries in the Wordpress Admin Backend:

![Human Connection Clock Wordpress Backend](documentation/coc-wordpress-backend.png)

* CoC Options: Settings for the connection to the Human Connection Clock API Server
* CoC Entries: View & manage the entries in the Human Connection Clock. Enable/Disable entries here.

**SET COC OPTIONS IN WORDPRESS**

The Human Connection Clock Frontend needs to be connected to the Human Connection Clock API server in order to display the data, especially the entries.

First you need to import the definitions of the advanced custom fields into your Wordpress installation. 
In the WP Admin backend under `Custom Fields -> Tools` you need to import the field definitions you will find in `coc\config\acf-export-2020-02-09.json`.

To establish a connection, it is necessary to set the base url and a valid API key for the Human Connection Clock API server. 
This needs to be done under `CoC Options` in the Wordpress admin backend in the respective fields.

For example when running the API server locally, this could be the settings:
```
Your API Key: secret
API base url: http://coc-api:1337
```

Please mind that a trailing slash for the API base url is not allowed.

Note: Due to problems with the Advanced Custom Fields Plugin and the Localization Plugin WPML, there is a second
fallback config under `coc\config\custom.php`. Please never commit your config settings with Git, because it will be visible to everybody on GitHub.
Only change the settings in the file on the live server.

**MANAGE COC ENTRIES IN WORDPRESS**

After setting valid options for the Human Connection Clock API server, we can list and manage all the Human Connection Clock entries 
in the Wordpress admin backend under `CoC Entries`.

Here we will find a list of all the entries that are stored in the Human Connection Clock API server.

It is also possible to activate and/or disable an entry. When hovering over the status of an entry the links to activate or disable the entry show up.

**DISPLAY Human Connection Clock**

To display the visual parts of the Human Connection Clock in Wordpress, we ca use [shortcodes](https://codex.wordpress.org/Shortcode).
These shortcodes can be integrated into any Wordpress post or page inside any text element with the following syntax:

```
[shortcode]
```

The shortcode needs to be placed inside of the square brackets and is then automatically replaced with the Human Connection Clock visual representation.

To see which shortcodes are available for the Human Connection Clock Frontend, please refer to the following list.

**WORDPRESS SHORTCUTS**

List of Human Connection Clock Frontend shortcodes for Wordpress:

| Shortcode                     | Description                                                                                                                                |
| ---                           | ---                                                                                                                                        |
| `[coc\shortcodes\shworld]`    | Display the animated Human Connection Clock with the turning world animation and the counter                                                      |
| `[coc\shortcodes\shsignup]`   | Display the signup button<br/>-Requires the coc\shortcodes\shsign shortcode                                                                |
| `[coc\shortcodes\shsign]`     | Display the signup modal with the form, which opens when clicking on the signup button<br/>-Requires the coc\shortcodes\shsignup shortcode |
| `[coc\shortcodes\shuserwall]` | Display the Human Connection Clock user wall with the entries                                                                                     |


**RUN WEBPACK BUILD PROCESS**
In this project we use webpack to bundle and transpile JavaScript. This essentially means that we use a newer JavaScript syntax (ES6+),
but compile this version to make it also possible for older browsers to run the Human Connection Clock (essentially IE9+).

Once you have installed the npm modules with `npm install`, you can run the webpack build process with command `npm run build`.
If you do not want to enter this command after every change, you can run `npm run watch`. This watch and automatically trigger the
build process, once a JavaScript file has been changed.

<br/>
<br/>

```
       _____
    _.'_____`._
  .'.-'  12 `-.`.
 /,' 11      1 `.\
// 10           2 \\
;;                 ::
|| 9  ---O-----  3 ||
::                 ;;
\ 8            4  //
 \`. 7       5 ,'/
 '.`-. __6__ .-'.'
   ((-._____.-))
   _))       ((_
  '--'  COC  '--'
```
