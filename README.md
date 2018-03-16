# User Guide
## Introduction

PHP SDK for ACM.

### Features
1. Get/Publish/Remove config from ACM server use REST API.
2. Local config cache supported.
3. Server failure failover.
4. TLS supported.
5. Address server supported.
6. Both Alibaba Cloud ACM and Stand-alone deployment supported.

### Supported PHP：

1. PHP 7.2

### Supported ACM version
1. ACM 1.0

### Change Logs
TODO

## Installation
TODO

## Getting Started
TODO

## Configuration
TODO

#### Extra Options
TODO

## API Reference

### Get Config
Get value of one config item following priority:

* Step 1 - Get from local cache with timestamp, if the cache value was expired, get from following sources and update local cache.
* Step 2 - Get from local failover dir(default: `${cwd}/acm/data`).
  * Failover dir can be manually copied from snapshot dir(default: `${cwd}/acm/snapshot`) in advance.
  * This helps to suppress the effect of known server failure.
* Step 3 - Get from one server until value is got or all servers tried.
  * Content will be save to snapshot dir after got from server.
* Step 4 - Get from snapshot dir.

### List All Config
Get all config items of current namespace, with dataId and group information only.
* Access local cache first, if local data was expired, request the data from server and update local cache correspondingly
* Warning: If there are lots of config in namespace, this function may cost some time.

### Publish Config
Publish one data item to ACM.
* If the data key is not exist, create one first.
* If the data key is exist, update to the content specified.
* Content can not be set to None, if there is need to delete config item, use function __remove__ instead.

### Remove Config
Remove one data item from ACM.

## Other Resources

* Alibaba Cloud ACM homepage: [https://www.aliyun.com/product/acm](https://www.aliyun.com/product/acm)

