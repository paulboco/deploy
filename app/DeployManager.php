<?php

namespace App;

class DeployManager
{
    private $baseDir;
    private $releaseName;
    private $releasePrefix = 'active_release_';
    private $releaseBaseDir = '/releases/';
    private $deployDir = '/current/';

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function getReleaseName($repository)
    {
        if ($this->releaseName) {
            return $this->releaseName;
        }

        $this->releaseName = $this->releasePrefix . date('YmdHis');;

        return $this->releaseName;
    }

    public function getReleaseBaseDir()
    {
        return $this->baseDir . $this->releaseBaseDir;
    }

    public function getReleaseDir($repository)
    {
        $releaseName = $this->getReleaseName($repository);

        return $this->baseDir . $this->releaseBaseDir . $repository . '/' . $releaseName;
    }

    public function getDeployDir($repository)
    {
        return $this->baseDir . $this->deployDir . $repository;
    }

    public function makeDeployDir($repository, $force = false)
    {
        $directory = $this->getDeployDir($repository);

        @mkdir($directory, 0755, true);

        return $directory;
    }

    public function makeDeployLink($repository)
    {
        $releaseDir = $this->getReleaseDir($repository);
        $deployDir = $this->getDeployDir($repository);

        exec("ln -nfs {$releaseDir}/public {$deployDir}", $null, $code);

        return !$code;
    }

    public function getOldReleases($repository)
    {
        $pattern = $this->baseDir . $this->releaseBaseDir . $repository. '/active_*';

        return glob($pattern);
    }

    public function deactivateOldReleases($releases)
    {
        foreach ($releases as $release) {
            rename($release, str_replace('active_', '', $release));
        }
    }
}