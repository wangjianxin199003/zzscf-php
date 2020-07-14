<?php

use com\bj58\zhuanzhuan\zzscf\util\ReferenceConfigUtil;

require '..\..\vendor\autoload.php';

ReferenceConfigUtil::parseSingleFromSimpleXmlString("<zzscf:referenceConfig serviceName=\"servertest1\"><zzscf:rpcArgs connections=\"1\" protocol=\"V2\" connectTimeout=\"2000\" timeout=\"1000\" heartBeat=\"3000\" idleTimeout=\"10000\" serialization=\"hessian2\"></zzscf:rpcArgs><zzscf:nodes><zzscf:node host=\"10.242.67.21\" port=\"16546\" version=\"1.2.0-SNAPSHOT\" systemEnv=\"testserver\" /></zzscf:nodes></zzscf:referenceConfig>");
