### Installation

*The installation process is almost fully automated, but there are a few steps to take to get it working as expected.*

1. `git clone git@github.com:EmersonStone/serenova.com.git [name-of-project]`
2. `cd [name-of-project]`
3. run `vagrant up`

* * *

*Note:*  If you are presented with error 500 on vagrant up - it may be necessary to add ` define( 'WP_DEBUG', true ); ` on line 40 of your wp-config.php file.  Alternatively, you can copy the wp-config-sample.php contents to your wp-config.php file.

**Access site at `http://serenova.local` and wp-admin at `http://serenova.local/wp-admin` (see credentials below).**

#### What Happens on Vagrant Up?

*When running `vagrant up` a variety of things happen:*

- Power up the virtual machine
- Add `serenova.local` to the host machine's hosts file
- Install any necessary dependencies within the virtual machine (PHP, MySQL, etc)
- Install all PHP dependencies via Composer
- Download WordPress
- Run the WordPress installer

#### Plugins installed:
- [Timber](https://www.upstatement.com/timber/)
- [Advanced Custom Fields](https://www.advancedcustomfields.com/)
- [ACF Smart Button](https://github.com/gillesgoetsch/acf-smart-button)
- [Nested Pages](https://wordpress.org/plugins/wp-nested-pages/)
- Emerson Stone Welcome Plugin (in development)

#### Automated Features
- Permalink structure is updated to `/%postname%`
- Emerson Stone Theme is installed
- Hello plugin is deleted
- Akismet plugin is deleted
- Generic image is uploaded to Media Library (used for page creation which is in development)

### Development Environment

*The development environment runs on a VirtualBox virtual machine that is managed using Vagrant. The following packages and tools are required to properly run this application:*

* [Vagrant](https://www.vagrantup.com/) - Development Environment Orchestration
* [Virtual Box](https://www.virtualbox.org/) - Virtualization Platform

### Credentials

#### Admin Credentials

The Vagrant provisioner automatically runs the WordPress installer with the following pre-created administration account credentials:

| Key      | Value   |
|----------|---------|
| Username | `admin` |
| Password | `admin` |

#### Database Credentials

If you would like to connect directly to the database (via the `mysql` command line utility, [Sequel Pro](https://www.sequelpro.com/), or whatever), you can use the following database credentials:

| Key      | Value       |
|----------|-------------|
| Hostname | `localhost` |
| Username | `root`      |
| Password | `root`      |
| Database | `scotchbox` |

**Note: If you are connecting to MySQL from outside of the Vagrant box, be sure to connect to the database using SSH tunneling. The SSH host is `192.168.33.10` and the username and password are both `vagrant`.**

### Static Assets Process

Static assets reside in the src folder in the root of site installation.  To compile these assets:

- run `npm run dev`
- watch `npm run watch`

*or*

- run `yarn`
- watch `yarn watch`

#### Customize route of compiled static files in webpack.mix.js and within theme

- Go to root of site install, webpack.mix.js and change route.
- In theme, go config > setup > Enqueue.php and update the route to app.css and app.js.

### Gitignore

Files that are ignore locally to your github repo are added in the .gitignore file.  Here you're ignoring everything except contents withing wp-content folder, which holds the plugins and the themes, the downloads folder in the root and the plugins folder in the root which are accessed when vagrant runs or provisions.

### Deployment

Deployment from the `develop` and `master` branches is automatic via CircleCI

## Development Environment
[wip]

### Colors

All colors are first set in the `helpers/_vars` file, and then added as a class for fill, text, or both in the `utilities/_colors` file.

As usual, we have many different variations of gray -- so the variable names for these colors will be slightly less intuitive. We've set up the grays from darkest (00) to lightest (100) and left some space in between for new variables to be added. Check out the scale on the [Asana task](https://app.asana.com/0/847218748635335/847408284555722)
