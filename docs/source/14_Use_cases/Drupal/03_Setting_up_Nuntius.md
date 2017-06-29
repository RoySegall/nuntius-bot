After setting up Drupal, let's set up Nuntius for that. We will start by editing
`hooks.local.yml` file:

```yml
entities:
  fb_reminders: '\Nuntius\Examples\Drupal\FbReminders'

# List of updates.
updates:
  2: '\Nuntius\Examples\Drupal\AddFbReminder'

# List of webhooks and te matcher handler.
webhooks_routing:
  'facebook': '\Nuntius\Examples\Drupal\DrupalExampleFacebook'
  'drupal': '\Nuntius\Examples\Drupal\Drupal'
  'facebook-drupal': '\Nuntius\Examples\Drupal\FacebookDrupal'

# Manage tasks.
tasks:
  fb_manage_updates: '\Nuntius\Examples\Drupal\FacebookUpdatesManage'
```

What's going on there?
1. We added a new Entity to store the recipient IDs of the Facebook Messenger 
accounts we need to update.
2. We created an update path to create the table of that entity.
3. We specify the webhooks routing.
4. We added a new Facebook task to handle the incoming text.

We also added the webhooks routing we defined in the 
[Setting up Drupal](Use_cases/Drupal/Setting_up_Drupal.html) section.

In the next part we need to the token which will help us validate the incoming
request. Update the `credentials.local.yml` by adding the Drupal token:
```yml
drupal_token: 'me'
```