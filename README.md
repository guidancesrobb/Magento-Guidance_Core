[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/guidancesrobb/Magento-Guidance_Core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/guidancesrobb/Magento-Guidance_Core/?branch=master)

Magento Guidance Core Module
==============================

# Features

* Magento Audit
    * Displays statistics about catalog, customer, order, etc size
    * Details products by type
    * Shows information about modules (code pools, rewrites)
* Database Logging
	* Extend your helper from `Guidance_Core_Helper_Abstract` and define `$_moduleName`.  Use `$this->log($message, $logLevel)` to log to the database
	* Easier to filter, sort, and check logs when needed, instead of reading the file system

# Screenshots

![Audit](http://i.imgur.com/MK2o07k.png)

---

![Logs](http://i.imgur.com/5LaShdj.png)

# Installation

1. Copy the contents of src/ to your Magento installation
2. Clear Magento caches
3. Log out of admin

## Installation with Modman

    cd /path/to/magento/
    modman init
    modman clone https://github.com/guidancesrobb/Magento-Guidance_Core.git

# License

Licensed under the Apache License, Version 2.0