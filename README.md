## PHP Docker Boilerplate for MAMIAS web App

![License MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)

This is an easy customizable docker boilerplate for MAMIAS web Application  PHP-based projects.

Supports:

- Nginx
- PHP-FPM (with Xdebug)
- Postgresql+PostGIS
- MailHog
- Traefik

Laravel and Filament are used to develop the MAMIAS Web Application.

This Docker boilerplate is based on the [Docker best practices](https://docs.docker.com/articles/dockerfile_best-practices/) and doesn't use too much magic. Configuration of each docker container is available in the `docker/` directory - feel free to customize.

*Warning: There may be issues when using it in production.*

## Table of contents

- [First steps / Installation and requirements](/documentation/INSTALL.md)
- [Updating docker boilerplate](/documentation/UPDATE.md)
- [Customizing](/documentation/CUSTOMIZE.md)
- [Services (Webserver, MySQL... Ports, Users, Passwords)](/documentation/SERVICES.md)
- [Docker Quickstart](/documentation/DOCKER-QUICKSTART.md)
- [Run your project](/documentation/DOCKER-STARTUP.md)
- [Container detail info](/documentation/DOCKER-INFO.md)
- [Troubleshooting](/documentation/TROUBLESHOOTING.md)
- [Changelog](/CHANGELOG.md)
