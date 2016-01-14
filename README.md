# hogosha-monitor

Monitoring tool for [hogosha service](https://github.com/hogosha/hogosha) that can be used as a standalone tool as a simple ping program.

**Currently in development.**

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

Keep Monitor up-to-date

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
VERSION=master curl -LO "https://raw.githubusercontent.com/hogosha/monitor/$VERSION/build/monitor.phar"
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
        timeout: 1
        status_code: 200 #status expected
```

Then run the tool

```bash
hogosha-monitor run -c $HOME
```

**Output**

```bash
+--------+--------+---------------+
| Name   | Status | Response Time |
+--------+--------+---------------+
| google | 200    | 0.356444      |
+--------+--------+---------------+
```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## TODO
- [ ] Connnect to the hogosha portal
- [ ] Collect metric to the hogosha portal metric system

## Credits

Thanks to Silex project for the compiler file.

## License

Hogosha Monitor is licensed under the MIT License - see the LICENSE file for details
