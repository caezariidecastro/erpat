# BusiNext - Business Management Solution

BusiNext was forked from ERPat project of Bytes Crafter. The goal of this project is to create a system that has a modularized feature. Meaning, any data management that needs a system can be integrated to this project by modules. Let's make a system that intelegently adopt to whatever the system administrator want it to be. 

## Setup & Migration

### Migration from CI3 to CI4, setting up and synching.

- For initial setup: Execute the following command to download the necessary composer vendors.
```
composer update
```

- For database migration: First, you need to copy env to .env and you need to configure your database configuration. Now, you can execute the following command to migrate database.
```
php spark migrate
```

- To create or edit new migration table, you need to execute the command. to create that migrate and edit it to your table structure.
```
php spark migrate:create tablename
``` 

- To create or insert demo data to your tables, you can use the following commands.
```
php spark make:seeder Accounts //create file
php spark db:seed Accounts //insert
```

## Contributing

Please read [CONTRIBUTING](CONTRIBUTING) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com//BytesCrafter/BusiNext/tags). 

## Authors

* **Bytes Crafter** - *Initial work* - [Github](https://github.com/BytesCrafter)

See also the list of [contributors](https://github.com//BytesCrafter/BusiNext/graphs/contributors) who participated in this project.

## License

This project is licensed under the GNU GPL License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* CodeIgniter
* MariaDB Developer
