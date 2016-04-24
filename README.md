# Hogosha Monitor

Monitoring tool for [Hogosha Service](https://github.com/hogosha/hogosha) that can be used as a standalone tool as a simple ping program.

**Currently in development but ready to use in production**

[![Build Status](https://img.shields.io/travis/hogosha/monitor.svg?branch=master&style=flat-square)](https://travis-ci.org/hogosha/monitor) [![Code Quality](https://img.shields.io/scrutinizer/g/hogosha/monitor.svg?b=master&style=flat-square)](https://scrutinizer-ci.com/g/hogosha/monitor/?branch=master) [![Code Coverage](https://img.shields.io/coveralls/hogosha/monitor.svg?style=flat-square)](https://coveralls.io/r/hogosha/monitor) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/87bbdd85-2cd8-4556-94c6-5ed9f501cf7d/mini.png)](https://insight.sensiolabs.com/projects/87bbdd85-2cd8-4556-94c6-5ed9f501cf7d) [![Latest Unstable Version](https://poser.pugx.org/hogosha/monitor/v/unstable)](https://packagist.org/packages/hogosha/monitor)
[![Latest Stable Version](https://poser.pugx.org/hogosha/monitor/v/stable)](https://packagist.org/packages/hogosha/monitor)

## Installation

### Composer

#### Globally

```bash
composer global require hogosha/monitor
```

If it is the first time you globally install a dependency then make sure
you include `~/.composer/vendor/bin` in $PATH as shown [here](http://getcomposer.org/doc/03-cli.md#global).

Keep Hogosha Monitor up-to-date

```bash
composer global update hogosha/monitor
```

#### In your project

```bash
composer require hogosha/monitor
```

### .phar

Download the last PHAR file from the release panel

```bash
VERSION=master; curl -LO "https://raw.githubusercontent.com/hogosha/monitor/$VERSION/build/monitor.phar"
chmod u+x monitor.phar
mv monitor.phar /usr/local/bin/hogosha-monitor
```

Or build it manually

```bash
git clone https://github.com/hogosha/monitor.git
composer install
bin/compile
mv monitor.phar /usr/local/bin/hogosha-monitor
```

## Usage

Create the configuration file:

```bash
hogosha-monitor init
```

or change the directory

```bash
hogosha-monitor init -c $HOME
```
This will create an yml file with this configuration:

```yaml
urls:
    google:
        url: 'https://www.google.fr'
        method: GET
        headers: { Accept: text/html }
        timeout: 1
        validator: { }
        status_code: 200 #status expected
```

Then run the tool

```bash
hogosha-monitor run -c $HOME
```

**Output - List**

```bash
[OK][200] google - 0.267724
```

**Output - Table**

```bash
+---------------+-------------+---------+---------------+----------------+---------------------+
| Global Status | Status Code | Name    | Response Time | Http Error Log | Validator Error Log |
+---------------+-------------+---------+---------------+----------------+---------------------+
| OK            | 200         | thefork | 0.274106      |                |                     |
+---------------+-------------+---------+---------------+----------------+---------------------+
```

**Output - Csv**

```bash
"Global Status","Status Code",Name,"Reponse Time","Http Error Log","Validator Error Log"
OK,200,thefork,0.307092,,
```

## Validator

Sometimes we need more than just status codes. So we introduced a validator system for **html**, **xml** and **json** so you can test a existing string. 

**Html Validator**

```yaml
urls:
    google:
        url: 'https://www.google.fr'
        method: GET
        headers: { Accept: text/html }
        timeout: 1
        validator:
        	type: html
        	match: /images/ #regex will be 
        status_code: 200 #status expected
```

**Json Validator**

Json example:

```json
{"name": "Chuck Norris"}
```

```yaml
urls:
    google:
        url: 'https://www.example.org/names.json'
        method: GET
        headers: { Accept: text/json }
        timeout: 1
        validator:
        	type: json
        	match: name #we use property accessor
        status_code: 200 #status expected
```

**Xml Validator**

Xml example:

```xml
<?xml version="1.0"?>
<root>
    <name>chuck</name>
</root>
```

```yaml
urls:
    google:
        url: 'https://www.example.org/test.xml'
        method: GET
        headers: { Accept: text/xml }
        timeout: 1
        validator:
        	type: xml
        	match: //name #use xpath selector
        status_code: 200 #status expected
```

## Connect to hogosha portal
Once you have configured the hogosha portal, you need to add this configuration in your yaml

```yaml
urls:
    google:
        url: 'https://www.google.fr'
        method: GET
        headers: { Accept: text/html }
        timeout: 1
        status_code: 200 #status expected
        metric_uuid: ec9e5ba7-9136-4b67-b049-a969e8e6dcde # Metric uuid you can find in the hogosha portal
        service_uuid: 9e231852-5343-4653-81c8-f52ee223d3d2 # Service monitored by the hogosha portal

hogosha_portal:
  username: admin
  password: admin
  base_uri: http://localhost:8000/api/
  metric_update: true # Update metric graph
  incident_update: true #create an incident when there is a problem and update it when there is one to resolve
  default_failed_incident_message: "An error as occured, we are investigating %service_name%",
  default_resolved_incident_message: "The service %service_name% is back to normal"
```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## TODO
- [x] Connnect to the hogosha portal
- [x] Collect metric to the hogosha portal metric system
- [x] Automatic incident update when failing
- [x] Automatic service resolver
- [ ] Refacto

## Credits

Thanks to Silex project for the compiler file.

## License

Hogosha Monitor is licensed under the MIT License - see the LICENSE file for details
