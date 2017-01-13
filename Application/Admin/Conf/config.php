<?php
return array(
	//'配置项'=>'配置值'
    'URL_ROUTE_RULES'=>array(

        array('Api/find_market_week_clientele/:id\d','Admin/Api/find_market_week_clientele',array('method'=>'get')),//单个销售周新增客户
        array('Api/find_market_month_clientele/:id\d','Admin/Api/find_market_month_clientele',array('method'=>'get')),//单个销售月新增客户
        // array('Api','Admin/Api/index','hjd=1',array('method'=>'get')),
    ),
);