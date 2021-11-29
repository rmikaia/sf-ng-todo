# sf-ng-todo
This is a demo project for a todo list with:
- Symfony as backend and api manager
- Doctrine as ORM
- [Lexik/JWT](https://github.com/lexik/LexikJWTAuthenticationBundle) as auth and jwt management
- Docker and docker-compose for containerization
- and github Action for CI/CD pipeline

This app is just an api for todo:
- List
- Creation / Update
- Delete
- Registration / Auth

We will then get json as response.

Furthermore, we can implements UI using differents kind of techno:
- React
- Vuejs
- Angular

# Installation
This project requires that you have docker and docker-compose installed. 
```bash
git clone https://github.com/rmikaia/sf-todo.git
cd sf-todo
# configure your .env.local with necessary env variables (database, token, ...)
docker-compose up -d
```
