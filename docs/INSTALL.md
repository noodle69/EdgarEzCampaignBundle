# EdgarEzCampaignBundle

## Installation

### Get the bundle using composer

Add EdgarEzCampaignBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-campaign-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Welp\MailchimpBundle\WelpMailchimpBundle(),
        new Edgar\EzUISitesBundle\EdgarEzUISitesBundle(),
        new Edgar\EzCampaignBundle\EdgarEzCampaignBundle()
        // ...
    );
}
```

## Add routing

Add to your global configuration app/config/routing.yml

```yaml
edgar.ezcampaign.ui:
    resource: '@EdgarEzCampaignBundle/Resources/config/routing_ui.yml'
    prefix: /_campaign
    defaults:
        siteaccess_group_whitelist: 'admin_group'

edgar.ezcampaign:
    resource: '@EdgarEzCampaignBundle/Resources/config/routing.yml'
    prefix: /_campaign
```

## (optional) Add specific configuration

in your ezplatform.yml, add folowing configuration
```yaml
edgar_ez_campaign:
    system:
        default:
            camapgin_view: 'campaign # defined wich content_view is used to create campaign content
            pagination:
                campaign_limit: 10
                list_limit: 10
```
