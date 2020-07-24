# php-lndconnect

[![](https://img.shields.io/badge/project-LND-blue.svg?style=flat-square)](https://github.com/lightningnetwork/lnd)
[![standard-readme compliant](https://img.shields.io/badge/standard--readme-OK-green.svg?style=flat-square)](https://github.com/RichardLitt/standard-readme)

> Generate and parse lndconnect uris https://github.com/LN-Zap/lndconnect ⚡️

This package provides utilities for generating and parsing lndconnect uris in PHP.

For more information take a look at the [specification of the uri format](https://github.com/LN-Zap/lndconnect/blob/master/lnd_connect_uri.md).

## Table of Contents

- [Install](#install)
- [Usage](#usage)
- [Maintainers](#maintainers)
- [Contribute](#contribute)
- [License](#license)

## Install

```
composer require lnpay/lndconnect
```

## Usage

**LndConnect::format($host,$encoded_cert,$base64url_macaroon);**

Formats a host / cert / macaroon combo into an lndconnect link.

```php
use lnpay\LndConnect;

LndConnect::format('127.0.0.1:10009','MIICuDCCAl...','AgEDbG5kAus...');

//lndconnect://127.0.0.1:10009?cert=MIICuDCCAl...&macaroon=AgEDbG5kAus...')
```

**LndConnect::encode($host,$raw_cert,$macaroon_hex);**

Encodes a host / cert / macaroon combo and formats into an lndconnect link.

```php
use lnpay\LndConnect;

LndConnect::encode('127.0.0.1:10009','-----BEGIN CERTIFICATE-----...','0201036c6...');

//lndconnect://127.0.0.1:10009?cert=MIICuDCCAl...&macaroon=AgEDbG5kAus...')

```

**LndConnect::decode($lndconnect_uri);**

Decodes an lndconnect link into it's component parts (host / cert as utf8 / macaroon as hex)

```php
use lnpay\LndConnect;

LndConnect::decode('lndconnect://127.0.0.1:10001?cert=MIICDjCCAbSgAwI&macaroon=AgEDbG5');

/*
 * [
 *   'host' => '127.0.0.1:10001',
 *   'cert => '-----BEGIN CERTIFICATE-----.....',
 *   'macaroon'=>'0201036c6....'
 * ]
 */
```

#### Certificate

**LndConnect::encodeCert($raw_cert):**

Encodes a certificate string to base64url encoded DER format.

```php
use lnpay\LndConnect;

LndConnect::encodeCert('-----BEGIN CERTIFICATE-----\n.....');

//MIICDjCCAbSgAwI
```

**LndConnect::decodeCert($lndconnect_cert):**

Decodes a certificate from base64url encoded DER format to a string.

```php
use lnpay\LndConnect;

LndConnect::decodeCert('MIICDjCCAbSgAwI');

//-----BEGIN CERTIFICATE-----\n.....
```

#### Macaroon

**LndConnect::encodeMacaroon($macaroon_hex):**

Encodes a binary macaroon hex to base64url encoded string.

```php
use lnpay\LndConnect;

LndConnect::encodeMacaroon('0201036c6...');

//AgEDbG5kAus...
```

**LndConnect::decodeMacaroon($lndconnect_macaroon):**

Decodes a base64url encoded macaroon to a hex encoded macaroon.

```php
use lnpay\LndConnect;

LndConnect::decodeMacaroon('AgEDbG5kAus...');

//0201036c6...
```

### Testing

Run the tests suite:

```bash
  vendor/bin/phpunit
```

## Maintainers

[Tim Kijewski (tkijewski)](https://github.com/tkijewski).

## Contribute

Feel free to dive in! [Open an issue](https://github.com/lnpay/php-lndconnect/issues/new) or submit PRs.

lndconnect follows the [Contributor Covenant](http://contributor-covenant.org/version/1/3/0/) Code of Conduct.

## License

[MIT](LICENSE) © Tim Kijewski