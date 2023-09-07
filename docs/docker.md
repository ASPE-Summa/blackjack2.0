# Docker

Docker is een tool om gevirtualiseerd applicaties te draaien. Jouw computer is de 'host', en de gevirtualiseerde applicaties zijn 'guests'.

## Virtualisatie

Docker is anders dan andere virtualisatie zoals [Virtualbox](https://www.virtualbox.org/) of [VMware](https://www.vmware.com/nl.html) omdat het niet het hele besturingssysteem virtualiseert voor elke guest. In plaats daarvan hergebruikt het dezelfde kernel voor elke guest, en is dus alleen de software anders.

Deze kleine guests die een kernel delen worden 'containers' genoemd. Het zijn dus kleine zelfstandige gevirtualiseerde computers met hun eigen besturingssysteem en software.

## Docker compose

Verschillende docker containers kunnen samen worden gecombineerd om samen een applicatie te vormen. Hiervoor gebruik je [docker compose](https://docs.docker.com/compose/).

Door containers klein te houden, en te zorgen dat ze zo min mogelijk software bevatten, heb je enkele voordelen:

- Het is makkelijker om jouw applicatie te delen met een ander, inclusief alle instellingen en software die je nodig hebt om het te draaien. Je hoeft alleen je `docker-compose.yml` te delen.
- Als je software wilt updaten, hoef je alleen één container te updaten.
- Omdat alle stukjes software in hun eigen container draaien is het ook veiliger.

## Docker in dit project

In dit starterproject hebben we een docker-compose.yml met twee containers. Als je `docker-compose.yml` opent kan je ze vinden:

```yaml
blackjack_php:
image: php:8.2-fpm-alpine3.18
volumes:
- ./app:/opt/app
restart: always
```

Deze container bevat de officiele [PHP container imge](https://hub.docker.com/_/php). Door zo'n container van Dockerhub te halen hoef je hem zelf niet te bouwen, je download hem automatisch van het internet.

Achter de `:` staat de versie die we gebruiken. 8.2 is de PHP versie, [fpm](https://www.php.net/manual/en/install.fpm.php) is een high-performance variant met meerdere workers, en -alpine3.18 geeft aan dat het image is gebaseerd op [Alpine Linux](https://www.alpinelinux.org/). Een lichtgewicht Linux versie die perfect is voor containers.

Onder `volumes` verbinden we een map op de lokale machine (`./app`) naar een map in de virtuele machine in de container (`/opt/app`). Als je een bestand lokaal wijzigt, wijzigt die hierdoor nu ook in de container.

Als laatste staat er de `restart: always`. Hiermee vertellen we `docker-compose` dat we graag willen dat deze de `blackjack_php` container automatisch herstart als hij crasht. Wel zo handig.

```yaml
blackjack_nginx:
  volumes:
    - ./app:/opt/app
    - ./nginx.conf:/etc/nginx/conf.d/default.conf
  ports:
    - "80:8080"
  depends_on:
    - blackjack_php
  restart: always
```

Deze container bevat [Nginx](https://www.nginx.com/), een webserver.

Ook hier hebben we een versienummber aangegeven, de 1.25.2 versie om precies te zijn. En ook hier maken we gebruik van een image die gebaseerd is op alpine linux.

Wel zien we hier een paar nieuwe configuratie opties:

```yaml
  volumes:
    - ./app:/opt/app
    - ./nginx.conf:/etc/nginx/conf.d/default.conf
```

Naast de inhoud van de`./app` map hebben we nu ook het `./nginx.conf` bestand in de container gezet. Deze bevat de configuratie voor nginx.

```yaml
  ports:
    - "80:8080"
```

Hiermee kunnen we het netwerk configureren. Nginx luistert standaard naar poort 8080. Met de `ports` optie 'mappen' we de lokale poort `80` naar `8080` in de container. Omdat `80` de standaard-poort is voor http (zonder SSL) kunnen we straks dus de webpagina bezoeken.

En als laatste hebben we deze optie:

```yaml
  depends_on:
    - blackjack_php
```

Hiermee vertellen we docker compoes dat de Nginx container de PHP container nodig heeft om zelf te kunnen werken.

## Docker compose starten

Om de applicatie nu te starten moet je eerst de composer dependencies installeren. Dit doe je voor nu nog even lokaal, en niet via Docker.

Zorg ervoordat je [composer geinstalleerd hebt](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).

Open nu de map `/app` en voer `composer install` uit. Je output zal er ongeveer uitzien als dit:

```shell
➜  blackjack2.0-docker git:(master) ✗ cd app 
➜  app git:(master) ✗ composer install
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Warning: The lock file is not up to date with the latest changes in composer.json. You may be getting outdated dependencies. It is recommended that you run `composer update` or `composer update <package name>`.
Package operations: 26 installs, 0 updates, 0 removals
  - Installing sebastian/version (4.0.1): Extracting archive
  - Installing sebastian/type (4.0.0): Extracting archive
  - Installing sebastian/recursion-context (5.0.0): Extracting archive
  - Installing sebastian/object-reflector (3.0.0): Extracting archive
  - Installing sebastian/object-enumerator (5.0.0): Extracting archive
  - Installing sebastian/global-state (6.0.1): Extracting archive
  - Installing sebastian/exporter (5.0.0): Extracting archive
  - Installing sebastian/environment (6.0.1): Extracting archive
  - Installing sebastian/diff (5.0.3): Extracting archive
  - Installing sebastian/comparator (5.0.1): Extracting archive
  - Installing sebastian/code-unit (2.0.0): Extracting archive
  - Installing sebastian/cli-parser (2.0.0): Extracting archive
  - Installing phpunit/php-timer (6.0.0): Extracting archive
  - Installing phpunit/php-text-template (3.0.0): Extracting archive
  - Installing phpunit/php-invoker (4.0.0): Extracting archive
  - Installing phpunit/php-file-iterator (4.0.2): Extracting archive
  - Installing theseer/tokenizer (1.2.1): Extracting archive
  - Installing nikic/php-parser (v4.17.1): Extracting archive
  - Installing sebastian/lines-of-code (2.0.0): Extracting archive
  - Installing sebastian/complexity (3.0.0): Extracting archive
  - Installing sebastian/code-unit-reverse-lookup (3.0.0): Extracting archive
  - Installing phpunit/php-code-coverage (10.1.3): Extracting archive
  - Installing phar-io/version (3.2.1): Extracting archive
  - Installing phar-io/manifest (2.0.3): Extracting archive
  - Installing myclabs/deep-copy (1.11.1): Extracting archive
  - Installing phpunit/phpunit (10.3.2): Extracting archive
Generating autoload files
23 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

```

Nu kan je met `docker-compose` de applicatie starten. Zorg dat Docker geinstalleerd is en draait.

Voer nu het volgende uit:

```shell
➜  app git:(master) ✗ cd ../
➜  blackjack2.0-docker git:(master) ✗ docker-compose up -d
WARNING: Found orphan containers (my_nginx_server, my_php_app) for this project. If you removed or renamed this service in your compose file, you can run this command with the --remove-orphans flag to clean it up.
Creating blackjack20-docker_blackjack_php_1 ... done
Creating blackjack20-docker_blackjack_nginx_1 ... done
```

Je kunt nu het volgende commando gebruiken om de logs van beide containers te zien:

```shell
➜  blackjack2.0-docker git:(master) ✗ docker-compose logs -f
```

De -f flag staat voor `follow`, en zorgt ervoor dat nieuwe log-lines automatisch op je scherm komen.

Als alles goed gegaan is zie je in de output onder andere de volgende regels:

```shell
blackjack_php_1    | [07-Sep-2023 13:19:46] NOTICE: ready to handle connections

...

blackjack_nginx_1  | 2023/09/07 13:19:47 [notice] 1#1: start worker processes
```

Deze geven aan dat zowel de PHP container als de Nginx container klaar staan om verzoeken af te handelen.

Open nu je browser en ga naar `http://localhost` om de website te zien!