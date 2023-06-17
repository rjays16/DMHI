<?php

Yii::import('eclaims.models.cf4.Cf4');
Yii::import('eclaims.models.cf4.Cf4LibClinicalHistory');
Yii::import('eclaims.models.cf4.MedicalRiskFactor');
Yii::import('eclaims.services.CF4HeaderService');


class medicalRiskFactorService
{
    public $encounter_nr;
    public $entry_id;
    // public $menstrual;
    public function __construct($encounter_nr)
    {
        $this->encounter_nr = $encounter_nr;
    }

    public function getMedicalData()
    {
        $lib = new Cf4LibClinicalHistory();

        $data = $lib->getMedicalData();

        return $data;
    }

    public function getData()
    {

        $models = MedicalRiskFactor::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        foreach ($models as $key => $model) {
            $entries[$key] = $model->clinical_history_id;
        }

        return $entries;
    }

    public function save($data)
    {

        if (!$this->delete()) {
            throw new \Exception("Error in saving Medical Risk Factor(1)");
        }
        $this->entry_id = $this->getEntryId($data);
        $ok = $this->insert($data);

        if (!$ok) {
            throw new \Exception("Error in saving Medical Risk Factor");
        }
        return true;
    }

    public function getEntryId($data)
    {

        $service = new CF4HeaderService($data['encounter_nr'], $data['pid']);
        $service->save();


        return $service->getId();
    }

    public function delete()
    {
        $model = MedicalRiskFactor::model()->findAll(
            array(
                'condition' => "t.encounter_nr = :encounter_nr",
                'params' => array(
                    ':encounter_nr' => $this->encounter_nr
                )
            )
        );
        if (count($model) > 0) {
            foreach ($model as $m) {
                $model = MedicalRiskFactor::model()->findByPk($m->id);
                $model->is_deleted = 1;
                $ok = $model->save();

                if (!$ok) {
                    return false;
                }
            }
            return true;
        }

        return true;
    }

    public function insert($data)
    {
        if ($data['clinical_history_id']) {
            foreach ($data['clinical_history_id'] as $clinical) {
                $model = new MedicalRiskFactor;
                $model->id = $model->getUuid();
                $model->entry_id = $this->entry_id;
                $model->encounter_nr = $this->encounter_nr;
                $model->clinical_history_id = $clinical;
                $ok = $model->save();

                if (!$ok) {
                    return false;
                }
            }
        }


        return true;
    }
}
