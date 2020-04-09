<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\utilities;

use noak\bjonky\Bjonky;
use noak\bjonky\assetbundles\bjonkyutilityutility\BjonkyUtilityUtilityAsset;

use Craft;
use craft\base\Utility;

/**
 * bjonky Utility
 *
 * Utility is the base class for classes representing Control Panel utilities.
 *
 * https://craftcms.com/docs/plugins/utilities
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class BjonkyUtility extends Utility
{
    // Static
    // =========================================================================

    /**
     * Returns the display name of this utility.
     *
     * @return string The display name of this utility.
     */
    public static function displayName(): string
    {
        return Craft::t('bjonky', 'BjonkyUtility');
    }

    /**
     * Returns the utility’s unique identifier.
     *
     * The ID should be in `kebab-case`, as it will be visible in the URL (`admin/utilities/the-handle`).
     *
     * @return string
     */
    public static function id(): string
    {
        return 'bjonky-bjonky-utility';
    }

    /**
     * Returns the path to the utility's SVG icon.
     *
     * @return string|null The path to the utility SVG icon
     */
    public static function iconPath()
    {
        return Craft::getAlias("@noak/bjonky/assetbundles/bjonkyutilityutility/dist/img/BjonkyUtility-icon.svg");
    }

    /**
     * Returns the number that should be shown in the utility’s nav item badge.
     *
     * If `0` is returned, no badge will be shown
     *
     * @return int
     */
    public static function badgeCount(): int
    {
        return 0;
    }

    /**
     * Returns the utility's content HTML.
     *
     * @return string
     */
    public static function contentHtml(): string
    {
        Craft::$app->getView()->registerAssetBundle(BjonkyUtilityUtilityAsset::class);

        $someVar = 'Have a nice day!';
        return Craft::$app->getView()->renderTemplate(
            'bjonky/_components/utilities/BjonkyUtility_content',
            [
                'someVar' => $someVar
            ]
        );
    }
}
