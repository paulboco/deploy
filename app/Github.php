<?php

namespace App;

class Github
{
    private $test = true;

    /**
     * Check Github Repository Existance
     *
     * @param  string  $repository
     * @return boolean
     */
    public function hasRepo($repository)
    {
        if ($this->test) {
            $location = 'https://github.com/' . $repository . '/archive/master.zip';
            return strpos(get_headers($location)[0], '404') === false;
        } else {
            exec("git ls-remote git@github.com:{$repository}.git", $void, $code);
            return !$code;
        }
    }

    /**
     * Clone A Github Repository
     *
     * @param  string  $repository
     * @param  string  $destination
     * @return boolean
     */
    public function cloneRepo($repository, $destination)
    {
        $repository = "https://github.com/{$repository}.git";

        exec("git clone --depth 1 {$repository} {$destination}", $null, $code);

        return !$code;
    }
}
