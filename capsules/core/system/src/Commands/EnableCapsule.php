<?php

namespace Nuntius\System\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableCapsule extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('system:capsule_enable')
      ->setDescription('Capsule enable')
      ->setHelp('Enabling a capsule')
      ->addArgument('capsule_name', InputArgument::REQUIRED);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    // Check the capsule is not enabled.

    // Check if the capsule has any requiremnts.

    // Check if the requirements not enabled/exists.

    // Enable the capsule.
  }

}
