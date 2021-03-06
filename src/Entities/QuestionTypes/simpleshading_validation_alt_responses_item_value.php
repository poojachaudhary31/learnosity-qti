<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class simpleshading_validation_alt_responses_item_value extends BaseQuestionTypeAttribute {
    protected $method;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Method \
    * Score the response based on exact locations. \
    * @return string $method ie. byLocation, byCount  \
    */
    public function get_method() {
        return $this->method;
    }

    /**
    * Set Method \
    * Score the response based on exact locations. \
    * @param string $method ie. byLocation, byCount  \
    */
    public function set_method ($method) {
        $this->method = $method;
    }

    
}

