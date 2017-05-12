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
        exec("git remote show origin", $output);
        if ($output && is_array($output)) {

            // Remove text "  Fetch URL:  " and ".git"
            $fetchUrl = substr($output[1], 13, -4);

            if ($this->id) {
                return $fetchUrl . '/commit/' . $this->id;
            }

            return $fetchUrl;
        }

        return '#';
    }
}