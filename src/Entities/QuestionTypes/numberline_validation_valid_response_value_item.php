<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class numberline_validation_valid_response_value_item extends BaseQuestionTypeAttribute
{
    protected $point;
    protected $position;

    public function __construct()
    {
    }

    /**
     * Get Point \
     * The point that should be validated. \
     *
     * @return string $point \
     */
    public function get_point()
    {
        return $this->point;
    }

    /**
     * Set Point \
     * The point that should be validated. \
     *
     * @param string $point \
     */
    public function set_point($point)
    {
        $this->point = $point;
    }

    /**
     * Get Position \
     * The position on the number line the point should represent. \
     *
     * @return string $position \
     */
    public function get_position()
    {
        return $this->position;
    }

    /**
     * Set Position \
     * The position on the number line the point should represent. \
     *
     * @param string $position \
     */
    public function set_position($position)
    {
        $this->position = $position;
    }


}

