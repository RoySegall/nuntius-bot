<?php

namespace Nuntius\System\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnableCapsule extends Command {

  /**
   * @var \Nuntius\Capsule\CapsuleServiceInterface
   */
  protected $capsuleService;

  /**
   * {@inheritdoc}
   */
  public function __construct($name = null) {
    parent::__construct($name);

    $this->capsuleService = Nuntius::getCapsuleManager();
  }

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
    $capsule_name = $input->getArgument('capsule_name');
    $io = new SymfonyStyle($input, $output);

    // Check if capsule exist.
    if (!$this->capsuleService->capsuleExists($capsule_name)) {
      $io->error('The capsule ' . $capsule_name . ' is missing.');
    }

    // Check the capsule is not enabled.
    if ($this->capsuleService->capsuleEnabled($capsule_name)) {
      $io->error('The capsule is already enabled');
    }

    // Check if the capsule has any requirements.
    $capsules = $this->capsuleService->getCapsules();

    $requirements = $capsules[$capsule_name]['dependencies'];

    // Check if the requirements not enabled/exists.

    // Enable the capsule.
  }

}
