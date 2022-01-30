# BusiNext - Business Management Solution

BusiNext was originally develop for the used of Bytes Crafter client ticketing and project management solution.

## Installation

1. Copy to your target public_html then open it on your browser.
2. You will be promt with installation page, complete it.
3. You can now login using your admin account from previous step.
4. Important! Dont for =get to update database on http://localhost/migrate/update.

## CI / CD Standards

Please add this command on git automatic deployment.

```
rm -R install
rm -R documentation
rm -R .env.backup.php
chmod 600 .env.production.php
```

## Issues on Plesk with JWT
- Add this to the top of the .htaccess
```
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
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
