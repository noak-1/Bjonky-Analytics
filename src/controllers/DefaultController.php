<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\controllers;
require_once '../../vendor/autoload.php';
use noak\bjonky\Bjonky;

use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/bjonky/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/bjonky/default/sessions
     *
     * @return mixed
     */
    public function actionSessions()
    {
        $googleData = Bjonky::$plugin->bjonkyService->getSessions($this->numberOfDays);
        //$spd = Bjonky::$plugin->bjonkyService->getSessionsPerDay($this->numberOfDays);
        $dc = Bjonky::$plugin->bjonkyService->getDeviceMetrics($this->numberOfDays);
        $pp = Bjonky::$plugin->bjonkyService->getPageMetrics($this->numberOfDays);
        $newRows = Bjonky::$plugin->bjonkyService->getTopSessions($this->numberOfDays);

        $sessions = $googleData->totalsForAllResults['ga:sessions'];
        $profileName = $googleData->profileInfo['profileName'];
        $rows = $googleData->rows;



        // returnera nåt sånt här = json_encode($rows);

        $result = json_encode($rows);
        //echo '<pre>'; print_r($result); exit;
        return $result;
    }


    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/bjonky/default/do-something
     *
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the DefaultController actionDoSomething() method';

        return $result;
    }

    private $message;
}
