# Homestead
------

- [Les bases](#section-1)
- [Installation](#section-2)
- [Lancement et configuration](#section-3)

<a name="section-1"></a>

## Bases

Pour pouvoir utiliser homestead il faut:

* Vagrant
* PHP storm
* Un pc ;-D 

Commandes 

`vagrant init`  -->  Initialiser les vagrant files

`vagrant up` --> Lancer la box

`vagrant destroy`  --> Détruire la vm

`vagrant box add`  --> Ajouter une box



<a name="section-1"></a>

## Installation

Une petite commande pour créer la box.

`vagrant box add laravel/homestead`

et la petite deuxième pour cloner le dossier. 

`git clone https://github.com/laravel/homestead.git ~/Homestead`.

On déplace le dossier Homestead dans le même répertoire que notre projet Forum.

Ensuite on accède au dossier.

`cd chemin/Homestead`

Enfin, on initialise le tout.

``````
// Mac / Linux...
bash init.sh

// Windows...
init.bat
``````



<a name="section-1"></a>

## Lancement et configuration

On modifie le dossier host pour l'url.

``C:\Windows\System32\drivers\etc\hosts`

``` bash
#	127.0.0.1       localhost
#	::1             localhost
    192.168.10.10   gui.test 
```

On modifie aussi le fichier Homestead.yaml

```yaml
---
ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: D:/Ynov/Projet/Php/Forum
      to: /home/vagrant/code/Forum

sites:
    - map: gui.test
      to: /home/vagrant/code/Forum/public

databases:
    - Forum
```

et enfin le fichier .env

```php
DB_CONNECTION=mysql
DB_HOST=192.168.10.10
DB_PORT=3306
DB_DATABASE=Forum
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Lancer la commande `Vagrant up` dans le dossier Homestead, un `php artisan migrate` et pour finir, le connecter à la base de données avec PhpStorm.

> {warning} Attention !! Si aucune clef ssh n'a été généré il risque d'avoir une erreur !! Utiliser `ssh-keygens`.









