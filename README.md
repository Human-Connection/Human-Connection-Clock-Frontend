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

When using the Docker installation, you we provide a fully Wordpress system with the Docker container.
Otherwise you need to setup a Wordpress system manually. To setup Wordpress you need to have a webserver with PHP and a MySQL database. 
Then just follow the famous [Wordpress 5-minute installation](https://codex.wordpress.org/Installing_WordPress#Famous_5-Minute_Installation).


## Installation

**PREREQUESITES**

Before starting the installation you need to make sure you have a recent version of [Git](https://git-scm.com/), [Composer](https://getcomposer.org/) installed. 
E.g. we have the following versions:
```
$ git --version
git version 2.14.2.windows.1
$ composer --version
Composer version 1.8.4 2019-02-11 10:52:10

Git: 2.14.2
Composer: 1.8.4
OS: Windows 10
```

**DOCKER INSTALLATION**

TODO: Add Docker installation

The Clock of Change API server comes bundled as a Docker Container, which enables you to run then server out of the box.

Of course you need to have a recent version of [Docker](https://www.docker.com/get-started) installed. If you don't have Docker, follow the instructions of the link.
You can check the version like this:
```
$ docker -v
Docker version 18.09.1, build 4c52b90
``` 

To run the Docker version, follow these steps:
1. First you need to clone the git repository of the Clock of Change API. Head to a directory where you want the git repository to reside
and open the directory in the console. Then run `git clone https://github.com/Human-Connection/Clock-of-Change-API.git` to clone the repository to this directory.
2. Go to the newly created Clock-of-Change-API directory (`cd Clock-of-Change-API` in the console)
3. Run `docker-compose up`. This will build the Docker container on first startup and run it. This can take a while, but after some time you should see the Clock of Change ticking.

Now the Clock of Change API server is ready for usage at [http://127.0.0.1:1337](http://127.0.0.1:1337)

**LOCAL INSTALLATION & USAGE**

If you do not want to use the docker version, you can also install the Clock of Change Frontend locally. 
This requires a [Wordpress system](#project-structure-&-components) for the Clock of Change to run in as a plugin.

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

Here you will find a list of all the entries, that are stored in the Clock of Change API server.

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

* coc\shortcodes\shworld
* [coc\shortcodes\shsign]
* coc\shortcodes\shsignup
* [coc\shortcodes\shuserwall]


| Shortcode | Description |
|---|---|
| coc\shortcodes\shworld | - Temp Route until path can be changed<br/>- Returns the current number of confirmed entries<br/>- No authentication required<br/>- Returns a number/string (we change to json once we can adjust hardware clocks)  |
| coc\shortcodes\shsign | - Verify an entry with a email validation link/hash :k (for example http://127.0.0.1/entries/verify/sadSdjsarj3jf3j3wfmwfj3w)<br/>- Returns Json {success : true || false, invalidKeyError if hash is invalid/used} |
| coc\shortcodes\shsignup | - Requires auth (see AUTHENTICATION)<br/>- Create a new entry from body parameters<br/>- Body parameters: firstname, lastname, email, country, message, anon (int 0 or 1), image<br/>- Returns Json {success : true}<br/>- May return errors mimeError, sizeError, missingFields, fieldErrors, missingImageError |
| coc\shortcodes\shuserwall | - Requires auth<br/>- Get back all entries<br/>- Parameters: limit (default 10), offset (default 0) and active (default false)<br/>- If active is true,  only confirmed and active accounts will be returned<br/>- Returns Json {success : true, results : out, totalCount : results.length, page : offset} or<br/> - Return {success : false, message : "error"}|


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
