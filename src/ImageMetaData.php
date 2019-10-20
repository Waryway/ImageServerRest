<?php
namespace Waryway\ImageServerRest;

use Waryway\PhpTraitsLibrary\Hydrator;

/**
 * @OA\Schema()
 */
class ImageMetaData {
    use Hydrator;

    /**
     * The Image file name
     * @var string
     * @OA\Property()
     */
    private $name;

    /**
     * The size of the image
     * @var string
     * @OA\Property()
     */
    private $size;

    /**
     * The type of image
     * @var string
     * @OA\Property()
     */
    private $type;

    /**
     * The url to retrieve the image.
     * @var string
     * @OA\Property()
     */
    private $url;
}