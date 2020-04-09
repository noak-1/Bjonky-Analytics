<?php
/**
 * bjonky plugin for Craft CMS 3.x
 *
 * a small plugin, in a big world.
 *
 * @link      https://github.com/nokia13
 * @copyright Copyright (c) 2020 Noak Salmgren
 */

namespace noak\bjonky\variables;

use noak\bjonky\Bjonky;

use Craft;

/**
 * bjonky Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.bjonky }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Noak Salmgren
 * @package   Bjonky
 * @since     1.0.0.
 */
class BjonkyVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.bjonky.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.bjonky.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */


    public function exampleVariable($optional)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }

    public function convertDataToChartForm($data)
    {

        $newData = array();
        $firstLine = true;

        foreach ($data as $dataRow)
        {
            if ($firstLine)
            {
                $newData[] = array_keys($dataRow);
                $firstLine = false;
            }

            $newData[] = array_values($dataRow);
        }

        return $newData;
    }

}
