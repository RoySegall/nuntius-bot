<?php

namespace Nuntius\Plugins;

class OnboardingConversation extends \Mpociot\BotMan\Conversation
{
  protected $firstname;

  protected $email;

  public function askFirstname()
  {
    $this->ask('Hello! What is your firstname?', function(\Mpociot\BotMan\Answer $answer) {
      // Save result
      $this->firstname = $answer->getText();

      $this->say('Nice to meet you '.$this->firstname);
      $this->askEmail();
    });
  }

  public function askEmail()
  {
    $this->ask('One more thing - what is your email?', function(\Mpociot\BotMan\Answer $answer) {
      // Save result
      $this->email = $answer->getText();

      $this->say('Great - that is all we need, '.$this->firstname);
    });
  }

  public function run()
  {
    // This will be called immediately
    $this->askFirstname();
  }
}
