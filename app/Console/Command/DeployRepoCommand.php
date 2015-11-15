<?php

namespace App\Console\Command;

use App\DeployManager;
use App\Github;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployRepoCommand extends Command
{
    protected $github;
    protected $manager;

    public function __construct(Github $github, DeployManager $manager)
    {
        parent::__construct();

        $this->github = $github;
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setName('deploy:repo')
            ->setDescription('Deploy a github repository')
            ->addArgument(
                'repository',
                InputArgument::REQUIRED,
                'What is the vendor/repository name?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $input->getArgument('repository');
        $destination = $this->manager->getReleaseDir($repository);
        $oldReleases = $this->manager->getOldReleases($repository);

        if (!$this->github->hasRepo($repository)) {
            $output->writeln("<error>Repository {$repository} not found</error>");
            return;
        }

        if ($this->github->cloneRepo($repository, $destination)) {
            $output->writeln("<info>Repository {$repository} was cloned successfully</info>");
        } else {
            $output->writeln("<error>Repository {$repository} did not clone successfully");
            return;
        }

        if ($this->deploy($repository)) {
            $this->manager->deactivateOldReleases($oldReleases);
            $output->writeln("<info>Repository {$repository} was deployed successfully</info>");
        } else {
            $output->writeln("<error>Repository {$repository} was not deployed due to unknown error</error>");
        }
    }

    private function deploy($repository)
    {
        $this->manager->makeDeployDir($repository, true);

        return $this->manager->makeDeployLink($repository);
    }
}
