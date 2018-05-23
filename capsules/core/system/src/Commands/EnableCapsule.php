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
    // Setting up the variables.
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

    // Check if one of the requirements even exists before moving on.
    $missing = array_filter($requirements, function ($item) use($capsules) {
        return in_array($item, array_keys($capsules));
    });

    if ($missing) {
      $list_of_missing = join(", ", $missing);
      $io->error("{$capsule_name} depends on {$list_of_missing} before. Download them before you can continue.");
    }

    // Checking what we need to enable before enabling this one.
    $disabled_capsules = $this->capsuleService->capsuleList('disabled');
    $need_to_enabled = array_filter($disabled_capsules, function ($item) use($requirements) {
      return in_array($item, $requirements);
    });

    if ($need_to_enabled) {
      // todo: check dependencies of dependencies.
      $depends = implode(", ", $need_to_enabled);

      if ($io->confirm("The capsule depends on {$depends}. would you like to enable them?")) {
        foreach ($need_to_enabled as $capsule) {
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
