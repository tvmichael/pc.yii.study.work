<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 29-Jul-19
 */

use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OL;
use yii\web\JsExpression;
?>

<style>
    .sibilino-map{
        width: 100%;
        height: 300px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">sibilino\yii2\openlayers\OpenLayers</div>
                <div class="panel-body">
                    <?
                    echo OpenLayers::widget([
                        'id' => 'sibilino-map',
                        'options'=>['class'=>'sibilino-map'],
                        'mapOptions' => [
                            'layers' => [
                                new OL('layer.Tile', [
                                    'source' => new OL('source.OSM', [
                                        'layer' => 'satMap1',
                                    ]),
                                ]),
                                //'Tile' => 'OSM',
                            ],
                            'view' => [
                                'center' => new JsExpression('ol.proj.transform([30.4899, 50.4922], "EPSG:4326", "EPSG:3857")'),
                                'zoom' => 12,

                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
