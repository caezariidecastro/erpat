# BusiNext - Business Management Solution

BusiNext was originally develop for the used of Bytes Crafter client ticketing and project management solution.

## CI / CD Standards

Please add this command on git automatic deployment.

```
#rm -R install #Uncomment after install
rm -R documentation
mv -n .env.backup .env.production
rm -R .env.backup
chmod 600 .env.production
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
