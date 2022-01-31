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

    $ git clone https://github.com/Lctrs/apiplatform-ddd-cqrs-es-demo.git
    $ make up

And go to https://localhost.

Loading Fixtures
================

    $ docker-compose exec php bin/console app:fixtures:load ./fixtures
