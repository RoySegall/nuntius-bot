<?php

namespace Nuntius\System\Commands;

use Nuntius\Capsule\CapsuleServiceInterface;
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
  public function __construct(CapsuleServiceInterface $capsule_service) {
    $this->capsuleService = $capsule_service;

    parent::__construct(NULL);
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
    // Setting up the variables.
    $capsule_name = $input->getArgument('capsule_name');
    $io = new SymfonyStyle($input, $output);

    // Check if capsule exist.
    if (!$this->capsuleService->capsuleExists($capsule_name)) {
      $io->error('The capsule ' . $capsule_name . ' is missing.');
      return;
    }

    // Check the capsule is not enabled.
    if ($this->capsuleService->capsuleEnabled($capsule_name)) {
      $io->error('The capsule is already enabled');
      return;
    }

    // Check if the capsule has any requirements.
    $capsules = $this->capsuleService->getCapsules();
    $requirements = $capsules[$capsule_name]['dependencies'];

    // Check if one of the requirements even exists before moving on.
    $missing = [];
    foreach ($requirements as $requirement) {
      if (!in_array($requirement, array_keys($capsules))) {
        $missing[] = $requirement;
      }
    }

    if ($missing) {
      $list_of_missing = join(", ", $missing);
      $io->error("{$capsule_name} depends on {$list_of_missing}. Download them try again.");
      return;
    }

    // Checking what we need to enable before enabling this one.
    $enabled_capsules = $this->capsuleService->capsuleList('enabled');
    $need_to_enabled = [];
    foreach ($capsules as $capsule) {
      if ($capsule['machine_name'] == $capsule_name) {
        continue;
      }
      if (!in_array($capsule['machine_name'], $enabled_capsules) && in_array($capsule['machine_name'], $requirements)) {
        $need_to_enabled[] = $capsule['machine_name'];
      }
    }

    if ($need_to_enabled) {
      // todo: check dependencies of dependencies.
      $depends = implode(", ", $need_to_enabled);

      if ($io->confirm("The capsule depends on {$depends}. would you like to enable them?")) {
        foreach ($need_to_enabled as $capsule) {
          $this->capsuleService->enableCapsule($capsule);
          $io->success('Enabling ' . $capsules[$capsule]['name']);
        }
      }
      else {
        $io->success("OK, the dependencies won't be enabled as well as this capsule.");
        return;
      }
    }

    // Enable the capsule.
    $this->capsuleService->enableCapsule($capsule_name);
    $io->success('The ' . $capsule_name . ' is now enabled');
  }

}
