# Clock-of-Change Frontend

This repository contains the Frontend part of the Clock of Change in form of a Wordpress plugin.

The purpose of the Clock of Change Frontend is to display the visual part of the Clock of Change, 
handle user interactions (especially registrations) and provide a basic administration surface for the Clock of Change through Wordpress.

The Clock of Change Frontend depends on the [Clock of Change API](https://github.com/Human-Connection/Clock-of-Change-API),
which acts as the Backend and takes care of storing and processing the data.

To find out more about the Clock of Change and Human Connection - the network behind it - visit https://human-connection.org/en/.

## Tech Stack

* PHP: The Frontend uses mainly [PHP](http://php.net/) as programming language
* Wordpress: The Frontend is running on [Wordpress](https://wordpress.org/download/) - the most used content management system
* MySQL: Wordpress requires a [MySQL](https://www.mysql.com) database 
* JavaScript / jQuery: We use [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) and [jQuery](https://jquery.com/) for the UX, user interactions and animations
* CSS: We use [CSS](https://developer.mozilla.org/en-US/docs/Learn/Getting_started_with_the_web/CSS_basics) to style the Frontend
* Composer: We use [Composer](https://getcomposer.org/) to manage (PHP) dependencies

## Project Structure & Components

**PROJECT STRUCTURE / DIRECTORIES**

* coc/: Main directory for the Clock of Change Frontend Wordpress plugin
* coc/assets/: Assets for the Frontend with subdirectories for css, images and js files
* coc/classes/: Contains the PHP classes for the Frontend with the main class ClockOfChange.php 
and core and shortcode classes in the respective subdirectories
* coc/config: Contains the Wordpress options for the plugin
* coc/helper: Contains helper classes
* coc/vendor/: Contains the dependencies (packages) that are managed by composer
* coc/coc.php: Main Wordpress plugin file

**WORDPRESS**

The Clock of Change Frontend is running as a Wordpress plugin. This is why we need a Wordpress system, 
in which we can use the Clock of Change Frontend as a plugin.

When using the Docker installation, we provide a full Wordpress system with the Docker container.
Otherwise you need to setup a Wordpress system manually. To setup Wordpress you need to have a webserver with PHP and a MySQL database. 
Then just follow the famous [Wordpress 5-minute installation](https://codex.wordpress.org/Installing_WordPress#Famous_5-Minute_Installation).


## Installation

**DOCKER INSTALLATION**

The Clock of Change Frontend comes bundled as a Docker Container, which enables you to run a Wordpress system 
with the Clock of Change Frontend plugin preinstalled and preconfigured out of the box.

Of course you need to have a recent version of [Docker](https://www.docker.com/get-started) installed. If you don't have Docker, follow the instructions of the link.
You can check the version like this:
```
$ docker -v
Docker version 18.09.1, build 4c52b90
``` 

To run the Docker installation, follow these steps:
1. First you need to clone the git repository of the Clock of Change Frontend. Head to a directory where you want the git repository to reside
and open the directory in the console. Then run `git clone https://github.com/Human-Connection/Clock-of-Change-Frontend.git` to clone the repository to this directory.
2. Go to the newly created Clock-of-Change-Frontend directory (`cd Clock-of-Change-Frontend` in the console)
3. Run `docker-compose up`. This will build the Docker container on first startup and run it. This can take a while, but after some time the system will be up and running.
4. Install Wordpress once at [http://localhost:8000](http://localhost:8000)
4. To activate and configure the Clock of Change Wordpress plugin, enter `docker-compose run --rm cli wp-init.sh` in the console. 
This also creates a sample page with all the available shortcodes.

Now you can use the Clock of Change Frontend in Wordpress at [http://localhost:8000](http://localhost:8000)

**LOCAL INSTALLATION & USAGE**

If you do not want to use the docker version, you can also install the Clock of Change Frontend locally. 
This requires a [Wordpress system](#project-structure-&-components) for the Clock of Change to run in as a plugin.

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

1. First you need to clone the git repository of the Clock of Change Frontend. Head to a directory where you want the git repository to reside
and open the directory in the console. Then run `git clone https://github.com/Human-Connection/Clock-of-Change-Frontend.git` to clone the repository to this directory.
2. Go to the newly created Clock-of-Change-Frontend directory (`cd Clock-of-Change-Frontend` in the console) and run `composer install`.
Now all the dependencies should install.
3. Now you need to copy the `coc/` directory into the Wordpress plugins directory `wp-content/plugins/`. 
Alternatively you can symlink the `coc/` directory into the Wordpress plugin directory, see [https://en.wikipedia.org/wiki/Symbolic_link](https://en.wikipedia.org/wiki/Symbolic_link)
4. Now the Clock of Change Frontend should show up in your Wordpress system and you just need to activate it in the Wordpress Admin Backend under Plugins.

Now you can use the Clock of Change Frontend in Wordpress.

## Usage

**USAGE IN WORDPRESS ADMIN BACKEND**

With the installation of the Clock of Change Frontend plugin in Wordpress, there will be two additional entries in the Wordpress Admin Backend:

![Clock of Change Wordpress Backend](documentation/coc-wordpress-backend.png)

* CoC Options: Settings for the connection to the Clock of Change API Server
* CoC Entries: View & manage the entries in the Clock of Change. Enable/Disable entries here.

**SET COC OPTIONS IN WORDPRESS**

The Clock of Change Frontend needs to be connected to the Clock of Change API server in order to display the data, especially the entries.

To establish a connection, it is necessary to set the base url and a valid API key for the Clock of Change API server. 
This needs to be done under `CoC Options` in the Wordpress admin backend in the respective fields.

For example when running the API server locally, this could be the settings:
```
Your API Key: secret
API base url: http://localhost:1337
```

Please mind that a trailing slash for the API base url is not allowed.

**MANAGE COC ENTRIES IN WORDPRESS**

After setting valid options for the Clock of Change API server, we can list and manage all the Clock of Change entries 
in the Wordpress admin backend under `CoC Entries`.

Here we will find a list of all the entries that are stored in the Clock of Change API server.

It is also possible to activate and/or disable an entry. When hovering over the status of an entry the links to activate or disable the entry show up.

**DISPLAY CLOCK OF CHANGE**

To display the visual parts of the Clock of Change in Wordpress, we ca use [shortcodes](https://codex.wordpress.org/Shortcode).
These shortcodes can be integrated into any Wordpress post or page inside any text element with the following syntax:

```
[shortcode]
```

The shortcode needs to be placed inside of the square brackets and is then automatically replaced with the Clock of Change visual representation.

To see which shortcodes are available for the Clock of Change Frontend, please refer to the following list.

**WORDPRESS SHORTCUTS**

List of Clock of Change Frontend shortcodes for Wordpress:

| Shortcode | Description |
|---|---|
| coc\shortcodes\shworld | - Display the animated Clock of Change with the turning world animation and the counter |
| coc\shortcodes\shsignup | - Display the signup button<br/>-Requires the coc\shortcodes\shsign shortcode |
| coc\shortcodes\shsign | - Display the signup modal with the form, which opens when clicking on the signup button<br/>-Requires the coc\shortcodes\shsignup shortcode |
| coc\shortcodes\shuserwall | - Display the Clock of Change user wall with the entries |


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
