<?php

namespace YOOtheme;

use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\Http\Uri;
use YOOtheme\Theme\SvgHelper;


return [
    'transforms' => [
        'render' => function ($node) {
            /**
             * @var Config        $config
             * @var ImageProvider $image
             * @var View          $view
             */
            [$config, $image, $view] = app(Config::class, ImageProvider::class, View::class);
            $plugin = PluginHelper::getPlugin('system', 'googlemapapi');
            $googlemapapiParams = json_decode($plugin->params);
            $node->mapsCategoryButtons = [];
            $placesTypesParam = $googlemapapiParams->places_types;
            $placesTypes = [];
            foreach(explode("\r\n", $placesTypesParam) as $type) {
                $typeDetails = explode(",", $type);
                $placesTypes[] = [
                    "type" => $typeDetails[0],
                    "icon" => $typeDetails[1],
                    "label" => $typeDetails[2]
                ];
            }
            $node->placeIds = explode("\r\n", $googlemapapiParams->places_ids);
            $node->mapsCategoryButtons = $placesTypes;

            // $plugin
            //$googlemapapi = $plugin->params->get("googlemapapi"); 

            $center = [];
            $node->options = [];
        
            // map options
            $node->options += Arr::pick($node->props, [
                'type',
                'zoom',
                'fit_bounds',
                'min_zoom',
                'max_zoom',
                'zooming',
                'dragging',
                'clustering',
                'controls',
                'popup_max_width',
            ]);
            $node->options['center'] = $center ?: ['lat' => 52.6, 'lng' => 0.5];
            $node->options['lazyload'] = true;

            $node->options = array_filter($node->options, fn($value) => isset($value));

            $node->props['metadata'] = [];
            $node->options['mapId'] = $googlemapapiParams->map_id;

            // add scripts, styles
            $key = $config('~theme.google_maps');
            $node->options['library'] = 'google';

            $node->options['apiKey'] = $key;
            $node->props['metadata']['script:component'] = [
                'src' => "https://unpkg.com/@googlemaps/extended-component-library",
                'type' => "module",
            ];
            $node->props['metadata']['script:builder-map'] = [
                'src' => Path::get('./app/gbp_map.min.js', __DIR__),
                'defer' => true,
            ];
        },
    ],
];
