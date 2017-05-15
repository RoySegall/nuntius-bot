All the managers(will be covered below) defined as Symfony services. In a
nutshell, a service is a class which implement a logic and can receive other
services as an argument and can be injected to other services as well. For more
info go to the [DependencyInjection Component](http://symfony.com/doc/current/components/dependency_injection.html)
documentation.


You can add as many services as you'd like to. As always, let's have a look at
the `hooks.yml` file:
```yml
services:
  - 'services.local.yml'
```

By default, you can add services in the `services.local.yml` but if you want to
manage that in other files you can add them in the `hooks.local.yml` file.
