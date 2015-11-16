<?php

namespace App\Console\Commands;

use App\DeployManager;
use App\Github;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployRepoCommand extends Command
{
    /**
     * The Github Instance
     *
     * @var App\Github
     */
    protected $github;

    /**
     * The DeployManager Instance
     *
     * @var App\DeployManager
     */
    protected $manager;

    /**
     * Create a new DeployRepoCommand
     *
     * @param  Github  $github
     * @param  DeployManager  $manager
     */
    public function __construct(Github $github, DeployManager $manager)
    {
        parent::__construct();

        $this->github = $github;
        $this->manager = $manager;
    }

    /**
     * Configure The Command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('deploy:repo')
            ->setDescription('Deploy a github repository')
            ->addArgument(
                'repository',
                InputArgument::REQUIRED,
                'What is the vendor/repository name?'
            );
    }

    /**
     * Execute The Command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $input->getArgument('repository');
        $destination = $this->manager->getReleaseDir($repository);
        $oldReleases = $this->manager->getOldReleases($repository);

        if (!$this->github->cloneRepo($repository, $destination)) {
            $output->writeln("<error>Repository {$repository} did not clone successfully");
            return 1;
        }

        if (!$this->manager->deploy($repository )) {
            $output->writeln("<error>Repository {$repository} was not deployed due to unknown error</error>");
            return 1;
        }

        $this->manager->deactivateOldReleases($oldReleases);
        $output->writeln("<info>Repository {$repository} was deployed successfully</info>");
        return 0;
    }
}
