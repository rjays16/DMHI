<?php

Yii::import('eclaims.models.cf4.Cf4PertinentSignSymptoms');
Yii::import('eclaims.models.cf4.Cf4LibChiefComplaint');
Yii::import('eclaims.models.cf4.Cf4Guie');
Yii::import('eclaims.models.cf4.Cf4LibGuie');
Yii::import('eclaims.models.cf4.Cf4Abdomen');
Yii::import('eclaims.models.cf4.Cf4LibAbdomen');
Yii::import('eclaims.models.cf4.Cf4VitalSigns');
Yii::import('eclaims.models.cf4.Cf4Heent');
Yii::import('eclaims.models.cf4.Cf4LibHeent');
Yii::import('eclaims.models.cf4.Cf4Chest');
Yii::import('eclaims.models.cf4.Cf4LibChest');
Yii::import('eclaims.models.cf4.Cf4Neuro');
Yii::import('eclaims.models.cf4.Cf4LibNeuro');
Yii::import('eclaims.models.cf4.Cf4Skin');
Yii::import('eclaims.models.cf4.Cf4LibSkin');
Yii::import('eclaims.models.cf4.Cf4Heart');
Yii::import('eclaims.models.cf4.Cf4LibHeart');
Yii::import('eclaims.models.cf4.Cf4GeneralSurvey');
Yii::import('eclaims.models.cf4.Cf4LibGenSurvey');
Yii::import('eclaims.models.cf4.Cf4PastMedHistory');
Yii::import('eclaims.models.cf4.Cf4ClinicalRecord');
Yii::import('eclaims.models.cf4.ObstetricHistory');
Yii::import('eclaims.models.cf4.MenstrualHistory');
Yii::import('eclaims.models.EclaimsPhicMember');
Yii::import('eclaims.models.EclaimsEncounter');
Yii::import('eclaims.models.ConfigGlobal');

use SegHis\modules\eclaims\models\ConfigGlobal;
use SegHis\modules\eclaims\models\TypeEncounter;
use SegHis\modules\eclaims\services\cf4\CF4DataService;
use SegHis\modules\eclaims\services\cf4\CF4Service;

/**
 *
 */
class Cf4PrintOutService
{
    public $encounter_nr;

    protected $service;

    protected $pid;

    public function __construct($encounter_nr)
    {
        $this->encounter_nr = $encounter_nr;
        $model = EclaimsEncounter::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );
        $this->pid = $model->pid;
        $this->service = new CF4DataService($model);
    }

    public function getPHICLogo()
    {
        $top_dir = 'frontend';
        $baseurl = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_ADDR'],
            substr(dirname($_SERVER["REQUEST_URI"]), 0, strpos($_SERVER["REQUEST_URI"], $top_dir))
        );
        #Logo of PHIC
        // $logo_path = $baseurl.'images/phic_logo.png'; #<-- Comment this for LOCAL TESTING!

        return array(
            "logo_path" => $logo_path,
        );
    }

    public function getHci()
    {
        $data = array();
        $addr_arr = array(
            'building_name',
            'city',
            'province',
        );

        $hci_name = ConfigGlobal::model()->findAllByAttributes(
            array(
                "type" => 'hie_service_hospital_name',
            )
        );

        $hci_addr = ConfigGlobal::model()->findAllByAttributes(
            array(
                "type" => 'main_info_address',
            )
        );

        $address = explode(",", $hci_addr[0]['value']);
        for ($i = 0; $i < count($address); $i++) {
            $data[$addr_arr[$i]] = $address[$i];
        }

        $data['hci_name'] = strtoupper($hci_name[0]['value']) . ".";

        return $data;
    }

    /**
     * @return array
     */
    public function getEncounterInformation()
    {
        $data = array();
        $model = EclaimsEncounter::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );

        $type = new TypeEncounter();

        $er = $type->findByAttributes(array(type => 'ER'))->type_nr;
        $opd = $type->findByAttributes(array(type => 'OPD'))->type_nr;

        $data['admission_date'] = $model->encounter_type == $er || $model->encounter_type == $opd ? date('m-d-Y h:i:a', strtotime($model->encounter_date)) : date('m-d-Y h:i:a', strtotime($model->admission_dt));
        $data['date_discharged'] = date('m-d-Y', strtotime($model->discharge_date)) . " " . date('h:i:a', strtotime($model->discharge_time));
        $data['admitting_diagnosis'] = $model->er_opd_diagnosis;

        $datetime1 = new DateTime($model->person->date_birth);
        $datetime2 = new DateTime($model->discharge_date);

        $diff = $datetime1->diff($datetime2);
        if ($diff->y >= 1) {
            $age = $diff->y . " year(s) " . $diff->m . " month(s)";
        } else {
            $age = " month(s) " . $diff->d . " day(s)";
        }
        // CVarDumper::dump($model->discharge_date, 10, true);die;

        $data['age'] = $age;

        return $data;
    }

    public function getPresentIllness()
    {
        $data = array();
        $model = Cf4ClinicalRecord::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $present = $model[$i]->present_illness;

            $data['present_illness'] = $present;
        }

        return $data;
    }

    public function getPastMedHistory()
    {
        $data = array();
        $model = Cf4PastMedHistory::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $pertinent = $model[$i]->pertinent;

            $data['medical_history'] = $pertinent;
        }

        return $data;
    }

    public function getOBHistory()
    {
        $data = array();
        $model = ObstetricHistory::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $gravida = $model[$i]->gravida;
            $parity = $model[$i]->parity;
            $t = $model[$i]->term_births;
            $p = $model[$i]->preterm_births;
            $a = $model[$i]->abortion;
            $l = $model[$i]->living_children;

            $data['date_gravity'] = $gravida;
            $data['date_parity'] = $parity;
            $data['T'] = $t;
            $data['P'] = $p;
            $data['A'] = $a;
            $data['L'] = $l;
        }

        $lmp_model = MenstrualHistory::model()->findAllByAttributes(
            array(
                "encounter_nr" => $this->encounter_nr,
            )
        );

        for ($i = 0; $i < count($lmp_model); $i++) {
            $lmp = $lmp_model[$i]->date_of_lmp;
            $is_applicable = $lmp_model[$i]->is_applicable;

            $data['last_period_menstrual'] = $lmp;
            $data['is_applicable'] = $is_applicable;
        }

        return $data;
    }

    public function getSignSymptoms()
    {
        $data = array();
        $symptoms = Cf4PertinentSignSymptoms::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );
        foreach ($symptoms as $key => $symptom) {
            $lib_model = Cf4LibChiefComplaint::model()->findByAttributes(
                array(
                    'id' => $symptom->sign_symptoms,
                )
            );
            if ($lib_model->ordering == "38") {
                $data['opt_2'] = "1";
                $data['opt_2_values'] = $symptom->pains;
            }
            $data['sign_and_symp_' . '' . $lib_model->ordering] = "1";
            $data['opt_3_values'] = $symptom->others;
            $data['opt_3'] = $symptom->others ? "1" : null;
        }

        return $data;
    }

    public function patientInfo()
    {
        $person = EclaimsPerson::model()->findByAttributes(
            array(
                'pid' => $this->pid,
            )
        );

        return array(
            'patient_name_last' => $person->name_last,
            'patient_name_first' => $person->name_first,
            'patient_name_middle' => $person->name_middle,
            'patient_name_suffix' => !empty($person->suffix) ? $person->suffix : '',
            'sex' => $person->sex,
            'pin' => CF4Service::getPatientPin($this->encounter_nr),
        );
    }

    public function generalSurvey()
    {
        $model = Cf4GeneralSurvey::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        $lib_model = Cf4LibGenSurvey::model()->findByPk(
            array(
                'id' => $model->gen_survey_id,
            )
        );
        return ($lib_model->id === "1") ? array('finding_1' => "1") : array('finding_2' => "1", 'value_2_Ge' => $model->remarks);
    }

    public function getChiefComplaint()
    {

        $chiefComplaint = $this->service->getChiefComplaint();

        return $chiefComplaint ? $chiefComplaint : array();
    }

    public function getMedicines()
    {
        $meds = $this->service->getMedicines();

        foreach ($meds as $key => $value) {
            $drug = $this->getPhilMedData($value['drug_code']);
            $strength = $drug['strength_code'] ? $this->getPhilMedStrength($drug['strength_code']) : null;
            $form = $drug['form_code'] ? $this->getPhilMedForm($drug['form_code']) : null;
            $drugs[] = array(
                'total_cost' => $value['cost'],
                'strength_desc' => $strength ? $strength['strength_desc'] : "",
                'form_desc' => $form ? $form['form_desc'] : "",
                'route' => $value['route'],
                'quantity' => $value['quantity'],
                'frequency' => $value['frequency'],
                'generic_name' => $value['generic'],

            );
        }
        return $drugs;
    }

    protected function getPhilMedData($drugCode)
    {

        $command = \Yii::app()->db->createCommand();
        $command->from('seg_phil_medicine t');
        $command->where('t.drug_code = :drugCode');
        $command->params[':drugCode'] = $drugCode;
        return $command->queryRow();
    }

    public function getPhilMedStrength($strengthCode)
    {
        $command = \Yii::app()->db->createCommand();
        $command->from('seg_phil_medicine_strength t');
        $command->where('t.strength_code = :strengthCode');
        $command->params[':strengthCode'] = $strengthCode;
        return $command->queryRow();
    }

    public function getPhilMedForm($formCode)
    {
        $command = \Yii::app()->db->createCommand();
        $command->from('seg_phil_medicine_form t');
        $command->where('t.form_code = :formCode');
        $command->params[':formCode'] = $formCode;
        return $command->queryRow();
    }

    public function getFinalDiagnosis()
    {
        return $this->service->getFinalDiagnosis();
    }

    public function getCaseRates()
    {
        return $this->service->getCaseRates();
    }

    public function vitalSigns()
    {

        $model = Cf4VitalSigns::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            ),
            array('order' => 'created_at DESC')
        );

        $data = array(
            'vital_bp' => $model ? "{$model->systolic} / {$model->diastolic} mmhg" : null,
            'vital_hr' => $model ? "{$model->cr} / min" : null,
            'vital_rr' => $model ? "{$model->rr} breaths/m" : null,
            'vital_temp' => $model ? "{$model->temperature} " : null,
            'weight' => $model->weight,
            'height' => $model->height,
        );

        return $data;
    }

    public function getCourseWards()
    {
        $courseWards = $this->service->getCourseWards();

        $data = array();
        foreach ($courseWards as $courseWard) {
            $data[] = array(
                'requestDate' => date('m-d-Y', strtotime($courseWard['date_action'])),
                'remarks' => $courseWard['doctor_action'],
            );
        }
        return $data;
    }

    public function getHeent()
    {

        $data = array();
        $model = Cf4Heent::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->heent_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibHeent::model()->findByAttributes(array('id' => $id));

            $data['heent_finding_' . '' . $id] = "1";
            $data['finding_' . $lib_model->param_value] = "1";
            $data['finding_others_HE'] = $id == 99 ? "1" : null;

            $data['value_others_HE'] = $remarks;
        }

        return $data;
    }

    public function getChest()
    {
        $data = array();
        $model = Cf4Chest::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->chest_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibChest::model()->findByAttributes(array('id' => $id));
            $data['finding_' . $lib_model->param_value] = "1";
            $data['chest_finding_' . '' . $id] = "1";
            $data['finding_others_Ch'] = $lib_model->id == 99 ? "1" : null;
            $data['value_others_Ch'] = $remarks;
        }
        return $data;
    }

    public function getHeart()
    {
        $data = array();
        $model = Cf4Heart::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->heart_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibHeart::model()->findByAttributes(
                array(
                    'id' => $id,
                )
            );
            $data['finding_' . $lib_model->param_value] = "1";
            $data['cv_finding_' . '' . $id] = "1";
            $data['finding_others_CV'] = $lib_model->id == 99 ? "1" : null;

            $data['value_others_CV'] = $remarks;
        }

        return $data;
    }

    public function getAbdomen()
    {
        $data = array();
        $model = Cf4Abdomen::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,

            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->abdomen_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibAbdomen::model()->findByAttributes(
                array(
                    'id' => $id,
                )
            );
            $data['finding_' . $lib_model->param_value] = "1";
            $data['abdomen_finding_' . '' . $id] = "1";
            $data['finding_others_AB'] = $model[$i]->abdomen_id == 99 ? "1" : null;
            $data['value_others_AB'] = $remarks;
        }

        return $data;
    }

    public function getGuie()
    {
        $data = array();
        $model = Cf4Guie::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->guie_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibGuie::model()->findByAttributes(
                array(
                    'id' => $id,
                )
            );
            $data['finding_' . $lib_model->param_value] = "1";
            $data['guie_finding_' . '' . $id] = "1";
            $data['finding_others_GU'] = $id == "99" ? "1" : null;
            $data['value_others_GU'] = $remarks;
        }

        return $data;
    }

    public function getSkin()
    {
        $data = array();
        $model = Cf4Skin::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->skin_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibSkin::model()->findByAttributes(
                array(
                    'id' => $id,
                )
            );
            $data['finding_' . $lib_model->param_value] = "1";

            $data['skin_finding_' . '' . $id] = "1";
            $data['finding_others_SK'] = $lib_model->id == 99 ? "1" : null;
            $data['value_others_SK'] = $remarks;
        }

        return $data;
    }

    public function getNeuro()
    {
        $data = array();
        $model = Cf4Neuro::model()->findAllByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
                'is_deleted' => 0,
            )
        );

        for ($i = 0; $i < count($model); $i++) {
            $id = $model[$i]->neuro_id;
            $remarks = $model[$i]->remarks;
            $lib_model = Cf4LibNeuro::model()->findByAttributes(
                array(
                    'id' => $id,
                )
            );
            $data['finding_' . $lib_model->param_value] = "1";
            $data['ne_finding_' . '' . $id] = "1";
            $data['finding_others_NE'] = $id == 99 ? "1" : null;
            $data['value_others_NE'] = $remarks;
        }

        return $data;
    }

    public function getDischargeInformation()
    {
        $data = array();
        $diagnosis = '';
        //for case rate
        $case_sql = "SELECT
                      sbc.`bill_nr`,
                      sbc.`package_id`,
                      sbc.`rate_type`,
                      scrp.`case_type`
                    FROM
                      seg_billing_encounter AS sbe
                      INNER JOIN seg_billing_caserate AS sbc
                        ON sbe.bill_nr = sbc.bill_nr
                      INNER JOIN seg_case_rate_packages AS scrp
                        ON sbc.package_id = scrp.`code`
                    WHERE sbe.`encounter_nr` = $this->encounter_nr
                    AND sbe.`is_deleted` IS NULL
                    GROUP BY sbc.`package_id` ";

        $command = \Yii::app()->db->createCommand($case_sql);
        $case_res = $command->queryAll();

        foreach ($case_res as $key => $case) {
            $code = $case['package_id'];
            $rate_type = $case['rate_type'];
            $case_type = $case['case_type'];
            if ($rate_type == "1") {
                $data['first_case'] = $case['package_id'];
            } else {
                $data['second_case'] = $case['package_id'];
            }

            if ($case_type == "p") {
                $dsc_sql = "SELECT
                              smod.`description`
                            FROM
                              seg_misc_ops AS smo
                              INNER JOIN
                                (SELECT
                                  `refno`,
                                  MAX(modify_dt) AS MaxDate,
                                  encounter_nr
                                FROM
                                  seg_misc_ops
                                GROUP BY refno) AS smoRef
                                ON smo.`modify_dt` = smoRef.MaxDate
                                AND smo.`refno` = smoRef.refno
                              INNER JOIN seg_misc_ops_details AS smod
                                ON smo.`refno` = smod.`refno`
                            WHERE smo.`encounter_nr` = $this->encounter_nr
                            AND smod.`ops_code` = '$code'
                            ORDER BY smo.`modify_dt` DESC
                            LIMIT 2 ";

                $command = \Yii::app()->db->createCommand($dsc_sql);
                $dsc_res = $command->queryAll();

                if ($dsc_res) {
                    foreach ($dsc_res as $key => $dscRes) {
                        if ($desc == null) {
                            $desc = $dscRes['description'];
                            $diagnosis = $dscRes['description'];
                            //                    $params->put('discharge_diagnosis',$dscRes['description']."<br>");
                        } else {
                            if ($rate_type != 1) {
                                //                        $params->put('discharge_diagnosis',$desc."<br>".$dscRes['description']."<br>");
                                $diagnosis = $desc . '<br>' . $dscRes['description'];
                            } else {
                                //                        $params->put('discharge_diagnosis',$dscRes['description']."<br>".$desc);
                                $diagnosis = $dscRes['description'] . '<br>' . $desc;
                            }
                        }
                    }
                }
            } else {

                $dsc_sql = "SELECT
                              sed.`description`,
                              sed.type_nr
                            FROM
                              seg_encounter_diagnosis AS sed
                            WHERE sed.`encounter_nr` = '$this->encounter_nr'
                              AND sed.`code` = '$code'
                              AND sed.is_deleted = 0";
                $command = \Yii::app()->db->createCommand($dsc_sql);
                $dsc_res = $command->queryAll();

                foreach ($dsc_res as $key => $res) {

                    if ($desc == null) {
                        $desc = $res['description'];
                        //                $params->put('discharge_diagnosis', $res['description']."<br>");
                        $diagnosis = $res['description'];
                    } else {
                        if ($rate_type != 1) {
                            //                    $params->put('discharge_diagnosis',$desc."<br>".$res['description']."<br>");
                            $diagnosis = $desc . '<br>' . $res['description'];
                        } else {
                            //                    $params->put('discharge_diagnosis',$res['description']."<br>".$desc);
                            $diagnosis = $res['description'] . '<br>' . $desc;
                        }
                    }
                }
            }
        }
        $other_diag = '';
        $other = $this->getOtherDiagnosis($code);
        $misc = $this->getMisc();
        while ($row = $other->FetchRow()) {
            if ($other_diag) {
                $other_diag .= '<br>' . $row['description'];
            } else {
                $other_diag = $row['description'];
            }
        }
        $other_diag .= '<br>' . $misc;

        $data['discharge_diagnosis'] = strtoupper($diagnosis) . '<br>' . strtoupper($other_diag);

        return $data;
    }

    public function getOtherDiagnosis($code)
    {
        global $db;
        $other_sql = "SELECT
                          description
                        FROM
                          seg_encounter_diagnosis
                        WHERE encounter_nr = '$this->encounter_nr'
                          AND code != '$code'
                          AND is_deleted = 0 ";
        if ($result = $db->Execute($other_sql)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getMisc()
    {
        global $db;
        $misc = "";
        $misc_sql = "SELECT
            smod.`description`
            FROM
              seg_misc_ops AS smo
              LEFT JOIN seg_misc_ops_details AS smod
                ON smo.`refno` = smod.`refno`
            WHERE smo.`modify_dt` IN
              (SELECT
                MAX(smo.`modify_dt`)
              FROM
                seg_misc_ops AS smo)
                AND smo.`encounter_nr` = '$this->encounter_nr'";
        $misc_res = $db->Execute($misc_sql);
        while ($row = $misc_res->FetchRow()) {
            if ($misc) {
                $misc .= '<br>' . $row['description'];
            } else {
                $misc = $row['description'];
            }
        }
        return $misc;
    }

    //added by Juna 2021
    public function getEncounterResult()
    {
        $model = EncounterResult::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr,
            )
        );

        $data = array(

            'result' => $model->result_code,
        );

        return $data;
    }

    public function getEncounterDisposition()
    {

        $model = EncounterDisposition::model()->findByAttributes(
            array(
                'encounter_nr' => $this->encounter_nr
            )
        );

        $data = array(

            'disposition' => $model->disp_code,
        );

        return $data;
    }
}
