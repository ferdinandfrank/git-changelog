<?php

namespace EpicArrow\GitChangeLog;

use EpicArrow\GitChangeLog\Models\Commit;

/**
 * GitChangeLog
 * -----------------------
 * Service class to receive the latest git commits, as well as the latest version.
 *
 * @author  Ferdinand Frank
 * @version 1.0
 * @package App\Services
 */
class GitChangeLog {

    /**
     * Fetches the latest unique git commits. If two contiguous commits have the same commit message only one commit
     * will be retrieved.
     *
     * @param int $count The number of results to retrieve.
     *
     * @return array The retrieved commits.
     */
    public static function get($count = 10) {
        $output = [];
        exec("git log -$count", $output);

        return self::parseLog($output);
    }

    private static function parseLog($log) {
        $changelog = [];

        $commit = new Commit();
        foreach ($log as $key => $line) {

            $lastLine = $key + 1 == count($log);

            if (strpos($line, 'commit') === 0 || $lastLine) {
                if ($commit->id != null) {
                    if ($lastLine) {
                        $commit->message .= $line;
                    }

                    $commit->message = substr($commit->message, 4);

                    // Only add if commit message is not equal to the last one
                    if (empty($changelog) || $commit->message != $changelog[sizeof($changelog) - 1]->message) {
                        array_push($changelog, $commit);
                    }
                    $commit = new Commit();
                }
                $commit->id = substr($line, strlen('commit') + 1);
            } else if (strpos($line, 'Author') === 0) {
                $commit->author = substr($line, strlen('Author:') + 1);
            } else if (strpos($line, 'Date') === 0) {
                $commit->date = \Carbon\Carbon::parse(substr($line, strlen('Date:') + 3));
            } elseif (strpos($line, 'Merge') === 0) {
                $commit->merge = substr($line, strlen('Merge:') + 1);
                $commit->merge = explode(' ', $commit->merge);
            } else {
                $commit->message .= $line;
            }
        }

        return $changelog;
    }

    /**
     * Gets the latest version of the git repository.
     *
     * @return string|null
     */
    public static function version() {
        exec("git tag", $output);

        return array_pop($output);
    }
}