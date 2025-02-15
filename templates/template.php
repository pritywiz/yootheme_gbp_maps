<?php

use YOOtheme\Metadata;
use function YOOtheme\app;

// Resets
if ($props['viewport_height'] == 'viewport' && $props['viewport_height_viewport'] > 100) {
    $props['viewport_height_offset_top'] = false;
}

/** @var Metadata $metadata */
$metadata = app(Metadata::class);

foreach ($props['metadata'] as $name => $attributes) {
    $metadata->set($name, $attributes);
}

$el = $this->el('div', [

    'class' => [
        'uk-position-relative',
        'uk-position-z-index',
        'uk-dark',
        'uk-inverse-{text_color}',

        // Height Viewport
        'uk-height-viewport {@viewport_height: viewport} {@!height_offset_top} {@viewport_height_viewport: |100}',
        'uk-height-viewport-{0} {@viewport_height: viewport} {@!height_offset_top} {@viewport_height_viewport: 200|300|400}' => (int) $props['viewport_height_viewport'] / 100,
    ],

    'style' => [
        'width: {width}px; {@!width_breakpoint} {@!viewport_height}',
        'height: {height}px; {@!width} {@!viewport_height}',

        // Height Viewport
        'min-height: {!viewport_height_viewport: |100|200|300|400}vh; {@viewport_height: viewport} {@!viewport_height_offset_top}'
    ],

    // Height Viewport
    'uk-height-viewport' => $props['viewport_height'] && !($props['viewport_height'] == 'viewport' && !$props['viewport_height_offset_top']) ? [
        'offset-top: true; {@viewport_height_offset_top}',
        'offset-bottom: {0}; {@viewport_height: viewport} {@height_offset_top}' => $props['viewport_height_viewport'] && $props['viewport_height_viewport'] < 100 ? 100 - (int) $props['viewport_height_viewport'] : false,
        'offset-bottom: !:is(.uk-section-default,.uk-section-muted,.uk-section-primary,.uk-section-secondary) +; {@viewport_height: section}',
    ] : false,

    'uk-responsive' => !$props['viewport_height'] ? [
        'width: {width}; height: {height}',
    ] : false,

    'uk-gbp-map' => true,
]);

$script = $this->el('script', ['type' => 'application/json'], json_encode($options));

// Width and Height
$props['width'] = trim($props['width'] ?: '', 'px');
$props['height'] = trim($props['height'] ?: '300', 'px');
//$scriptMap = $this->el('script', ['type' => 'application/json'], json_encode($paramsMap));
//var_dump($options);
?>
<?= $el($props, $attrs) ?>
    <?= $scriptMap ?>
    <?= $script() ?>
    <script>

window.mapOptions = {
  "center":{"lat":52.6,"lng":0.5},
  "fullscreenControl":true,
  "mapTypeControl":true,
  "streetViewControl":true,
  "zoom":10,
  "zoomControl":true,
  "maxZoom":17,
  "mapId":"bd32a1a1e5190d3e"
};
window.gbpPlaceIds = <?= json_encode($placeIds) ?>;

window.marker = {
  "url" : "<?= $props['marker_icon'] ?>",
  "width": <?= $props['marker_icon_width'] ?>,
  "height": <?= $props['marker_icon_height'] ?>
}
</script>
<div class="uk-flex uk-flex-row">
    <div class="uk-flex uk-flex-center" style="width: 70px;" id="category-buttons">
        <div>
        <?php foreach($mapsCategoryButtons as $button) : ?>
            <button data-type="<?= $button["type"]?>" style="width:60px" type="button" class="uk-button uk-border-rounded uk-button-primary uk-margin-xsmall-right uk-margin-xsmall-bottom places-type" uk-tooltip="<?= $button["label"]?>">
                <img src="//maps.gstatic.com/mapfiles/place_api/icons/v2/<?= $button["icon"]?>_pinlet.svg" alt="<?= $button["label"]?>">
            </button>
        <?php endforeach ?>
        </div>
    </div>
    <div class="uk-position-relative">
        <div id="map" data-place="<?= $props['place_id'] ?>" style="width:100%; height:100%"></div>
        <div class="uk-position-absolute" style="right: 65px; bottom: 30px;">
          <div id="direction-info" class="uk-box-shadow-small uk-padding-small uk-background-default"></div>
        </div>
    </div>
</div>
<?= $el->end() ?>
