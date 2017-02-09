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
    public static function get($count = null) {

        // Retrieve all published tags
        $tags = [];
        exec("git tag", $output);
        foreach ($output as $tag) {
            array_push($tags, $tag);
        }

        // Create count option
        $countStmt = $count ? "-$count" : '';

        // If no tags found return normal log
        if (sizeof($tags) < 1) {
            $output = [];
            exec("git log $countStmt", $output);
            return self::parseLog($output);
        }

        // Add empty tag for commits without tag
        array_push($tags, '');

        // Reverse the tag array to get the latest first
        $tags = array_reverse($tags);

        $changelog = [];
        for ($i = 0; $i < sizeof($tags); $i++) {
            $version = $tags[$i];

            $versionStmt = $version;
            if ($i != sizeof($tags) - 1) {
                $prevVersion = $tags[$i+1];
                $versionStmt = "$prevVersion..$version";
            }

            // Adapt result count to number of retrieved results
            $countStmt = $count ? "-" . ($count - count($changelog)) : '';
            exec("git log $countStmt $versionStmt", $output);

            $parsedLog = self::parseLog($output, $version);
            $changelog = array_merge($changelog, $parsedLog);

            if ($count && sizeof($changelog) >= $count) {
                break;
            }

            unset($output);
        }

        return $changelog;
    }

    private static function parseLog($log, $version = null) {
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
                $commit->version = $version;
            } else if (strpos($line, 'Author') === 0) {

                // Separate author name and email address
                $authorInfo = explode(" ", substr($line, strlen('Author:') + 1));

                $commit->author = $authorInfo[0];

                // Remove leading and trailing bracket from the email address
                $commit->email = substr($authorInfo[1], 1, -1);

            } else if (strpos($line, 'Date') === 0) {
                $commit->date = \Carbon\Carbon::parse(substr($line, strlen('Date:') + 3));
            } elseif (strpos($line, 'Merge') === 0) {
                $commit->merge = substr($line, strlen('Merge:') + 1);
                $commit->merge = explode(' ', $commit->merge);
            } else if ($commit->id) {
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