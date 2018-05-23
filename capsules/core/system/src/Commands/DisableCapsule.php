<?php

namespace Nuntius\System\Commands;

use Nuntius\Capsule\CapsuleServiceInterface;
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
  public function __construct(CapsuleServiceInterface $capsule_service) {
    $this->capsuleService = $capsule_service;

    parent::__construct(NULL);
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
      return;
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
      if ($enabled_capsule == $capsule_name) {
        continue;
      }

      $capsule_info = $capsules[$enabled_capsule];

      if (empty($capsule_info['dependencies'])) {
        continue;
      }

      if (in_array($capsule_name, $capsule_info['dependencies'])) {
        $need_to_disable[] = $capsule_info['machine_name'];
      }
    }


    if ($need_to_disable) {
      // todo: check dependencies of dependencies.
      $depends = implode(", ", $need_to_disable);

      if ($io->confirm("The capsule depended by {$depends}. would you like to disable them?")) {
        foreach ($need_to_disable as $capsule) {
          $this->capsuleService->disableCapsule($capsule);
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
