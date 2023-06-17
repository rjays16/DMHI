<?php

/**
 * This is the model class for table "seg_referral_diag_and_procedure".
 *
 * The followings are the available columns in table 'seg_referral_diag_and_procedure':
 * @property integer $id
 * @property string $accredit_nr
 * @property string $reason
 * @property string $enc_nr
 * @property string $icd_code
 * @property integer $is_deleted
 */
class ReferralDiagAndProcedure extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'seg_referral_diag_and_procedure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('accredit_nr, reason, enc_nr, icd_code', 'required'),
			array('is_deleted', 'numerical', 'integerOnly'=>true),
			array('accredit_nr, enc_nr, icd_code', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, accredit_nr, reason, enc_nr, icd_code, is_deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'accredit_nr' => 'Accredit Nr',
			'reason' => 'Reason',
			'enc_nr' => 'Enc Nr',
			'icd_code' => 'Icd Code',
			'is_deleted' => 'Is Deleted',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('accredit_nr',$this->accredit_nr,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('enc_nr',$this->enc_nr,true);
		$criteria->compare('icd_code',$this->icd_code,true);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReferralDiagAndProcedure the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
