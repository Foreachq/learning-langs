# Learning Languages

«Learning Languages» — a site where you can learn new languages.

## Description

Application is build in the form of a site where you can add new words for learning, make it active/inactive and learn words with smart algorithm of words displaying. Registration and authentication are required to work with the system.

Project features:
- Authentication, policy management;
- Additing words for learning on the learning page;
- PostgreSQL storage for added entities;
- Docker containerization for easy to run local instances.

## Requirements

- docker-compose

## Installation

- Download package

``` bash
git clone https://github.com/Foreachq/learning-langs
```

- Setup project

```bash
cp .env.dist .env
```

- Run local instance

```bash
make docker-up    # starting on http://127.0.0.1:8041
```

- Stop local instance

``` bash
make docker-down
```

You can get into php container with:

```bash
make php
```

