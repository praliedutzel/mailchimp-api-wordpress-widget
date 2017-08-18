# mailchimp-api-wordpress-widget
A simple and customizable WordPress plugin that allows you to add a MailChimp subscription signup form as a widget to dynamic sidebars. It works with the MailChimp API, so you will need access to a MailChimp account, your API Key, the datacenter for your account, and the ID of the list you want to add new subscribers to.

## Installation
To install, upload the entire `mailchimp-subscription-widget` folder to the `/wp-content/plugins/` directory. You can then activate it in the Plugins section in WordPress.

## Settings
After activating the plugin, a new settings page will be added to the sidebar in the WordPress dashboard called "MailChimp Subscription". This page has a number of settings that the plugin requires in order to work.

### MailChimp API Settings
These fields are required in order to connect to your MailChimp account. The subscription form will not work without these settings.

| Setting Name | Description                                                   | Notes   |
| ------------ | ------------------------------------------------------------- | ------- |
| API Key      | This is what connects to your MailChimp account               | [Instructions to generate an API Key](http://kb.mailchimp.com/integrations/api-integrations/about-api-keys#Find-or-Generate-Your-API-Key) |
| Datacenter   | This is the datacenter for your MailChimp account             | This can be found at the beginning of the url when logged into MailChimp, and should begin with "us" followed by a number |
| List ID      | This is the ID of the list you want to add new subscribers to | This can be found in the "List name and campaign defaults" section of your list |

### Default Text Settings
These fields are the default text options for widgets. They can still be changed individually per widget.

| Setting Name | Description                                                           | Notes             |
| ------------ | --------------------------------------------------------------------- | ----------------- |
| Title        | The title that will be displayed above the form                       | Can be left blank |
| Description  | The description that will be displayed between the title and the form | Can be left blank |

### Form Submission Messaging Settings
These fields control the messaging that appears after a user submits a subscription form.

| Setting Name               | Description                                                                    |
| -------------------------- | ------------------------------------------------------------------------------ |
| Success Message            | The message that will appear if the user is successfully signed up to the list |
| Already Subscribed Message | The message that will appear if the user is already subscribed to the list     |
| Error Message              | The message that will appear if there is an error with the submission          |

## Basic Usage
Because this plugin allows you to add widgets to dynamic sidebars, your theme needs to support dynamic sidebars and at least one sidebar should be registered. If your theme does not have this support, the "Widgets" menu will not appear under the "Appearance" menu and you won't be able to use the plugin.

Once you have updated all of the plugin settings, navigate to the "Widgets" menu under "Appearance" in the WordPress dashboard. You should now see a widget called "MailChimp Signup Widget". Drag and drop the widget to the sidebars you want it to appear in. If you want, you can change the title and description fields individually per widget. Otherwise, they will use the default text settings.

## Theme Customization
There is no CSS included with the plugin, which allows you to theme the signup form to match your site with ease.

## Uninstallation
If you choose you no longer wish to use the plugin, simply deactivate and then delete it in the Plugins section in WordPress. After uninstalling, the widget and settings menu will be removed from WordPress, but the data you've entered is still stored in the database.

## Questions? Comments? Issues?
You can use the Issues tab at the top of the page to leave questions, bug lists, or feature requests. You can also tweet any comments at [@praliedutzel](http://twitter.com/praliedutzel). Thanks for checking this out!

> Current stable build: v1.0
