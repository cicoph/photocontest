<?php
echo UserModule::t("Ti sei registrato su {site_name}, ",array('{site_name}'=>Yii::app()->name)),UserModule::t("per attivare il tuo account fai click qui -> {activation_url}",array('{activation_url}'=>$activation_url));
?>