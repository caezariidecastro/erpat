# ERPat System - Enterprise Resource Planning for Small to Large Business

Enterprise Resource Planning (ERP) software offers an integrated and comprehensive solution tailored to meet the diverse needs of businesses, ranging from small enterprises to large corporations. With an intuitive interface and a robust feature set, our ERP system is designed to optimize and streamline various aspects of your business operations, enhancing efficiency, collaboration, and decision-making across departments.

## Key Features:

### Human Resources Management

Streamline HR processes, including recruitment, onboarding, payroll, and performance management. Our ERP system helps manage employee data, track training and certifications, and ensure compliance with labor regulations.

### Centralized Data Management

Our ERP software acts as a centralized repository for all your business data, eliminating data silos and enabling real-time access to information across functions such as finance, procurement, sales, inventory, HR, and more. This eliminates duplication, ensures data consistency, and enhances accuracy in reporting.

### Process Automation

Increase operational efficiency by automating routine and time-consuming processes. From order processing to invoice generation, our ERP system simplifies workflows, reduces manual errors, and accelerates task completion.

### Inventory Management

Keep track of inventory levels, manage stock movement, and optimize reorder points with ease. Our ERP software ensures that you have the right amount of inventory at the right time, minimizing stockouts and reducing carrying costs.

### Financial Management

Gain insights into your financial health through advanced accounting features, including accounts payable, accounts receivable, general ledger, and financial reporting. Generate accurate financial statements and track key performance indicators to make informed strategic decisions.

### Customer Relationship Management

Build stronger customer relationships by centralizing customer data and interactions. Our ERP system enables you to track customer interactions, manage sales leads, and enhance customer support, resulting in improved customer satisfaction and loyalty.

### Supply Chain Optimization

Optimize your supply chain by effectively managing suppliers, orders, and deliveries. Our ERP software enhances visibility into the entire supply chain, helping you make informed decisions to reduce lead times and improve supply chain resilience.

### Business Analytics

Leverage built-in analytics and reporting tools to derive actionable insights from your data. Our ERP solution offers customizable dashboards and reports, enabling you to monitor key performance metrics, identify trends, and make data-driven decisions.

### Scalability

Whether your business is small, growing, or well-established, our ERP software scales with your needs. You can easily add modules and functionalities as your business evolves, ensuring that the system remains aligned with your changing requirements.

### Security and Compliance

Safeguard sensitive business information with robust security features, including user authentication, access controls, and data encryption. Our ERP solution helps you maintain compliance with industry regulations and data privacy standards.

## Installation

1. Copy to your target public_html then open it on your browser.
2. Rename the .env.backup.php to .env.production.php
3. Edit the .env.production.php and set mysqli credentials.
4. Set ENVIRONMENT to 'production' and ENCRYPTION as any md5 hash.
5. You can now login using your admin account using default credentials.

### Default Credentials

```
Username: admin@erpat.app
Password: bytescrafter
```

## CI / CD Standards

Please add this command on git automatic deployment.

```
rm -rf install
rm -rf documentation
rm -f .env.backup.php
chmod 600 .env.production.php
```

### Issues on Plesk with JWT
- Add this to the top of the .htaccess
```
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

### Issues on MySQL slow query
- First we can truncate the ci_sessions table but will lgout users.
- For long term, you need to set this on your php config.
```
session.gc_probability = 1
session.gc_divisor = 1000
session.gc_maxlifetime = 1440
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
