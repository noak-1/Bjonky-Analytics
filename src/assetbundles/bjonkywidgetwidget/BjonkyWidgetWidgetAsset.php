<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\assetbundles\bjonkywidgetwidget;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * BjonkyWidgetWidgetAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class BjonkyWidgetWidgetAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {

        // define the path that your publishable resources live
        $this->sourcePath = "@noak/bjonky/assetbundles/bjonkywidgetwidget/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered

        /* for assets
        $this->js = [

            'js/top_sessions.js',
            'js/device_usage.js',
            'js/top_pages.js'
        ];
        */
        $this->css = [
            'css/BjonkyWidget.css',
        ];

        parent::init();
    }
}
