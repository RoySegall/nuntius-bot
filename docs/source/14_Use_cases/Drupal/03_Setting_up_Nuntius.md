After setting up Drupal, let's set up Nuntius for that. We will start by editing
out `hooks.local.yml` file:

```yml
entities:
  fb_reminders: '\Nuntius\Examples\Drupal\FbReminders'

# List of updates.
updates:
  2: '\Nuntius\Examples\Drupal\AddFbReminder'

# List of webhooks and te matcher handler.
webhooks_routing:
  'drupal': '\Nuntius\Examples\Drupal\Drupal'
  'facebook-drupal': '\Nuntius\Examples\Drupal\FacebookDrupal'

```

What's going on there? We added a new Entity to store the recipient IDs of the
Facebook Messenger accounts we need to update and we created an update path
to create the table of that entity.

We also added the webhooks routing we defined in the 
[Setting up Drupal](Use_cases/Drupal/Setting_up_Drupal.html) section.

In the next part we need to the token which will help us validate the incoming
request. Update the `credentials.local.yml`:
```yml
drupal_token: 'me'
```