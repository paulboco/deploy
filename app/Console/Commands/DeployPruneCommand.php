<?php

namespace App\Console\Commands;

use App\DeployManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DeployPruneCommand extends Command
{
    /**
     * The DeployManager Instance
     *
     * @var App\DeployManager
     */
    protected $manager;

    /**
     * Create a new DeployPruneCommand
     *
     * @param  DeployManager  $manager
     */
    public function __construct(DeployManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Configure The Command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('deploy:prune')
            ->setDescription('Prune old deployment releases')
            ->addArgument(
                'count',
                InputArgument::OPTIONAL,
                'Number of old releases to keep'
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
        $count = $input->getArgument('count');

        $helper = $this->getHelper('question');
        $question = new Question('Are you sure you want to prune all old releases?', 'n');

        $bundle = $helper->ask($input, $output, $question);

        $output->writeln("<error>Count: {$count}; Bundle: {$bundle}</error>");
    }
}
