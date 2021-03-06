<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class formula_symbols_keyboard_item extends BaseQuestionTypeAttribute {
    protected $symbol;
    protected $title;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Symbol \
    * Latex symbol \
    * @return string $symbol \
    */
    public function get_symbol() {
        return $this->symbol;
    }

    /**
    * Set Symbol \
    * Latex symbol \
    * @param string $symbol \
    */
    public function set_symbol ($symbol) {
        $this->symbol = $symbol;
    }

    /**
    * Get Title \
    * Symbol title (optional) \
    * @return string $title \
    */
    public function get_title() {
        return $this->title;
    }

    /**
    * Set Title \
    * Symbol title (optional) \
    * @param string $title \
    */
    public function set_title ($title) {
        $this->title = $title;
    }

    
}

