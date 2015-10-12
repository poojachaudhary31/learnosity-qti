<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class clozeinlinetext_response_container extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $placeholder;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Height \
    * The height of the cloze response containers including units. Example: "100px" \
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height \
    * The height of the cloze response containers including units. Example: "100px" \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
    * Get Width \
    * The width of the cloze response containers including units. Example: "100px" \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width \
    * The width of the cloze response containers including units. Example: "100px" \
    * @param string $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
    * Get Placeholder \
    * Text to display as a hint to the user of what to enter \
    * @return string $placeholder \
    */
    public function get_placeholder() {
        return $this->placeholder;
    }

    /**
    * Set Placeholder \
    * Text to display as a hint to the user of what to enter \
    * @param string $placeholder \
    */
    public function set_placeholder ($placeholder) {
        $this->placeholder = $placeholder;
    }

    
}
