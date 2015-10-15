0.5.0
------
- Add delivery note support
- Add payment support e.g. Paypal Express
- Add support for Measurement Units
- Add "is_default" support for currency, language, customer group
- Fix multiple tax rates per tax class issue
- Fix HTML encoding of names and descriptions
- Refactor gerneric pulls in image, order item and payment
- Remove all constant expression to work with PHP version 5.4
- Delete obsolete options after push
- Fix currency update
- Fix measurement unit update
- Fix issue that global options with less values overwrites the one with more values
- Remove complicate image folder structure to reduce complexity
- Change language iso 2 letter to 3 letter
- Refactor product variation types
- Fix system independent directory separator issue
- Automatic password generation of connector

0.3.0
------
- Add top products support
- Add support for specifics
- Make variations global
- Use sku as article number instead of model
- Show requirements of the connector in the modules page
- Fix existing image pull issue
- Implement tax zone and tax zone country
- Implement file upload
- Implement product checksum
- Finish and integrate build process with phar
- Add currency pull support

0.2.0
------
- Update the payment status of an order
- Fix the deletion of images and articles before every sync
- Fix null reference of items without a language identification
- Fix hardcoded database credentials error- type checks