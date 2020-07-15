<?php


namespace test;


use com\bj58\zhuanzhuan\zzscf\application\Application;
use com\bj58\zhuanzhuan\zzscf\config\ApplicationConfig;
use com\bj58\zhuanzhuan\zzscf\config\Reference;
use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

class StudentService
{
    private $ref;

    public function __construct()
    {
        if (!Application::getInstance()) {
            $applicationConfig = new ApplicationConfig();
            $document = new \DOMDocument();
            $document->load('scf.config.xml');
            $applicationConfig->setLocalServiceRefConfigs(ReferenceConfigUtil::parserMultiFromXmlDOMDocument($document));
            Application::buildSingletonInstance($applicationConfig);
        }
        $refConfig = new Reference();
        $refConfig->setServiceName("servertest1");
        $refConfig->setContract(new StudentServiceContract());
        $this->ref = $refConfig->ref();
    }

    public function getStudent(int $id, string $name, int $age){
        return $this->ref->getStudent($id, $name, $age);
    }

    public function saveStudent(Student $student){
        return $this->ref->saveStudent($student);
    }




}