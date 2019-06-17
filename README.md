# VatNumberCheck (Web Service) plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-vat-number-check-webservice.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-vat-number-check-webservice)
[![PHP 7 ready](http://php7ready.timesplinter.ch/Oefenweb/cakephp-vat-number-check-webservice/badge.svg)](https://travis-ci.org/Oefenweb/cakephp-vat-number-check-webservice)
[![Coverage Status](https://codecov.io/gh/Oefenweb/cakephp-vat-number-check-webservice/branch/master/graph/badge.svg)](https://codecov.io/gh/Oefenweb/cakephp-vat-number-check-webservice)
[![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-vat-number-check-webservice.svg)](https://packagist.org/packages/oefenweb/cakephp-vat-number-check-webservice)
[![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-vat-number-check-webservice/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-vat-number-check-webservice)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Oefenweb/cakephp-vat-number-check-webservice/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Oefenweb/cakephp-vat-number-check-webservice/?branch=master)

## Requirements

* CakePHP 2.9.0 or greater.
* PHP 7.0.0 or greater.

## Installation

Clone/Copy the files in this directory into `app/Plugin/VatNumberCheck`

## Configuration

Ensure the plugin is loaded in `app/config/app.php` by calling:

```php
CakePlugin::load('VatNumberCheck', ['routes' => true]);
```

Ensure to configure the following lines in `app/Config/database.php`:

```php
public $vatNumberCheckWebservice = [
	'datasource' => 'VatNumberCheck.SoapSource',
	'wsdl' => 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl'
];
```

## Usage

### Model

Normalizes a VAT number:

```php
$vatNumber = $this->VatNumberCheck->normalize($vatNumber);
```

Checks a given VAT number:

```php
$vatNumberValid = $this->VatNumberCheck->check($vatNumber);
```

### Helper

Generates a VAT number check form field:

```php
echo $this->VatNumberCheck->input('vat_number', ['label' => __('VAT number')]);
```
