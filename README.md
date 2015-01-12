# Laravel Base Helpers

A set of base helper functions for Laravel applications.

## Installing

Require this package in your `composer.json` file:

```json
{
	"require": {
		"antarctica/laravel-base-helpers": "~0.1"
	}
}
```

Run `composer update`.

## Usage

* Helper functions are organised into multiple files described below within the `src/Helpers` directory
* All helpers are documented using DocBlocks to provide usage information.

Note: All helper functions are currently loaded into the global scope. This may change in the future.

### `utils.php`

General purpose functions. These functions may also be used within other functions, including within this package.

## Contributing

This project welcomes contributions, see `CONTRIBUTING` for our general policy.

## Developing

To aid development and keep your local computer clean, a VM (managed by Vagrant) is used to create an isolated environment with all necessary tools/libraries available.

### Requirements

* Mac OS X
* Ansible `brew install ansible`
* [VMware Fusion](http://vmware.com/fusion)
* [Vagrant](http://vagrantup.com) `brew cask install vmware-fusion vagrant`
* [Host manager](https://github.com/smdahlen/vagrant-hostmanager) and [Vagrant VMware](http://www.vagrantup.com/vmware) plugins `vagrant plugin install vagrant-hostmanager && vagrant plugin install vagrant-vmware-fusion`
* You have a private key `id_rsa` and public key `id_rsa.pub` in `~/.ssh/`
* You have an entry like [1] in your `~/.ssh/config`

[1] SSH config entry

```shell
Host bslweb-*
    ForwardAgent yes
    User app
    IdentityFile ~/.ssh/id_rsa
    Port 22
```

### Provisioning development VM

VMs are managed using Vagrant and configured by Ansible.

```shell
$ git clone ssh://git@stash.ceh.ac.uk:7999/basweb/laravel-base-helpers.git
$ cp ~/.ssh/id_rsa.pub laravel-base-helpers/provisioning/public_keys/
$ cd laravel-base-helpers
$ ./armadillo_standin.sh

$ vagrant up

$ ssh bslweb-laravel-base-helpers-dev-node1
$ cd /app

$ composer install

$ logout
```

### Committing changes

The [Git flow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow) workflow is used to manage development of this package.

Discrete changes should be made within *feature* branches, created from and merged back into *develop* (where small one-line changes may be made directly).

When ready to release a set of features/changes create a *release* branch from *develop*, update documentation as required and merge into *master* with a tagged, [semantic version](http://semver.org/) (e.g. `v1.2.3`).

After releases the *master* branch should be merged with *develop* to restart the process. High impact bugs can be addressed in *hotfix* branches, created from and merged into *master* directly (and then into *develop*).

### Issue tracking

Issues, bugs, improvements, questions, suggestions and other tasks related to this package are managed through the BAS Web & Applications Team Jira project ([BASWEB](https://jira.ceh.ac.uk/browse/BASWEB)).

### Clean up

To remove the development VM:

```shell
vagrant halt
vagrant destroy
```

The `laravel-base-helpers` directory can then be safely deleted as normal.

## License

Copyright 2015 NERC BAS. Licensed under the MIT license, see `LICENSE` for details.

