We will begin by downloading the [Nuntius Drupal integration](https://github.com/RoySegall/nuntius-drupal-integration)
and enable it.

Now, under `admin/config/system/nuntius` we will set the settings in the next 
form:
![Setup](../../images/drupal_usecase/image1.png)

Let's go over the settings:
* Nuntius token - this is a token which help us validate the incoming webhooks.
* Nuntius slack address - Drupal will send information to this end point. 
* Nuntius facebook address - Same as the above but oriented for Facebook 
Messenger.
* Slack room - The target slack room to post the messages.

_Don't forget to add Nuntius user as a member of that room._
