<?php 

/**
 * A "safe" embed module. See SafeObject. This is a proprietary element.
 */
class HTMLPurifier_HTMLModule_SafeEmbed extends HTMLPurifier_HTMLModule
{

    public $name = 'SafeEmbed';

    public function setup($config) {

        $max = $config->get('HTML.MaxImgLength');
        $embed = $this->addElement(
            'embed', 'Inline', 'Empty', 'Common',
            array(
                'src*' => 'URI#embedded',
                'type' => 'Enum#application/x-shockwave-flash',
                'width' => 'Pixels#' . $max,
                'height' => 'Pixels#' . $max,
                'allowscriptaccess' => 'Enum#never',
                'allownetworking' => 'Enum#internal',
                'wmode' => 'Enum#window',
                'name' => 'ID',
            )
        );
        $embed->attr_transform_post[] = new HTMLPurifier_AttrTransform_SafeEmbed();

    }

}

// vim: et sw=4 sts=4
