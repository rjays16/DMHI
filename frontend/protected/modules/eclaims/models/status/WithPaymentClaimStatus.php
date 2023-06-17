<?php

/**
 *
 * @author  Ma. Dulce O. Polinar  <dulcepolinar1010@gmail.com>
 * @copyright (c) 2014, Segworks Technologies Corporation (http://www.segworks.com)
 *
 */

/**
 * This is the model class for table "seg_eclaims_with_payment_claim_status".
 *
 * The followings are the available columns in table 'seg_eclaims_with_payment_claim_status':
 * @property integer $id
 * @property integer $status_id
 * @property double $total_amount_paid
 *
 */
class WithPaymentClaimStatus extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'seg_eclaims_with_payment_claim_status';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'claimStatus' => array(self::BELONGS_TO, 'ClaimStatus', 'status_id'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WithPaymentClaimStatus the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
