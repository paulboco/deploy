<?php

namespace App;

class DeployManager
{
    /**
     * The App Base Directory
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
     * The Release Prefix
     *
     * @var string
     */
    private $releasePrefix = 'active_release_';

    /**
     * The Release Base Directory
     *
     * @var string
     */
    private $releaseBaseDir = '/releases/';

    /**
     * The Deploy Directory
     *
     * @var string
     */
    private $deployDir = '/current/';

    /**
     * Create a new DeployManager
     *
     * @param  string  $baseDir
     */
    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * Get The Release Name
     *
     * @param  string  $repository
     * @return string
     */
    public function getReleaseName($repository)
    {
        if ($this->releaseName) {
            return $this->releaseName;
        }

        $this->releaseName = $this->releasePrefix . date('YmdHis');

        return $this->releaseName;
    }

    /**
     * Get The Release Base Directory
     *
     * @return string
     */
    public function getReleaseBaseDir()
    {
        return $this->baseDir . $this->releaseBaseDir;
    }

    /**
     * Get The Release Directory
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
     * Get The Deploy Directory
     *
     * @param  string  $repository
     * @return string
     */
    public function getDeployDir($repository)
    {
        return $this->baseDir . $this->deployDir . $repository;
    }

    /**
     * Make The Deploy Directory
     *
     * @param  string  $repository
     * @param  boolean  $force
     * @return void
     */
    public function makeDeployDir($repository, $force = false)
    {
        $directory = $this->getDeployDir($repository);

        @mkdir($directory, 0755, true);
    }

    /**
     * Make Deploy Link
     *
     * @param  string  $repository
     * @return boolean
     */
    public function makeDeployLink($repository)
    {
        $releaseDir = $this->getReleaseDir($repository);
        $deployDir = $this->getDeployDir($repository);

        exec("ln -nfs {$releaseDir}/public {$deployDir}", $null, $code);

        return !$code;
    }

    /**
     * Get The Old Releases
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
     * Deactivate The Old Releases
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
}
