<?php

namespace App;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DeployManager
{
    /**
     * The app base directory.
     *
     * @var string
     */
    private $baseDir;

    /**
     * The Release Name
     *
     * @var string
     */
    private $releaseName;

    /**
     * The release prefix.
     *
     * @var string
     */
    private $releasePrefix = 'active_release_';

    /**
     * The release base directory.
     *
     * @var string
     */
    private $releaseBaseDir = '/releases/';

    /**
     * The deploy directory.
     *
     * @var string
     */
    private $deployDir = '/current/';

    /**
     * The date format.
     *
     * @var string
     */
    private $dateFormat = 'YmdHis';

    /**
     * Create a new DeployManager
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseDir = realpath(__DIR__ . '/../');
    }

    /**
     * Deploy the repository.
     *
     * @param  string  $repository
     * @return boolean
     */
    public function deploy($repository)
    {
        $this->makeDeployDir($repository, true);
        $this->makeDeployLink($repository);
        return 1;
    }

    /**
     * Get the release directory.
     *
     * @param  string  $repository
     * @return string
     */
    public function getReleaseDir($repository)
    {
        $releaseName = $this->getReleaseName($repository);

        return $this->baseDir . $this->releaseBaseDir . $repository . '/' . $releaseName;
    }

    /**
     * Get the old releases.
     *
     * @param  string  $repository
     * @return array
     */
    public function getOldReleases($repository)
    {
        $pattern = $this->baseDir . $this->releaseBaseDir . $repository . '/active_*';

        return glob($pattern);
    }

    /**
     * Deactivate the old releases.
     *
     * @param  array  $releases
     * @return void
     */
    public function deactivateOldReleases($releases)
    {
        foreach ($releases as $release) {
            rename($release, str_replace('active_', '', $release));
        }
    }

    /**
     * Get the deploy directory.
     *
     * @param  string  $repository
     * @return string
     */
    private function getDeployDir($repository)
    {
        return $this->baseDir . $this->deployDir . $repository;
    }

    /**
     * Make the deploy directory.
     *
     * @param  string  $repository
     * @param  boolean  $force
     * @return void
     */
    private function makeDeployDir($repository, $force = false)
    {
        $directory = $this->getDeployDir($repository);

        @mkdir($directory, 0755, true);
    }

    /**
     * Make deploy link.
     *
     * @param  string  $repository
     * @return boolean
     */
    private function makeDeployLink($repository)
    {
        $releaseDir = $this->getReleaseDir($repository);
        $deployDir = $this->getDeployDir($repository);

        $process = new Process("ln -nfs {$releaseDir}/public {$deployDir}");

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Get the release name.
     *
     * @param  string  $repository
     * @return string
     */
    private function getReleaseName($repository)
    {
        if ($this->releaseName) {
            return $this->releaseName;
        }

        $this->releaseName = $this->releasePrefix . date($this->dateFormat);

        return $this->releaseName;
    }
}
