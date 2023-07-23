# Mage2 Module ML DeveloperTest

    ``ml/module-developertest``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities

#### Magento 2 GeoIP Product Restriction Module
This is a Magento 2 module developed to demonstrate the understanding of the platform.
The module aims to restrict certain products based on the customer's country using the 'IP 2 Country GeoIP' service (https://ip2country.info).

## Installation

To install the module, follow the steps below:

### Type 1: Zip file

 - Unzip the zip file in `app/code/ML`
 - Enable the module by running `php bin/magento module:enable ML_DeveloperTest`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require ml/module-developertest`
 - enable the module by running `php bin/magento module:enable ML_DeveloperTest`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

Once the module is installed, you can access the module configuration in the admin panel:

1. Log in to your Magento admin panel.
2. Navigate to ``Stores`` -> ``Configuration.``
3. In the left panel, under Restricted Products Countries Settings, click on Restricted To Countries.

In the module configuration, you can:

* Enable or disable the overall functionality of the GeoIP product restriction.
* Select the countries to which the products will be restricted in the product edit page.
* Edit the message shown to customers when they cannot order a restricted product. 
* The message can be customized to include the name of the customer's country using the variable {COUNTRY_NAME}.
* Set the API URL and API Key for the IP 2 Country GeoIP service.

## Specifications

The GeoIP Product Restriction module provides the following functionality:

1. **Product-Level Country Blocking:** In the Magento admin, while editing a product, you can select the countries from which the product should be blocked. This setting allows you to restrict the product from being ordered from specific countries.

2. **GeoIP Lookup:** The module uses the 'IP 2 Country GeoIP' service to obtain the customer's country based on their IP address.

3. **Cart Product Validation:** When a product is added to the cart, the module performs a check to determine if the customer can order the product based on their country and the product-level country blocking settings.

4. **Customer Notification:** If the customer cannot order a product due to country restrictions, a message will be displayed using the standard built-in notices functionality. The message will be in the format: "I’m sorry, this product cannot be ordered from {COUNTRY_NAME}."


## Attributes

 - Product - restrictedCountry (restricted_country)

##  Assumptions and Decisions
1. We have implemented a configuration setting to enable/disable the GeoIP product 
   restriction functionality. This allows store administrators to easily control the feature based on their business requirements.
2. The module uses the 'IP 2 Country GeoIP' service to obtain the customer's country. 
   The service may have rate limits or usage restrictions, so it is essential to comply with their terms of use and 
   handle any potential errors or exceptions gracefully.
3. To ensure compatibility with future Magento versions, the module has been developed following Magento's coding 
   standards and best practices.
4. The module has been designed as a standalone extension and can be installed using composer.
5. The vendor name is set to ML, and the extension name is set to DeveloperTest, as per the provided instructions.
6. The message shown to customers when they cannot order a restricted product is customizable 
   using the {COUNTRY_NAME} variable, which will be replaced with the actual name of the customer's country.
7. The latest version of Magento Open Source has been used for development to ensure compatibility and access to the latest features
