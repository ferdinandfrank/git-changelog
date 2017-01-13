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
     * The author of the commit.
     *
     * @var string
     */
    public $author;

    /**
     * The merge info of the commit.
     *
     * @var string
     */
    public $merge;

}