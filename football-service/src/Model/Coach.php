<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(required={"name"})
 */
class Coach extends Person
{
    /**
     * @SWG\Property(property="url_image", type="string", example="http://football-api.devks.msk.bg/assets/image.jpeg", description="277x338 full body image of Person")
     */
    protected static $assetTypes = [
        'image',
        'thumb'
    ];
}

