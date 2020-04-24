<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\widgets;

use dukt\analytics\apis\Analytics;
use noak\bjonky\Bjonky;
use noak\bjonky\assetbundles\bjonkywidgetwidget\BjonkyWidgetWidgetAsset;
use noak\bjonky\controllers\DefaultController;
use noak\bjonky\controllers\FormsController;
use Craft;
use craft\base\Widget;use phpDocumentor\Reflection\Types\This;

/**
 * bjonky Widget
 *
 * Dashboard widgets allow you to display information in the Admin CP Dashboard.
 * Adding new types of widgets to the dashboard couldn’t be easier in Craft
 *
 * https://craftcms.com/docs/plugins/widgets
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class BjonkyWidget extends Widget
{

    // Public Properties
    // =========================================================================

    /**
     * @var string The message to display
     */
    public $message = '0';
    public $numberOfDays = 7;
    public $graphType = '';

    // Static Methods
    // =========================================================================

/**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('bjonky', 'Bjonky Analytics');

    }

    /**
     * Returns the path to the widget’s SVG icon.
     *
     * @return string|null The path to the widget’s SVG icon
     */
    public static function iconPath()
    {
        return Craft::getAlias("@noak/bjonky/assetbundles/bjonkywidgetwidget/dist/img/BjonkyWidget-icon.svg");
    }

    /**
     * Returns the widget’s maximum colspan.
     *
     * @return int|null The widget’s maximum colspan, if it has one
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['message', 'integer'],
                ['message', 'default', 'value' => 7],
            ]
        );
        return $rules;
    }

    /**
     * Returns the component’s settings HTML.
     *
     * An extremely simple implementation would be to directly return some HTML:
     *
     * ```php
     * return '<textarea name="foo">'.$this->getSettings()->foo.'</textarea>';
     * ```
     *
     * For more complex settings, you might prefer to create a template, and render it via
     * [[\craft\web\View::renderTemplate()]]. For example, the following code would render a template loacated at
     * craft/plugins/myplugin/templates/_settings.html, passing the settings to it:
     *
     * ```php
     * return Craft::$app->getView()->renderTemplate('myplugin/_settings', [
     *     'settings' => $this->getSettings()
     * ]);
     * ```
     *
     * If you need to tie any JavaScript code to your settings, it’s important to know that any `name=` and `id=`
     * attributes within the returned HTML will probably get [[\craft\web\View::namespaceInputs() namespaced]],
     * however your JavaScript code will be left untouched.
     *
     * For example, if getSettingsHtml() returns the following HTML:
     *
     * ```html
     * <textarea id="foo" name="foo"></textarea>
     *
     * <script type="text/javascript">
     *     var textarea = document.getElementById('foo');
     * </script>
     * ```
     *
     * …then it might actually look like this before getting output to the browser:
     *
     * ```html
     * <textarea id="namespace-foo" name="namespace[foo]"></textarea>
     *
     * <script type="text/javascript">
     *     var textarea = document.getElementById('foo');
     * </script>
     * ```
     *
     * As you can see, that JavaScript code will not be able to find the textarea, because the textarea’s `id=`
     * attribute was changed from `foo` to `namespace-foo`.
     *
     * Before you start adding `namespace-` to the beginning of your element ID selectors, keep in mind that the actual
     * namespace is going to change depending on the context. Often they are randomly generated. So it’s not quite
     * that simple.
     *
     * Thankfully, [[\craft\web\View]] service provides a couple handy methods that can help you deal
     * with this:
     *
     * - [[\craft\web\View::namespaceInputId()]] will give you the namespaced version of a given ID.
     * - [[\craft\web\View::namespaceInputName()]] will give you the namespaced version of a given input name.
     * - [[\craft\web\View::formatInputId()]] will format an input name to look more like an ID attribute value.
     *
     * So here’s what a getSettingsHtml() method that includes field-targeting JavaScript code might look like:
     *
     * ```php
     * public function getSettingsHtml()
     * {
     *     // Come up with an ID value for 'foo'
     *     $id = Craft::$app->getView()->formatInputId('foo');
     *
     *     // Figure out what that ID is going to be namespaced into
     *     $namespacedId = Craft::$app->getView()->namespaceInputId($id);
     *
     *     // Render and return the input template
     *     return Craft::$app->getView()->renderTemplate('myplugin/_fieldinput', [
     *         'id'           => $id,
     *         'namespacedId' => $namespacedId,
     *         'settings'     => $this->getSettings()
     *     ]);
     * }
     * ```
     *
     * And the _settings.html template might look like this:
     *
     * ```twig
     * <textarea id="{{ id }}" name="foo">{{ settings.foo }}</textarea>
     *
     * <script type="text/javascript">
     *     var textarea = document.getElementById('{{ namespacedId }}');
     * </script>
     * ```
     *
     * The same principles also apply if you’re including your JavaScript code with
     * [[\craft\web\View::registerJs()]].
     *
     * @return string|null
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'bjonky/_components/widgets/BjonkyWidget_settings',
            [
                'widget' => $this
            ]

        );
    }


    /**
     * Returns the widget's body HTML.
     *
     * @return string|false The widget’s body HTML, or `false` if the widget
     *                      should not be visible. (If you don’t want the widget
     *                      to be selectable in the first place, use {@link isSelectable()}.)
     * @throws \yii\base\InvalidConfigException
     */
    public function getBodyHtml()
    {

        Craft::$app->getView()->registerAssetBundle(BjonkyWidgetWidgetAsset::class);

        //$googleData = Bjonky::$plugin->bjonkyService->getSessions($this->numberOfDays);
        //$spd = Bjonky::$plugin->bjonkyService->getSessionsPerDay($this->numberOfDays);
        //$dc = Bjonky::$plugin->bjonkyService->getDeviceMetrics($this->numberOfDays);
        //$pp = Bjonky::$plugin->bjonkyService->getPageMetrics($this->numberOfDays);
        //$newRows = Bjonky::$plugin->bjonkyService->getTopSessions($this->numberOfDays);

        //$sessions = $googleData->totalsForAllResults['ga:sessions'];
        //$profileName = $googleData->profileInfo['profileName'];
        //$rows = $googleData->rows;
        //echo '<pre>'; print_r($sessions); exit;

        $this->graphType;



        return Craft::$app->getView()->renderTemplate(
            'bjonky/_components/widgets/'.$this->graphType,
            [


                'numberOfDays' => $this->numberOfDays,
                //'sessions' => $sessions,
                //'sessionsPerDay' => $spd,
                //'rows' => $newRows,
                //'tableRows' => $rows,
                //'profileName' => $profileName,
                //'deviceCategory' =>$dc,
                //'popularPage' => $pp
            ]
        );
    }
}
