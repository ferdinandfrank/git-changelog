<?php

namespace EpicArrow\GitChangeLog\Models;

use Carbon\Carbon;


/**
 * Commit
 * -----------------------
 * Model to represent a git commit.
 *
 * @author  Ferdinand Frank
 * @version 1.0
 * @package App\Models
 */
class Commit {

    /**
     * The remote url of the git repo.
     *
     * @var string|null
     */
    private static $remoteUrl;

    /**
     * The commit hash.
     *
     * @var string
     */
    public $id;

    /**
     * The date of the commit.
     *
     * @var Carbon
     */
    public $date;

    /**
     * The commit message.
     *
     * @var string
     */
    public $message;

    /**
     * The author's name of the commit.
     *
     * @var string
     */
    public $author;

    /**
     * The author's email address of the commit.
     *
     * @var string
     */
    public $email;

    /**
     * The merge info of the commit.
     *
     * @var string
     */
    public $merge;

    /**
     * The version of the commit.
     *
     * @var string
     */
    public $version;

    /**
     * Gets the remote link for the commit.
     *
     * @return string
     */
    public function getRemoteLink() {
        if (!self::$remoteUrl) {
            $remoteUrl = exec("git config --get remote.origin.url");
            if ($remoteUrl) {

                // Remove ".git"
                self::$remoteUrl = substr($remoteUrl, 0, -4);
            }
        }

        if ($this->id) {
            return self::$remoteUrl . '/commit/' . $this->id;
        }

        return self::$remoteUrl;
    }
}