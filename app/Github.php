<?php

namespace App;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Github
{
    private $repoMask = 'git@github.com:%s.git';

    /**
     * Check Github Repository Existence
     *
     * @param  string  $repository
     * @return boolean
     */
    // public function hasRepo($repository)
    // {
    //     $location = sprintf($this->repoMask, $repository);
    //     $command = "git ls-remote {$location}";
    //     $process = new Process($command, $_SERVER['HOME'], array_merge($_SERVER, $_ENV));

    //     $process->run();

    //     if (!$process->isSuccessful()) {
    //         throw new ProcessFailedException($process);
    //     }

    //     return 1;
    // }

    /**
     * Clone A Github Repository
     *
     * @param  string  $repository
     * @param  string  $destination
     * @return boolean
     */
    public function cloneRepo($repository, $destination)
    {
        $location = sprintf($this->repoMask, $repository);

        $process = new Process("git clone --depth 1 {$location} {$destination}");

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return 1;
    }
}
