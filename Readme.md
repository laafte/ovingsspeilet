# Øvingsspeilet

Dette er versjon 2 av øvingsspeilet til internt bruk for Studentersamfundets Orkesters medlemmer.


## Setup

- Installer dependencies for å bygge ved å kjøre `npm install`
- Bygg siden ved å kjøre `make`
- Koppier `config.sample.php` til `www/config.php`
- Fyll inn `www/config.php` med riktig info
- Databasestruktur finnes i `ovingsspeilet.sql`
- Pek web serveren på www mappen. Ingen andre filer er nødvendig på deployserver.


## TODO

- Flytt eksterne hostede JavaScript dependencies til en egen vendor mappe når ting har stabilisert seg.
- Kun bruk de delene av JS bibloteker som faktisk trengs.

## Lisens

MIT
