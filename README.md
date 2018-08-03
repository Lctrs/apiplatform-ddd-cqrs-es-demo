DDD + CQRS + Event Sourcing + API Platform
==========================================

Demo application wiring up some concepts and libraries :
- DDD
- CQRS
- Event Sourcing
- API Platform
- Prooph

Install
=======

    $ git clone https://github.com/Lctrs/lctrs/apiplatform-ddd-es-demo.git
    $ composer install
    $ docker-compose run php bin/console event-store:event-stream:create
    $ docker-compose up -d

And go to https://localhost.
