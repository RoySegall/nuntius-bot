One way to communicate with Nuntius is via text. First, let's have a look at the
`hooks.yml` file:
```yml
tasks:
  reminders: '\Nuntius\Tasks\Reminders'
  help: '\Nuntius\Tasks\Help'
  introduction: '\Nuntius\Tasks\Introduction'
```

The tasks plugin needs to declare to which text it needs to response AKA scope:

There two types of plugins:
1. Black box task - A task that needs arguments, or not, and does a simple job: 
set a reminder for later.
2. Conversation task - A task which depends on information and can get it by
asking the user a couple of questions. Each conversation task has a 
conversation scope:
  * Forever - a scope that likely won't change in the near future: List of the
  user's team members.
  * Temporary - A scope that we don't need to keep forever: What you want to eat
  for lunch. **But** a temporary scope may not be relevant forever but we might
  want to use in the future. We would likely want to keep the places the user
  invited food from so we could suggest that in the past.
