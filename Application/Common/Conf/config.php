<?php
$config_1= array(
	//'配置项'=>'配置值'
    /*网站配置*/
    'WEB_NAME'              =>'CRM管理控制台 | 凌众& 谋士',
    'WEB_VERSION'           =>'Alpha 10.0.1',


    /* 数据库设置
    'DB_TYPE'               =>  'pgsql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'crmx',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '',        // 端口
    'DB_PREFIX'             =>  'jd_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    */
    /* //PDO连接方式*/
    /*
     * 线上
   'DB_TYPE' => 'pgsql', // 数据库类型
   'DB_HOST'               =>  'rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com', // 服务器地址
   'DB_NAME'               =>  'crm',          // 数据库名
   'DB_USER' => 'rdspg', // 用户名
   'DB_PWD' => 'anmeng', // 密码
   'DB_PORT'               =>  '3432',        // 端口
   'DB_PREFIX' => 'jd_', // 数据库表前缀

    本地
   'DB_TYPE' => 'pgsql', // 数据库类型
   'DB_HOST'               =>  'localhost', // 服务器地址
   'DB_NAME'               =>  'crm',          // 数据库名
   'DB_USER' => 'postgres', // 用户名
   'DB_PWD' => '123456', // 密码
   'DB_PORT'               =>  '5432',        // 端口
   'DB_PREFIX' => 'jd_', // 数据库表前缀
*/
    'DB_TYPE' => 'pgsql', // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'crm',          // 数据库名
    'DB_USER' => 'postgres', // 用户名
    'DB_PWD' => '123456', // 密码
    'DB_PORT'               =>  '5432',        // 端口
    'DB_PREFIX' => 'jd_', // 数据库表前缀
   /*
   'DB_TYPE' => 'mongo', // 数据库类型
   'DB_HOST'               =>  '101.200.174.136', // 服务器地址
   'DB_NAME'               =>  'yushan',          // 数据库名
   'DB_USER' => 'yushan', // 用户名
   'DB_PWD' => 'yushan', // 密码
   'DB_PORT'               =>  '10222',        // 端口
   'DB_PREFIX' => '', // 数据库表前缀

   //'DB_DSN' => 'pgsql:host=localhost;port=5432;dbname=crm;',

   /* URL设置 */
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_PATHINFO_DEPR'     =>  '/',	// PATHINFO模式下，各参数之间的分割符号
    'URL_PATHINFO_FETCH'    =>  'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
    'URL_REQUEST_URI'       =>  'REQUEST_URI', // 获取当前页面地址的系统变量 默认为REQUEST_URI
    'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
    'URL_DENY_SUFFIX'       =>  'ico|png|gif|jpg', // URL禁止访问的后缀设置
    'URL_PARAMS_BIND'       =>  true, // URL变量绑定到Action方法参数
    'URL_PARAMS_BIND_TYPE'  =>  0, // URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
    'URL_PARAMS_FILTER'     =>  false, // URL变量绑定过滤
    'URL_PARAMS_FILTER_TYPE'=>  '', // URL变量绑定过滤方法 如果为空 调用DEFAULT_FILTER
    'URL_ROUTER_ON'         =>  false,   // 是否开启URL路由
    'URL_ROUTE_RULES'       =>  array(), // 默认路由规则 针对模块
    'URL_MAP_RULES'         =>  array(), // URL映射定义规则

    /* 默认设定 */
    'DEFAULT_M_LAYER'       =>  'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER'       =>  'Controller', // 默认的控制器层名称
    'DEFAULT_V_LAYER'       =>  'View', // 默认的视图层名称
    'DEFAULT_LANG'          =>  'zh-cn', // 默认语言
    'DEFAULT_THEME'         =>  '',	// 默认模板主题名称
    'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE'      =>  'PRC',	// 默认时区
    'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn', // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数...
    //'URL_CASE_INSENSITIVE' =>true,
    /*我的设置*/
    'Portrait'              => '/Uploads/portrait/wu.png', //默认头像地址
    'Upload_path'           =>'/Uploads',
    // 开启路由
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=>array(
       // 'news/:year/:month/:day' => array('News/archive', 'status=1'),
        'login'               => 'Admin/Public/login',
        'adminIndex'          => 'Admin/Public/index',
        //'sqladmin'               => 'Sqladmin/index',
        //'news/read/:id'          => '/news/:1',
        //销售接口
        array('Api/find_market_week_clientele','Admin/Api/find_market_week_clientele',array('method'=>'get')),//单个销售周新增客户
        array('Api/find_market_month_clientele','Admin/Api/find_market_month_clientele',array('method'=>'get')),//单个销售月新增客户
        array('Api/find_market_day_counsumption','Admin/Api/find_market_day_counsumption',array('method'=>'get')),//单个销售的所有客户昨日消耗
        array('Api/find_market_week_counsumption','Admin/Api/find_market_week_counsumption',array('method'=>'get')),//单个销售的所有客户本周消耗
        array('Api/find_market_month_counsumption','Admin/Api/find_market_month_counsumption',array('method'=>'get')),//单个销售的所有客户本月消耗
        array('Api/find_market_week_counsumption_statistics','Admin/Api/find_market_week_counsumption_statistics',array('method'=>'get')),//单个销售的所有客户周消耗趋势图
        array('Api/find_market_clientele_counsumption','Admin/Api/find_market_clientele_counsumption',array('method'=>'get')),//单个销售的所有客户周消耗趋势图
        //boss接口
        array('Api/contract_week','Admin/Api/contract_week',array('method'=>'get')),//周新增合同
        array('Api/contract_month','Admin/Api/contract_month',array('method'=>'get')),//月新增合同
        array('Api/contract_day','Admin/Api/contract_day',array('method'=>'get')),//昨日新增合同
        array('Api/find_market_smonth_counsumption','Admin/Api/find_market_smonth_counsumption',array('method'=>'get')),//单个销售的所有客户上月消耗
        array('Api/today_day_type','Admin/Api/today_day_type',array('method'=>'get')),// 根据type 返回 本日 回款 续费  垫款数据
        array('Api/today_month_type','Admin/Api/today_month_type',array('method'=>'get')),// 根据type 返回 本月 回款 续费  垫款数据
        array('Api/boss_money_type_list','Admin/Api/boss_money_type_list',array('method'=>'get')),// 根据type，date  返回 本日 或者本月 回款 续费  垫款 补款 数据列表
        //sem 接口
        array('Api/find_sem_day_counsumption','Admin/Api/find_sem_day_counsumption',array('method'=>'get')),//单个SEM的所有账户昨日消耗
        array('Api/find_sem_week_counsumption','Admin/Api/find_sem_week_counsumption',array('method'=>'get')),//单个SEM的所有账户本周消耗
        array('Api/find_sem_month_counsumption','Admin/Api/find_sem_month_counsumption',array('method'=>'get')),//单个SEM的所有账户本月消耗
        array('Api/sem_account_counsumption','Admin/Api/sem_account_counsumption',array('method'=>'get')),//单个SEM的所有账户列表消耗

        array('Api/diankuan_compare','Admin/Api/diankuan_compare',array('method'=>'get')),//垫款列表页——欠款公司最高的20条，并取得她的近五次回款记录
        array('Api/contract_date_list','Admin/Api/contract_date_list',array('method'=>'get')),//周新增合同
        array('Api/SpecifyDate_counsumption_list','Admin/Api/SpecifyDate_counsumption_list',array('method'=>'get')),//根据日期获取所有账户的日月周消耗
        //合同详情
        array('Api/company_contract_list','Admin/Api/company_contract_list',array('method'=>'get')),//根据公司id 列出公司所有合同
        array('Api/customer_info','Admin/Api/customer_info',array('method'=>'get')),//根据公司id 列出公司详情（客户详情）
        array('Api/customer_date_counsumption_line','Admin/Api/customer_date_counsumption_line',array('method'=>'get')),//根据公司id type 给出数据折线图

        //账户详情  日周月 消耗 SEM  公司，合同
        array('Api/account_date_counsumption_line','Admin/Api/account_date_counsumption_line',array('method'=>'get')),//根据账户id type 给出数据折线图
        array('Api/account_info','Admin/Api/account_info',array('method'=>'get')),//根据账户id 给出账户详细信息
        array('Api/account_chongzhi_recode','Admin/Api/account_chongzhi_recode',array('method'=>'get')),//根据账户ID获取最近8次充值记录
        array('Api/account_list_acinfo','Admin/Api/account_list_acinfo',array('method'=>'get')),//根据账户ID获取最近8次充值记录

        //部门消耗
        array('Api/sem_list','Admin/Api/sem_list',array('method'=>'get')),//sem 列表
        array('Api/market_list','Admin/Api/market_list',array('method'=>'get')),//销售列表
        array('Api/find_marker_contract_counsumption_list','Admin/Api/find_marker_contract_counsumption_list',array('method'=>'get')),//一个销售的合同列表
        array('Api/contract_date_counsumption_line','Admin/Api/contract_date_counsumption_line',array('method'=>'get')),//一个合同日周月列表
        array('Api/customer_yihuikuanxufei','Admin/Api/customer_yihuikuanxufei',array('method'=>'get')),

        array('Api/sem_account_counsumption_3_line_list','Admin/Api/sem_account_counsumption_3_line_list',array('method'=>'get')),//某个sem 所有账户三 日周月消耗
        array('Api/sem_account_counsumption_3_line','Admin/Api/sem_account_counsumption_3_line',array('method'=>'get')),//某个sem 所有账户三 日周月消耗方法
        array("Api/diankuan_excel","Admin/Api/diankuan_excel",array('method'=>'get')),//垫款导出表格
        array("Api/consumption_manual","Admin/Api/consumption_manual",array('method'=>'get')),//垫款导出表格
        array("Api/upusers","Admin/Api/upusers",array('method'=>'get')),//修改用户
        array("Api/account_server_type","Admin/Api/account_server_type",array('method'=>'get')),
        array("Api/consume_list_to_date","Admin/Api/consume_list_to_date",array('method'=>'get')),
        array("Api/set_account_users","Admin/Api/set_account_users",array('method'=>'get')),
        array("Api/get_appid_markert","Admin/Api/get_appid_markert",array('method'=>'get')),

    // array('Api','Admin/Api/index','hjd=1',array('method'=>'get')),

    ),
    'SHOW_PAGE_TRACE' =>false,
    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误

	'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭
     'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
     'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
     'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true

    /* 数据缓存设置 */
    'DATA_CACHE_TYPE'       =>  'Memcache',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'MEMCACHE_HOST' => '127.0.0.1',
    'MEMCACHE_PORT'	=>	'11211',
	);

return $config_1;