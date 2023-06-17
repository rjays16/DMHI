<?php

use SegHis\modules\eclaims\helpers\cf4\CF4Helper;

Yii::import('eclaims.reports.Report');
Yii::import('eclaims.services.Cf4PrintOutService');

class Cf4PrintOutController extends Controller
{

    public function actionPrintCf4()
    {

        $service = new Cf4PrintOutService($_GET['encounter_nr']);

        $report_path = "/reports/cf4.jrxml";
        $reportClass = new Report;
        $data = array();

        if ($service->getCourseWards() == null) {
            $data = array_merge($this->forHeader(), $service->getMedicines());
        } else if ($service->getMedicines() == null) {
            $data = array_merge($service->getCourseWards(), $this->forHeader());
        } else {
            $data = array_merge($service->getCourseWards(), $this->forHeader(), $service->getMedicines());
        }

        $page_1 = array_merge(
            $service->getOBHistory(),
            $service->getPHICLogo(),
            $service->getHci(),
            $service->getEncounterInformation(),
            $service->getPresentIllness(),
            $service->getPastMedHistory(),
            $service->getChiefComplaint(),
            $service->getDischargeInformation()
        );

        $parameter = array(
            'path' => getcwd(),
            'logo_path' => getcwd() . '/images/phic_logo.png',
            'city' => CF4Helper::getHciCity(),
            'province' => CF4Helper::getHciProvince(),
            'zipcode' => CF4Helper::getHciZipCode(),
            'date_signed' => date('m-d-Y'),
            'pan' => CF4Helper::getAccreditationCode(),
            'building_name' => CF4Helper::getBuildingName(),
        );

        $pe = array_merge(
            $service->getSignSymptoms(),
            $service->getNeuro(),
            $service->getSkin(),
            $service->getGuie(),
            $service->getAbdomen(),
            $service->getHeart(),
            $service->getChest(),
            $service->getHeent(),
            $service->patientInfo(),
            $service->generalSurvey(),
            $service->vitalSigns(),
            $service->getEncounterResult(),
            $service->getEncounterDisposition()
        );

        $params = array_merge(
            $page_1,
            $pe,
            $parameter
        );

        $report = \Yii::createComponent(array(
            'class' => $reportClass,
            'template' => getcwd() . $report_path,
            'format' => '',
            'data' => $data,
            'params' => $params,

        ));
        $report->display();
    }


    public function forHeader()
    {
        $rowindex = 1;
        $data[$rowindex] = array(
            'for_header' => $rowindex,
        );
        return $data;
    }

}
