<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class imageclozeformula_ui_style extends BaseQuestionTypeAttribute
{
    protected $fontsize;
    protected $validation_stem_numeration;
    protected $response_font_scale;
    protected $type;
    protected $min_width;
    protected $keyboard_below_response_area;

    public function __construct()
    {
    }

    /**
     * Get Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function get_fontsize()
    {
        return $this->fontsize;
    }

    /**
     * Set Font size \
     * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
     *
     * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
     */
    public function set_fontsize($fontsize)
    {
        $this->fontsize = $fontsize;
    }

    /**
     * Get Validation Stem Numeration \
     * Numeration character to be displayed to the left of the validation label. \
     *
     * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function get_validation_stem_numeration()
    {
        return $this->validation_stem_numeration;
    }

    /**
     * Set Validation Stem Numeration \
     * Numeration character to be displayed to the left of the validation label. \
     *
     * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
     */
    public function set_validation_stem_numeration($validation_stem_numeration)
    {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    /**
     * Get Response font scale \
     * This scales the font relative to the question's font size. \
     *
     * @return string $response_font_scale \
     */
    public function get_response_font_scale()
    {
        return $this->response_font_scale;
    }

    /**
     * Set Response font scale \
     * This scales the font relative to the question's font size. \
     *
     * @param string $response_font_scale \
     */
    public function set_response_font_scale($response_font_scale)
    {
        $this->response_font_scale = $response_font_scale;
    }

    /**
     * Get Type \
     * Keyboard style. See the <a href="//docs.learnosity.com/questionsapi/knowledgebase/formula_keyboard_uitypes.php" target="
     * _blank">knowledgebase article on formula keyboard types</a> for more information. \
     *
     * @return string $type \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Type \
     * Keyboard style. See the <a href="//docs.learnosity.com/questionsapi/knowledgebase/formula_keyboard_uitypes.php" target="
     * _blank">knowledgebase article on formula keyboard types</a> for more information. \
     *
     * @param string $type \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get Response minimum width \
     * Controls the minimum width of the response input area, e.g. 550px \
     *
     * @return string $min_width \
     */
    public function get_min_width()
    {
        return $this->min_width;
    }

    /**
     * Set Response minimum width \
     * Controls the minimum width of the response input area, e.g. 550px \
     *
     * @param string $min_width \
     */
    public function set_min_width($min_width)
    {
        $this->min_width = $min_width;
    }

    /**
     * Get Position floating keyboard below question response area \
     * If true, the floating formula keyboard will appear below the question as a whole (instead of below each individual math
     * editor in the question). \
     *
     * @return boolean $keyboard_below_response_area \
     */
    public function get_keyboard_below_response_area()
    {
        return $this->keyboard_below_response_area;
    }

    /**
     * Set Position floating keyboard below question response area \
     * If true, the floating formula keyboard will appear below the question as a whole (instead of below each individual math
     * editor in the question). \
     *
     * @param boolean $keyboard_below_response_area \
     */
    public function set_keyboard_below_response_area($keyboard_below_response_area)
    {
        $this->keyboard_below_response_area = $keyboard_below_response_area;
    }


}

