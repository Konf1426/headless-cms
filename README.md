## Headless CMS

### Installation
1. Clone the repository
2. Run `cd headless-cms/` to navigate to the project directory
3. Create a `.env.local` file and set the database connection from `.env` file 
4. Run `composer install`
5. Run `php bin/console doctrine:database:create`
6. Run `php bin/console doctrine:migrations:migrate`
7. Run `php bin/console doctrine:fixtures:load`

Launch the server with `php bin/console server:start` and navigate to [API Documentation](https://127.0.0.1:8000/api) to see the available routes.

---

### Testing accounts
- ![Administrator](https://img.shields.io/badge/-ROLE_ADMIN-000000)
  - Mail : root@exemple.com
  - Password : root
---
- ![Administrator](https://img.shields.io/badge/-ROLE_ADMIN-000000)
  - Mail : root2@exemple.com
  - Password : root2
---
- ![User](https://img.shields.io/badge/-ROLE_USER-000000)
  - Mail : user@exemple.com
  - Password : user
---
- ![User](https://img.shields.io/badge/-ROLE_USER-000000)
  - Mail : user2@exemple.com
  - Password : user2

### Made with
![Symfony](https://img.shields.io/badge/Symfony-7.1-000000?style=flat-rounded&logo=Symfony&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-40436B?style=flat-rounded&logo=PHP&logoColor=white&labelColor=777BB4)
![MariaDB](https://img.shields.io/badge/MariaDB-4479A1?style=flat-rounded&logo=MariaDB)
![APIPlatform](https://img.shields.io/badge/API_Platform-4.0.2-054C4F?style=flat-rounded&logo=APIPlatform&logoColor=white&labelColor=00979D)

&circledast; <a href="https://github.com/ndnahel">ndnahel</a> - <a href="https://ndelaruelle.fr">ndelaruelle.fr</a>