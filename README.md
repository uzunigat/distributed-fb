# FB Distribué

Ce projet cherche à créer un réseau social à l'aide d'algorithmes distribués.

## Sujet
---
L'idée d'avoir un réseau social distribué est que chaque utilisateur possède ses informations et dispose d'une copie de l'application pour son utilisation.

L'utilisateur ne saura jamais que l'utilisation du système est distribuée, il aura l'impression qu'il s'agit d'un système centralisé comme cela se fait normalement.

Pour la propagation de messages ou de publications, nous utiliserons l'algorithme **demi-vague**

# Docker CONFIG

## Requirement:
- linux VM (ubuntu)
- docker engine
- docker compose

Liens :
- https://docs.docker.com/engine/install/ubuntu/
- https://docs.docker.com/compose/install/
- https://docs.docker.com/engine/install/linux-postinstall/

## Pour lancer différente application :

Pour l'instant modifié un utilisateur manuellement en base de donnée pour utilsateur unique

### Creation network bridge  :
```bash=
docker network create sr05-red
```

**TODO** : Script d'initialisation de base

**Attention** : bien penser a changer les CONTAINER_NAME et ne pas laisser nom.server
Le nom.server choisit pour chaque conteneur sera celui qui devra être inscrit dans la base de donnés, en tant qu'owner dans la table amis

- Premier site :
```bash=
PORT_SERVER=8001 PORT_SQL=3306 PORT_PMA=3000 CONTAINER_NAME="nom.server" docker-compose -p first up -d 
```
- Deuxième site :
```bash=
PORT_SERVER=8002 PORT_SQL=3307 PORT_PMA=3001 CONTAINER_NAME="nom2.server" docker-compose -p second up -d
```
- Troisième site :
```bash=
PORT_SERVER=8003 PORT_SQL=3308 PORT_PMA=3002 CONTAINER_NAME="nom3.server" docker-compose -p third up -d
```

**Windows**

Afin de bien définir les variables d'environnement sur Windows il faut faire le suivant pour chaque un des sites (Modifier les variables à chaque fois qu'on souhaite créer une autre conteneur):

```bash=
$env:PORT_SERVER=8001 
$env:PORT_SQL=3306 
$env:PORT_PMA=3000
$env:CONTAINER_NAME="nom.server"

docker-compose -p first up -d

```


### Lister les container :
```bash=
docker container ls
```
##  Arreter un container :
```bash=
docker stop {container name}
docker rm {container name}
```
## Arreter tous les containers :
```bash=
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
```

## Rentrer dans un container :
Pour avoir accès au /etc/hosts par exemple :

```bash=
docker exec -it [nom du container] bash
```

## Utilisation de l'application
---
Tout d'abord, une fois fait l'installation des conteneurs en local, il est important de commencer a connecter le réseau:

Dans la section "Amis" un site doit envoyer une rêquete d'ami avec son url et port (Le port est nécessaire quand on teste en local).

Imaginons qu'on a 3 sites:

        - http://nom.server1    port: 8001
        - http://nom.server2    port: 8002
        - http://nom.server3    port: 8003

**Note: Il est important de modifier le tableau "Utilisateur" dans la BDD de chaque conteneur. On doit modifier le seul registre en écrivant l'url correcte (i.e. Pour le site 1 l'url serai -> http://nom.server1:8001)**

Le site "nom.server1" souhaite devenir ami du site 2, dans la section "Amis" il écrit la rêquete dans les deux champs input:

```
url: http://nom.server2
port: 8002
```
Si on a bien fait les derniers steps, on devrait recevoir une rêquete dans le site 2 et il faut juste l'accepter afin de pouvoir connecter les deux sites et avoir la possibilité d'échanger POST ou messages.
