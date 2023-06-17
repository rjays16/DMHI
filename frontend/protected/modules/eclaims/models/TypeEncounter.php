<?php

/**
 * This is the model class for table "care_type_encounter".
 *
 * The followings are the available columns in table 'care_type_encounter':
 * @property string $type_nr
 * @property string $type
 * @property string $name
 * @property string $LD_var
 * @property string $description
 * @property integer $hide_from
 * @property string $status
 * @property string $history
 * @property string $modify_id
 * @property string $modify_time
 * @property string $create_id
 * @property string $create_time
 */

namespace SegHis\modules\eclaims\models;

class TypeEncounter extends \CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'care_type_encounter';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('history, modify_time', 'required'),
            array('hide_from', 'numerical', 'integerOnly'=>true),
            array('type, name, modify_id, create_id', 'length', 'max'=>35),
            array('LD_var, status', 'length', 'max'=>25),
            array('description', 'length', 'max'=>255),
            array('create_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type_nr, type, name, LD_var, description, hide_from, status, history, modify_id, modify_time, create_id, create_time', 'safe', 'on'=>'search'),
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
            'type_nr' => 'Type Nr',
            'type' => 'Type',
            'name' => 'Name',
            'LD_var' => 'Ld Var',
            'description' => 'Description',
            'hide_from' => 'Hide From',
            'status' => 'Status',
            'history' => 'History',
            'modify_id' => 'Modify',
            'modify_time' => 'Modify Time',
            'create_id' => 'Create',
            'create_time' => 'Create Time',
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

        $criteria=new \CDbCriteria;

        $criteria->compare('type_nr',$this->type_nr,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('LD_var',$this->LD_var,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('hide_from',$this->hide_from);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('history',$this->history,true);
        $criteria->compare('modify_id',$this->modify_id,true);
        $criteria->compare('modify_time',$this->modify_time,true);
        $criteria->compare('create_id',$this->create_id,true);
        $criteria->compare('create_time',$this->create_time,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TypeEncounter the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}