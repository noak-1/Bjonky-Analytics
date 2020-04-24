<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\services;
use noak\bjonky\Bjonky;
use craft\base\Component;
use Google_Client;
use Google_Service_Analytics;

/**
 * BjonkyService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class BjonkyService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Bjonky::$plugin->bjonkyService->exampleService()
     *
     * @return mixed
     * @throws \Google_Exception
     */


        function initializeAnalytics()
    {
        // Creates and returns the Analytics Reporting service object.

        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = 'C:\temp\bjonky\service-account-credentials.json';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }

        function getFirstProfileId($analytics)
    {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();
                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

        function getSessions($days)
    {

        $analytics = $this->initializeAnalytics();
        $profileId = $this->getFirstProfileId($analytics);

        if (!is_numeric($days)) {
            $days = 0;
        }
        $metrics = 'ga:sessions, ga:visits,ga:pageviews,ga:bounces,ga:entranceBounceRate';
        $dimensions = 'ga:date,ga:year,ga:month,ga:day';

        $sessions = $analytics->data_ga->get(
            'ga:' . $profileId,
            $days . 'daysAgo',
            'today',
            $metrics,
            ['dimensions' => $dimensions]
        );

        return $sessions;
    }




    function getTopSessions($days)
    {
        $analytics = $this->initializeAnalytics();
        $profileId = $this->getFirstProfileId($analytics);

        if (!is_numeric($days)) {
            $days = 0;
        }
        $metrics = 'ga:sessions, ga:visits,ga:pageviews,ga:bounces,ga:entranceBounceRate';
        $dimensions = 'ga:date,ga:year,ga:month,ga:day';

        $sessions = $analytics->data_ga->get(
            'ga:' . $profileId,
            $days . 'daysAgo',
            'today',
            $metrics,
            ['dimensions' => $dimensions]
        );

        $rows = $sessions->rows;
        $newRows = [['', 'sessions', 'bounces']];
        foreach($rows as $row) {
            $newRows[] = [$row[0], $row[4], $row[7]];
        }

        return $newRows;
    }

    public function getDeviceMetrics($days)
        {
            {
                if (!is_numeric($days)) {
                    $days = 0;
                }

                $metrics = 'ga:sessions';
                $dimensions = 'ga:deviceCategory';
            }
            $analytics = $this->initializeAnalytics();
            $profileId = $this->getFirstProfileId($analytics);

            $report = $analytics->data_ga->get(
                'ga:' . $profileId,
                $days . 'daysAgo',
                'today',
                $metrics,
                ['dimensions' => $dimensions]
            );

            return $report->rows;
        }

        public function getPageMetrics($days)
        {
            {
                if (!is_numeric($days)) {
                    $days = 0;
                }
                $metrics = 'ga:pageviews';
                $optParams = array(
                    'max-results' => 5,
                    'dimensions' => 'ga:pagePath',
                    'sort' => '-ga:pageviews'
                );
                $dimensions = 'ga:pageTitle,ga:pagePath';
            }
            $analytics = $this->initializeAnalytics();
            $profileId = $this->getFirstProfileId($analytics);

            $result = $analytics->data_ga->get(
                'ga:' . $profileId,
                $days . 'daysAgo',
                'today',
                $metrics,
                $optParams
            );
            return $result->rows;
        }



        /*
          public function getSessionsPerDay($days)
          {

              $googleData = Bjonky::$plugin->bjonkyService->getSessions($days);
              $sessions = $googleData->totalsForAllResults['ga:sessions'];

              $total = $sessions / $days;
             // echo '<pre>'; print_r($total); exit;
              return $total;

          }
  */
}

/*
    function printResults($results)
    {
        // Parses the response from the Core Reporting API and prints
        // the profile name and total sessions.
        //echo '<pre>'; print_r($results); exit;
        if (count($results->getRows()) > 0) {

            // Get the profile name.
            $profileName = $results->getProfileInfo()->getProfileName();

            // Get the entry for the first entry in the first row.
            $rows = $results->getRows();
            $sessions = $rows[0][0];

            // Print the results.
            print "First view (profile) found: $profileName\n";
            print "Total sessions: $sessions\n";
        } else {
            print "No results found.\n";
        }
    }
*/
/*
        public function exampleService($days)
        {
            $analytics = $this->initializeAnalytics();
            $profile = $this->getFirstProfileId($analytics);
            $results = $this->getResults($days);
            echo '<pre>'; print_r($results); exit;

            return $results;
        }
    */
