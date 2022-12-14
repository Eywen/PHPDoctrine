MiW: Doctrine - Gesti贸n de Resultados
======================================

[![MIT license](http://img.shields.io/badge/license-MIT-brightgreen.svg)](http://opensource.org/licenses/MIT)
[![Minimum PHP Version](https://img.shields.io/badge/php-%5E7.4-blue.svg)](http://php.net/)

> 馃幆 Ejemplo ORM Doctrine

Para desarrollar una sencilla gesti贸n de datos se ha utilizado
el ORM [Doctrine][doctrine]. Doctrine 2 es un Object-Relational Mapper que proporciona
persistencia transparente para objetos PHP. Utiliza el patr贸n [Data Mapper][dataMapper]
con el objetivo de obtener un desacoplamiento completo entre la l贸gica de negocio y la
persistencia de los datos en los sistemas de gesti贸n de bases de datos.

Para su configuraci贸n, este proyecto se apoya en el componente [Dotenv][dotenv], que
permite realizar la configuraci贸n a trav茅s de variables de entorno. De esta manera,
cualquier configuraci贸n que pueda variar entre diferentes entornos puede ser establecida
en variables de entorno, tal como se aconseja en la metodolog铆a [鈥淭he twelve-factor app鈥漖[12factor].

## 馃洜锔? Instalaci贸n de la aplicaci贸n

El primer paso consiste en generar un esquema de base de datos vac铆o y un usuario/contrase帽a con privilegios completos sobre dicho esquema.

A continuaci贸n se deber谩 crear una copia del fichero `./.env` y renombrarla
como `./.env.local`. Despu茅s se debe editar dicho fichero y modificar las variables `DATABASE_NAME`,
`DATABASE_USER` y `DATABASE_PASSWD` con los valores generados en el paso anterior (el resto de opciones
pueden quedar como comentarios). Una vez editado el anterior fichero y desde el directorio ra铆z del
proyecto se deben ejecutar los comandos:
```
$ composer update
$ ./bin/doctrine orm:schema-tool:update --dump-sql --force
```
Para verificar la validez de la informaci贸n de mapeo y la sincronizaci贸n con la base de datos:
```
$ ./bin/doctrine orm:validate-schema
```

## 馃梽锔? Estructura del proyecto:

A continuaci贸n se describe el contenido y estructura del proyecto:

* Directorio `/bin`:
    - Ejecutables (*doctrine* y *phpunit*)
* Directorio `/config`:
    - `config/cli-config.php`: configuraci贸n de la consola de comandos de Doctrine
* Directorio `/src`:
    - Subdirectorio `src/Entity`: entidades PHP (incluyen atributos de mapeo del ORM)
    - Subdirectorio `src/scripts`: scripts de ejemplo
* Directorio `/public`:
    - Ra铆z de documentos del servidor web
    - `public/index.php`: controlador frontal
* Directorio `/tests`:
    - Pruebas unitarias y funcionales de la API
* Directorio `/vendor`:
    - Componentes desarrollados por terceros (Doctrine, Dotenv, etc.)

## 馃殌 Puesta en marcha de la aplicaci贸n

Para acceder a la aplicaci贸n utilizando el servidor interno del int茅rprete
de PHP se ejecutar谩 el comando:
```
$ php -S 127.0.0.1:8000 -t public
```

Una vez hecho esto, la aplicaci贸n estar谩 disponible en [http://127.0.0.1:8000/][lh].

## 馃攷 Ejecuci贸n de pruebas

La aplicaci贸n incorpora un conjunto completo de herramientas para la ejecuci贸n de pruebas 
unitarias y de integraci贸n con [PHPUnit][phpunit]. Empleando este conjunto de herramientas
es posible comprobar de manera autom谩tica el correcto funcionamiento de las entidades
sin la necesidad de herramientas adicionales.

Para configurar el entorno de pruebas se debe crear una copia del fichero `./phpunit.xml.dist`
y renombrarla como `./phpunit.xml`. A continuaci贸n se debe editar dicho fichero y modificar los
mismos par谩metros (`DATABASE_NAME`, `DATABASE_USER` y `DATABASE_PASSWD`) que en la fase de
instalaci贸n con los valores apropiados. Para lanzar la suite de pruebas se debe ejecutar:
```
$ ./bin/phpunit [--testdox] [--coverage-text] [-v]
```

[12factor]: https://www.12factor.net/es/
[dataMapper]: http://martinfowler.com/eaaCatalog/dataMapper.html
[doctrine]: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/
[dotenv]: https://packagist.org/packages/vlucas/phpdotenv
[lh]: http://127.0.0.1:8000/
[phpunit]: http://phpunit.de/manual/current/en/index.html
