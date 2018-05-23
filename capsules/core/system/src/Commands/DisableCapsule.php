<?php

namespace Nuntius\System\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DisableCapsule extends Command {

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
      ->setName('system:capsule_disable')
      ->setDescription('Disable enable')
      ->setHelp('Disabling a capsule')
      ->addArgument('capsule_name', InputArgument::REQUIRED);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    // Setting up the variables.
    $capsules = $this->capsuleService->getCapsules();
    $capsule_name = $input->getArgument('capsule_name');
    $io = new SymfonyStyle($input, $output);

    // Check if capsule exist.
    if (!$this->capsuleService->capsuleExists($capsule_name)) {
      $io->error('The capsule ' . $capsule_name . ' is missing.');
    }

    // Check the capsule is not disabled.
    if (!$this->capsuleService->capsuleEnabled($capsule_name)) {
      $io->error('The capsule is already disabled.');
      return;
    }

    // Check if the capsule has any depends capsules which enabled.
    $enabled_capsules = $this->capsuleService->capsuleList('enabled');
    $need_to_disable = [];
    foreach ($enabled_capsules as $enabled_capsule) {

      if (empty($capsules[$enabled_capsule]['dependencies'])) {
        // No dependencies.
        continue;
      }

      if (in_array($capsule_name, $capsules[$enabled_capsule]['dependencies'])) {
        $need_to_disable[] = $enabled_capsule['machine_name'];
      }
    }

    if ($need_to_disable) {
      // todo: check dependencies of dependencies.
      $depends = implode(", ", $need_to_disable);

      if ($io->confirm("The capsule depended by {$depends}. would you like to disable them?")) {
        foreach ($need_to_disable as $capsule) {
          $io->success('Disabling ' . $capsules[$capsule]['name']);
        }
      }
      else {
        $io->success("OK, the capsule won't be disabled.");
        return;
      }
    }

    // Disable the capsule.
    $this->capsuleService->disableCapsule($capsule_name);
    $io->success("The capsule {$capsule_name} has been disabled.");
  }

}
