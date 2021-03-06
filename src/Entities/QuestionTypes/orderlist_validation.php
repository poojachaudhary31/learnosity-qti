<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class orderlist_validation extends BaseQuestionTypeAttribute {
    protected $allow_negative_scores;
    protected $penalty;
    protected $min_score_if_attempted;
    protected $scoring_type;
    protected $unscored;
    protected $valid_response;
    protected $alt_responses;
    protected $rounding;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Allow negative scores \
    * Negative scores will be normalised to zero by default. Allowing negative scores, on the other hand, means that the score
	 can drop below zero when penalties are applied. \
    * @return boolean $allow_negative_scores \
    */
    public function get_allow_negative_scores() {
        return $this->allow_negative_scores;
    }

    /**
    * Set Allow negative scores \
    * Negative scores will be normalised to zero by default. Allowing negative scores, on the other hand, means that the score
	 can drop below zero when penalties are applied. \
    * @param boolean $allow_negative_scores \
    */
    public function set_allow_negative_scores ($allow_negative_scores) {
        $this->allow_negative_scores = $allow_negative_scores;
    }

    /**
    * Get Penalty point(s) \
    * Value indicating the marks deducted for an incorrect response. \
    * @return number $penalty \
    */
    public function get_penalty() {
        return $this->penalty;
    }

    /**
    * Set Penalty point(s) \
    * Value indicating the marks deducted for an incorrect response. \
    * @param number $penalty \
    */
    public function set_penalty ($penalty) {
        $this->penalty = $penalty;
    }

    /**
    * Get Minimum score if attempted \
    * Positive value indicating the minimum score if a student attempted the question. \
    * @return number $min_score_if_attempted \
    */
    public function get_min_score_if_attempted() {
        return $this->min_score_if_attempted;
    }

    /**
    * Set Minimum score if attempted \
    * Positive value indicating the minimum score if a student attempted the question. \
    * @param number $min_score_if_attempted \
    */
    public function set_min_score_if_attempted ($min_score_if_attempted) {
        $this->min_score_if_attempted = $min_score_if_attempted;
    }

    /**
    * Get Scoring type \
    * The way in which marks are distributed for this question. Possible options:<br><strong>'exactMatch'</strong>: Exact Matc
	h - All parts of the question must be answered correctly to receive a mark. <br><strong>'partialMatchV2'</strong>: Parti
	al Match - Each response entity will be scored individually, and the overall question score will be divided between the 
	response items.<br><strong>'partialMatch'</strong>: Partial Match Per Response - Each correct response entity will be aw
	arded an individual score.<br><strong>'partialMatchPairwise'</strong>: Partial Pairwise Per Response - Entities are comp
	ared in pairs to determine the score. \
    * @return string $scoring_type ie. exactMatch, partialMatch, partialMatchV2, partialMatchPairwise  \
    */
    public function get_scoring_type() {
        return $this->scoring_type;
    }

    /**
    * Set Scoring type \
    * The way in which marks are distributed for this question. Possible options:<br><strong>'exactMatch'</strong>: Exact Matc
	h - All parts of the question must be answered correctly to receive a mark. <br><strong>'partialMatchV2'</strong>: Parti
	al Match - Each response entity will be scored individually, and the overall question score will be divided between the 
	response items.<br><strong>'partialMatch'</strong>: Partial Match Per Response - Each correct response entity will be aw
	arded an individual score.<br><strong>'partialMatchPairwise'</strong>: Partial Pairwise Per Response - Entities are comp
	ared in pairs to determine the score. \
    * @param string $scoring_type ie. exactMatch, partialMatch, partialMatchV2, partialMatchPairwise  \
    */
    public function set_scoring_type ($scoring_type) {
        $this->scoring_type = $scoring_type;
    }

    /**
    * Get Unscored/Practice usage \
    * When enabled, this option will remove all scoring from the question. This is useful for creating practice questions. \
    * @return boolean $unscored \
    */
    public function get_unscored() {
        return $this->unscored;
    }

    /**
    * Set Unscored/Practice usage \
    * When enabled, this option will remove all scoring from the question. This is useful for creating practice questions. \
    * @param boolean $unscored \
    */
    public function set_unscored ($unscored) {
        $this->unscored = $unscored;
    }

    /**
    * Get Valid response \
    * An object containing the valid response score and value. \
    * @return orderlist_validation_valid_response $valid_response \
    */
    public function get_valid_response() {
        return $this->valid_response;
    }

    /**
    * Set Valid response \
    * An object containing the valid response score and value. \
    * @param orderlist_validation_valid_response $valid_response \
    */
    public function set_valid_response (orderlist_validation_valid_response $valid_response) {
        $this->valid_response = $valid_response;
    }

    /**
    * Get Alternate responses \
    * Add an alternate response if there is more than one correct overall solution to a question. \
    * @return array $alt_responses \
    */
    public function get_alt_responses() {
        return $this->alt_responses;
    }

    /**
    * Set Alternate responses \
    * Add an alternate response if there is more than one correct overall solution to a question. \
    * @param array $alt_responses \
    */
    public function set_alt_responses (array $alt_responses) {
        $this->alt_responses = $alt_responses;
    }

    /**
    * Get Rounding \
    * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
	plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
    * @return string $rounding \
    */
    public function get_rounding() {
        return $this->rounding;
    }

    /**
    * Set Rounding \
    * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
	plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
    * @param string $rounding \
    */
    public function set_rounding ($rounding) {
        $this->rounding = $rounding;
    }

    
}

