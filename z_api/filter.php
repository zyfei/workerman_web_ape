<?php
//在这里配置和在根目录下配置是相同的
$apeWeb->AddFilter ( "/api", \z_api\filter\Filter::login( $app ) );
