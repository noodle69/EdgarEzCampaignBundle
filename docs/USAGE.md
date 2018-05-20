# EdgarEzCampaignBundle

## Usage

### MailChimp account

Create a MailChimp account and generate and API key you would register in your config.yml

```yaml
welp_mailchimp:
    api_key: %your.api.key.here%
```

You will find MailChimp reference here:

https://kb.mailchimp.com/integrations/api-integrations/about-api-keys

### Campaign content view

You will define content_view for content wou will use as campaign content, example
If you have defined in your ezplatform.yml, this configuration

```yaml
edgar_ez_campaign:
    system:
        default:
            camapgin_view: 'campaign # defined wich content_view is used to create campaign content
```

and want to use "blog_post" content as campaign content, just define a content_view "campaign

```yaml
ezpublish:
    system:
        site_group:
            content_view:
                campaign:
                    blog_post:
                        template: "@ezdesign/campaign/blog_post.html.twig"
                        match:
                            Identifier\ContentType: [blog_post]
```

now, create a twig template like this

```twig
{% extends "@ezdesign/full/blog_post.html.twig" %}

{% block header %}{% endblock %}
{% block footer %}{% endblock %}
```

it will just remove header/footer from your content

### Subscription form

A new fieldtype named "Campaign" is used to let you choose wich campaign to uuse for tour site and display in front a subscription form.

* First: Edit a ContentType and add a new FieldType name "Campaign" with identifier "campaign"

* Second: in content view twig template of your ContentType you have edited, just add following code

```twig
{% trans_default_domain 'edgarezcampaign' %}

<div class="ez-notification-container">
    <div class="alert">
        {% if app.request.query.has('subscribed') %}
            {{ 'edgar.campaign.subscribed'|trans|default('Subscription successfull!') }}
        {% endif %}
        {% if app.request.query.has('subscribe_error') %}
            {{ 'edgar.campaign.subscribe.error'|trans|default('Subscription failed!') }}
        {% endif %}
    </div>
</div>
{{ ez_render_field(content, 'campaign') }}
```

Now, if you edit a Content having "Campgign" FieldType, you can select which campaign to use (if you have already create a MailChimp Campaign).
