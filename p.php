<?php
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ob_start();

$mysqlReShow = "none";
$mailReShow = "none";
$funReShow = "none";
$opReShow = "none";
$sysReShow = "none";

define("YES", "<span class='resYes'>YES</span>");
define("NO", "<span class='resNo'>NO</span>");
define("ICON", "<span class='icon'>2</span>&nbsp;");
$phpSelf = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define("PHPSELF", preg_replace("/(.{0,}?\/+)/", "", $phpSelf));

$act = $_REQUEST['act'] ?? '';
if ($act == "phpinfo") {
	phpinfo();
	exit();
} elseif ($act == "CONNECT") {
	$mysqlReShow = "show";
	$mysqlRe = "MYSQL连接测试结果：";
	$mysqlRe .= (false !== @mysqli_connect($_POST['mysqlHost'], $_POST['mysqlUser'], $_POST['mysqlPassword'], $_POST['mysqlDb'])) ? "MYSQL服务器连接正常, " :
		"MYSQL服务器连接失败, ";
} elseif ($act == "SENDMAIL") {
	$mailReShow = "show";
	$mailRe = "MAIL邮件发送测试结果：发送";
	$mailRe .= (false !== @mail($_POST["mailReceiver"], "MAIL SERVER TEST", "This is a test email.")) ? "完成" : "失败";
} elseif ($act == "FUNCTION_CHECK") {
	$funReShow = "show";
	$funRe = "函数 <b>" . $_POST['funName'] . "</b> 支持状况检测结果：" . isfun($_POST['funName']);
} elseif ($act == "CONFIGURATION_CHECK") {
	$opReShow = "show";
	$opRe = "配置参数 <b>" . $_POST['opName'] . "</b> 检测结果：" . getcon($_POST['opName']);
}


// 系统参数


switch (PHP_OS) {
	case "Linux":
		$sysReShow = (false !== ($sysInfo = sys_linux())) ? "show" : "none";
		break;
	default:
		break;
}

/*========================================================================*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>大芒果PHP探针</title>
	<style type="text/css">
		/* ========== Reset & Base ========== */
		*, *::before, *::after { box-sizing: border-box; }
		body, div, p, ul, form, h1, table { margin: 0; padding: 0; }

		body {
			background: #0d1117;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans SC", Roboto, "Helvetica Neue", Arial, sans-serif;
			font-size: 16px;
			color: #c9d1d9;
			line-height: 1.6;
			-webkit-font-smoothing: antialiased;
		}

		div {
			max-width: 1200px;
			margin: 0 auto;
			padding: 0 24px;
		}

		/* ========== Links ========== */
		a {
			color: #58a6ff;
			text-decoration: none;
			transition: color 0.15s ease;
		}
		a:hover {
			color: #79c0ff;
			text-decoration: underline;
		}
		a.arrow {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 24px; height: 24px;
			font-size: 14px; font-weight: 700;
			color: #8b949e;
			background: #21262d;
			border-radius: 6px;
			transition: all 0.15s ease;
		}
		a.arrow:hover {
			color: #58a6ff;
			background: #30363d;
			text-decoration: none;
		}

		/* ========== YES / NO Badges ========== */
		.resYes, .resNo {
			display: inline-block;
			padding: 2px 10px;
			font-size: 13px;
			font-weight: 700;
			border-radius: 10px;
			letter-spacing: 0.5px;
		}
		.resYes { color: #3fb950; background: rgba(63,185,80,0.12); border: 1px solid rgba(63,185,80,0.25); }
		.resNo  { color: #f85149; background: rgba(248,81,73,0.12);  border: 1px solid rgba(248,81,73,0.25); }

		/* ========== Input ========== */
		input[type="text"] {
			background: #0d1117;
			border: 1px solid #30363d;
			border-radius: 6px;
			padding: 6px 12px;
			color: #c9d1d9;
			font-size: 15px;
			font-family: inherit;
			transition: border-color 0.2s, box-shadow 0.2s;
			outline: none;
		}
		input[type="text"]:focus {
			border-color: #58a6ff;
			box-shadow: 0 0 0 3px rgba(88,166,255,0.15);
		}
		input[type="text"]:disabled {
			opacity: 0.35;
			cursor: not-allowed;
		}

		/* ========== Button ========== */
		.myButton {
			background: #21262d;
			border: 1px solid #30363d;
			color: #c9d1d9;
			font-size: 14px;
			font-weight: 600;
			padding: 6px 16px;
			border-radius: 6px;
			cursor: pointer;
			font-family: inherit;
			transition: all 0.15s ease;
		}
		.myButton:hover {
			background: #30363d;
			border-color: #8b949e;
		}
		.myButton:disabled {
			opacity: 0.35;
			cursor: not-allowed;
		}

		/* ========== Progress Bar ========== */
		.bar {
			display: inline-block;
			width: 130px;
			height: 8px;
			background: #21262d;
			border: 1px solid #30363d;
			border-radius: 4px;
			overflow: hidden;
			vertical-align: middle;
			margin: 0 6px;
		}
		.bar li {
			height: 8px;
			background: linear-gradient(90deg, #3fb950, #238636);
			border-radius: 4px;
			list-style: none;
			font-size: 0;
			transition: width 0.4s ease;
		}

		/* ========== Tables ========== */
		table {
			width: 100%;
			border-collapse: separate;
			border-spacing: 0;
			background: #161b22;
			border: 1px solid #30363d;
			border-radius: 8px;
			overflow: hidden;
			margin-bottom: 20px;
		}
		td, th {
			padding: 10px 16px;
			text-align: left;
			border-bottom: 1px solid #21262d;
			font-size: 15px;
		}
		tr:last-child td { border-bottom: none; }

		th {
			background: #161b22;
			color: #e6edf3;
			font-weight: 600;
		}
		th span {
			font-size: 16px;
			font-weight: 400;
			padding-right: 6px;
			opacity: 0.6;
		}
		th p {
			float: right;
			line-height: 16px;
			text-align: right;
			margin: 0;
		}
		th p a {
			color: #484f58;
			font-size: 14px;
		}
		th p a:hover { color: #58a6ff; }

		td { background: #0d1117; color: #c9d1d9; }

		/* ========== Navigation Tabs ========== */
		#t3 { margin-top: 12px; }
		#t3 td {
			background: #161b22;
			border: 1px solid #21262d;
			border-right: none;
			text-align: center;
			padding: 10px 0;
			font-weight: 500;
		}
		#t3 td:last-child { border-right: 1px solid #21262d; }
		#t3 td a { color: #8b949e; font-size: 13px; }
		#t3 td a:hover { color: #58a6ff; text-decoration: none; }

		/* ========== Section Headers ========== */
		.th2 th, .th3 {
			background: #1c2128;
			color: #e6edf3;
			font-weight: 600;
			text-align: left;
			font-size: 13px;
		}
		.th3 { background: #1c2128; border-bottom-color: #30363d; }

		/* ========== Title ========== */
		h1 {
			color: #3fb950;
			font-size: 28px;
			font-weight: 700;
			float: left;
			padding: 20px 0 12px 0;
			line-height: 1.3;
		}
		h1 b {
			color: #f85149;
			font-size: 42px;
			font-weight: 400;
			vertical-align: -2px;
		}
		h1 span {
			display: block;
			font-size: 12px;
			font-weight: 400;
			color: #8b949e;
			padding-top: 2px;
		}

		/* ========== Top-right Info ========== */
		#t12 {
			float: right;
			text-align: right;
			padding: 20px 0 24px 0;
			font-size: 12px;
			color: #8b949e;
		}
		#t12 a { color: #8b949e; font-size: 12px; }

		/* ========== Footer ========== */
		#footer table { clear: none; margin-bottom: 0; }
		#footer td {
			text-align: center;
			padding: 4px 6px;
			font-size: 11px;
			color: #484f58;
			background: transparent;
		}
		#footer a { font-size: 11px; color: #484f58; }
		#f1 { text-align: right; padding: 20px 0; }
		#f2 { float: left; border: 1px solid #30363d; }
		#f2 td { background: #d29922; }
		#f2 a { color: #fff; }
		#f3 { border: 1px solid #30363d; float: right; }
		#f3 a { color: #c9d1d9; }
		#f31 { background: #1f6feb; color: #fff; }
		#f32 { background: #21262d; }
	</style>
</head>

<body>
	<br />
	<form method="post" action="<?= PHPSELF . "#bottom" ?>">
		<div>
			<!-- =============================================================页头 ============================================================= -->
			<a name="top"></a>
			<table width="100%" border="0" cellspacing="1" cellpadding="0" id="t3">
				<tr>
					<td><a href="#sec1">服务器特征</a></td>
					<td><a href="#sec2">PHP基本特征</a></td>
					<td><a href="#sec3">PHP组件支持状况</a></td>
					<td><a href="#sec4">自定义检测</a></td>
					<td><a href="<?= PHPSELF ?>" class="t211">刷新</a></td>
					<td><a href="#bottom" class="arrow">66</a></td>
				</tr>
			</table>
			<!-- =============================================================服务器特性 ============================================================= -->
			<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr>
					<th colspan="2">
						<p>
							<a href="#top" class="arrow">5</a>
							<br />
							<a href="#bottom" class="arrow">6</a>
						</p>
						<span>8</span>服务器特性
						<a name="sec1" id="sec1"></a>
					</th>
				</tr>
				<?php if ("show" == $sysReShow) { ?>
					<tr>
						<td>CPU个数</td>
						<td><?= $sysInfo['cpu']['num'] ?></td>
					</tr>
					<tr>
						<td>CPU型号</td>
						<td><?= $sysInfo['cpu']['model'] ?></td>
					</tr>
					<tr>
						<td>CPU二级缓存</td>
						<td><?= $sysInfo['cpu']['cache'] ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td>服务器时间</td>
					<td><?= date("Y年n月j日 H:i:s") ?>
						&nbsp;北京时间：
						<?= gmdate("Y年n月j日 H:i:s", time() + 8 * 3600) ?></td>
				</tr>
				<?php if ("show" == $sysReShow) { ?>
					<tr>
						<td>服务器运行时间</td>
						<td><?= $sysInfo['uptime'] ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td>服务器域名/IP地址</td>
					<td><?= $_SERVER['SERVER_NAME'] ?>
						(
						<?= @gethostbyname($_SERVER['SERVER_NAME']) ?>
						)</td>
				</tr>
				<tr>
					<td>服务器操作系统
						<?php $os = explode(" ", php_uname()); ?></td>
					<td><?= $os[0]; ?>
						&nbsp;内核版本：
						<?= $os[2] ?></td>
				</tr>
				<tr>
					<td>主机名称</td>
					<td><?= $os[1]; ?></td>
				</tr>
				<tr>
					<td>服务器解译引擎</td>
					<td><?= $_SERVER['SERVER_SOFTWARE'] ?></td>
				</tr>
				<tr>
					<td>Web服务端口</td>
					<td><?= $_SERVER['SERVER_PORT'] ?></td>
				</tr>
				<tr>
					<td>服务器语言</td>
					<td><?php echo getenv("HTTP_ACCEPT_LANGUAGE"); ?></td>
				</tr>
				<tr>
					<td>服务器管理员</td>
					<td><?php $admin = $_SERVER['SERVER_ADMIN'] ?? '';
						if ($admin): ?>
							<a href="mailto:<?= $admin ?>"><?= $admin ?></a>
						<?php else: echo '无';
						endif; ?>
					</td>
				</tr>
				<tr>
					<td>本文件路径</td>
					<td><?= $_SERVER['DOCUMENT_ROOT'] . "<br />" . ($_SERVER['PATH_INFO'] ?? '') ?></td>
				</tr>
				<tr>
					<td>目前还有空余空间&nbsp;diskfreespace</td>
					<td><?= round((@disk_free_space(".") / (1024 * 1024)), 2) ?>
						M</td>
				</tr>
				<?php if ("show" == $sysReShow) { ?>
					<tr>
						<td>内存使用状况</td>
						<td> 物理内存：共
							<?= $sysInfo['memTotal'] ?>
							M, 已使用
							<?= $sysInfo['memUsed'] ?>
							M, 空闲
							<?= $sysInfo['memFree'] ?>
							M, 使用率
							<?= $sysInfo['memPercent'] ?>
							%
							<?= bar($sysInfo['memPercent']) ?>
							Cache化内存为
							<?= $sysInfo['memCached'] ?>
							M, 真实内存使用率为
							<?= $sysInfo['memRealPercent'] ?>
							%
							<?= bar($sysInfo['memRealPercent']) ?>
							SWAP区：共
							<?= $sysInfo['swapTotal'] ?>
							M, 已使用
							<?= $sysInfo['swapUsed'] ?>
							M, 空闲
							<?= $sysInfo['swapFree'] ?>
							M, 使用率
							<?= $sysInfo['swapPercent'] ?>
							%
							<?= bar($sysInfo['swapPercent']) ?>
						</td>
					</tr>
					<tr>
						<td>系统平均负载</td>
						<td><?= $sysInfo['loadAvg'] ?></td>
					</tr>
				<?php } ?>
			</table>
			<!-- ============================================================= PHP基本特性 ============================================================== -->
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<th colspan="2">
						<p>
							<a href="#top" class="arrow">5</a>
							<br />
							<a href="#bottom" class="arrow">6</a>
						</p>
						<span>8</span>PHP基本特性
						<a name="sec2" id="sec2"></a>
					</th>
				</tr>
				<tr>
					<td width="49%">PHP运行方式</td>
					<td width="51%"><?= strtoupper(php_sapi_name()) ?></td>
				</tr>
				<tr>
					<td>PHP版本</td>
					<td><?= PHP_VERSION ?></td>
				</tr>
				<tr>
					<td>运行于安全模式</td>
					<td><?= getcon("safe_mode") ?></td>
				</tr>
				<tr>
					<td>支持Zend OPcache</td>
					<td><?= (function_exists('opcache_get_status') && opcache_get_status() !== false) ? YES : NO ?></td>
				</tr>
				<tr>
					<td>允许使用URL打开文件&nbsp;allow_url_fopen</td>
					<td><?= getcon("allow_url_fopen") ?></td>
				</tr>
				<tr>
					<td>允许动态加载链接库&nbsp;enable_dl</td>
					<td><?= getcon("enable_dl") ?></td>
				</tr>
				<tr>
					<td>显示错误信息&nbsp;display_errors</td>
					<td><?= getcon("display_errors") ?></td>
				</tr>
				<tr>
					<td>自动定义全局变量&nbsp;register_globals</td>
					<td><?= getcon("register_globals") ?></td>
				</tr>
				<tr>
					<td>程序最多允许使用内存量&nbsp;memory_limit</td>
					<td><?= getcon("memory_limit") ?></td>
				</tr>
				<tr>
					<td>POST最大字节数&nbsp;post_max_size</td>
					<td><?= getcon("post_max_size") ?></td>
				</tr>
				<tr>
					<td>允许最大上传文件&nbsp;upload_max_filesize</td>
					<td><?= getcon("upload_max_filesize") ?></td>
				</tr>
				<tr>
					<td>程序最长运行时间&nbsp;max_execution_time</td>
					<td><?= getcon("max_execution_time") ?>
						秒</td>
				</tr>
				<tr>
					<td>magic_quotes_gpc</td>
					<td><?= (function_exists('get_magic_quotes_gpc') && 1 === get_magic_quotes_gpc()) ? YES : NO ?></td>
				</tr>
				<tr>
					<td>magic_quotes_runtime</td>
					<td><?= (function_exists('get_magic_quotes_runtime') && 1 === get_magic_quotes_runtime()) ? YES : NO ?></td>
				</tr>
				<tr>
					<td>被禁用的函数&nbsp;disable_functions</td>
					<td><?= ("" == ($disFuns = get_cfg_var("disable_functions"))) ? "无" : str_replace(",", "<br />", $disFuns) ?></td>
				</tr>
				<tr>
					<td>PHP信息&nbsp;PHPINFO</td>
					<td><?= (false !== stripos($disFuns, "phpinfo")) ? NO : "<a href='$phpSelf?act=phpinfo' target='_blank' class='static'>PHPINFO</a>" ?></td>
				</tr>
			</table>
			<!-- ============================================================= PHP组件支持 ============================================================== -->
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<th colspan="4">
						<p>
							<a href="#top" class="arrow">5</a>
							<br />
							<a href="#bottom" class="arrow">6</a>
						</p>
						<span>8</span>PHP组件支持
						<a name="sec3" id="sec3"></a>
					</th>
				</tr>
				<tr>
					<td width="38%">拼写检查 ASpell Library</td>
					<td width="12%"><?= isfun("aspell_check_raw") ?></td>
					<td width="38%">高精度数学运算 BCMath</td>
					<td width="12%"><?= isfun("bcadd") ?></td>
				</tr>
				<tr>
					<td>历法运算 Calendar</td>
					<td><?= isfun("cal_days_in_month") ?></td>
					<td>DBA数据库</td>
					<td><?= isfun("dba_close") ?></td>
				</tr>
				<tr>
					<td>dBase数据库</td>
					<td><?= isfun("dbase_close") ?></td>
					<td>DBM数据库</td>
					<td><?= isfun("dbmclose") ?></td>
				</tr>
				<tr>
					<td>FDF表单资料格式</td>
					<td><?= isfun("fdf_get_ap") ?></td>
					<td>FilePro数据库</td>
					<td><?= isfun("filepro_fieldcount") ?></td>
				</tr>
				<tr>
					<td>Hyperwave数据库</td>
					<td><?= isfun("hw_close") ?></td>
					<td>图形处理 GD Library</td>
					<td><?= isfun("gd_info") ?></td>
				</tr>
				<tr>
					<td>IMAP电子邮件系统</td>
					<td><?= isfun("imap_close") ?></td>
					<td>Informix数据库</td>
					<td><?= isfun("ifx_close") ?></td>
				</tr>
				<tr>
					<td>LDAP目录协议</td>
					<td><?= isfun("ldap_close") ?></td>
					<td>MCrypt加密处理</td>
					<td><?= isfun("mcrypt_cbc") ?></td>
				</tr>
				<tr>
					<td>哈稀计算 MHash</td>
					<td><?= isfun("mhash_count") ?></td>
					<td>mSQL数据库</td>
					<td><?= isfun("msql_close") ?></td>
				</tr>
				<tr>
					<td>SQL Server数据库</td>
					<td><?= isfun("mssql_close") ?></td>
					<td>MySQL数据库</td>
					<td><?= isfun("mysqli_connect") ?></td>
				</tr>
				<tr>
					<td>SyBase数据库</td>
					<td><?= isfun("sybase_close") ?></td>
					<td>Yellow Page系统</td>
					<td><?= isfun("yp_match") ?></td>
				</tr>
				<tr>
					<td>Oracle数据库</td>
					<td><?= isfun("ora_close") ?></td>
					<td>Oracle 8 数据库</td>
					<td><?= isfun("OCILogOff") ?></td>
				</tr>
				<tr>
					<td>PREL相容语法 PCRE</td>
					<td><?= isfun("preg_match") ?></td>
					<td>PDF文档支持</td>
					<td><?= isfun("pdf_close") ?></td>
				</tr>
				<tr>
					<td>Postgre SQL数据库</td>
					<td><?= isfun("pg_close") ?></td>
					<td>SNMP网络管理协议</td>
					<td><?= isfun("snmpget") ?></td>
				</tr>
				<tr>
					<td>VMailMgr邮件处理</td>
					<td><?= isfun("vm_adduser") ?></td>
					<td>WDDX支持</td>
					<td><?= isfun("wddx_add_vars") ?></td>
				</tr>
				<tr>
					<td>压缩文件支持(Zlib)</td>
					<td><?= isfun("gzclose") ?></td>
					<td>XML解析</td>
					<td><?= isfun("xml_set_object") ?></td>
				</tr>
				<tr>
					<td>FTP</td>
					<td><?= isfun("ftp_login") ?></td>
					<td>ODBC数据库连接</td>
					<td><?= isfun("odbc_close") ?></td>
				</tr>
				<tr>
					<td>Session支持</td>
					<td><?= isfun("session_start") ?></td>
					<td>Socket支持</td>
					<td><?= isfun("socket_accept") ?></td>
				</tr>
				<tr>
					<td>浮点型数据显示的有效位数(precision)</td>
					<td><?= getcon("precision") ?></td>
					<td>socket超时时间(default_socket_timeout)</td>
					<td><?= getcon("default_socket_timeout") ?>秒</td>
				</tr>
				<tr>
					<td>"&lt;?...?&gt;"短标签(short_open_tag)</td>
					<td><?= getcon("short_open_tag") ?></td>
					<td>指定包含文件目录(include_path)</td>
					<td><?= getcon("include_path") ?></td>
				</tr>
				<tr>
					<td>忽略重复错误信息(ignore_repeated_errors)</td>
					<td><?= getcon("ignore_repeated_errors") ?></td>
					<td>忽略重复的错误源(ignore_repeated_source)</td>
					<td><?= getcon("ignore_repeated_source") ?></td>
				</tr>
				<tr>
					<td>报告内存泄漏(report_memleaks)</td>
					<td><?= getcon("report_memleaks") ?></td>
					<td>声明argv和argc变量(register_argc_argv)</td>
					<td><?= getcon("register_argc_argv") ?></td>
				</tr>
				<tr>
					<td>历法运算函数库：</td>
					<td><?= isfun("JDToGregorian") ?></td>
					<td>Iconv编码转换：</td>
					<td><?= isfun("iconv") ?></td>
				</tr>
				<tr>
					<td>mbstring：</td>
					<td><?= isfun("mb_eregi") ?></td>
					<td>SQLite 数据库：</td>
					<td><?php $sqliteOk = function_exists('sqlite_close');
						echo $sqliteOk ? YES : NO;
						if ($sqliteOk) {
							echo ",版本为: " . @sqlite_libversion();
						} ?></td>
				</tr>
			</table>
			<!-- ============================================================= 自定义检测 ============================================================== -->
			<?php
			$isMysql = (false !== function_exists("mysqli_connect")) ? "" : " disabled";
			$isMail = (false !== function_exists("mail")) ? "" : " disabled";
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr>
					<th colspan="4">
						<p>
							<a href="#top" class="arrow">5</a>
							<br />
							<a href="#bottom" class="arrow">6</a>
						</p>
						<span>8</span>自定义检测
						<a name="sec4" id="sec4"></a>
					</th>
				</tr>
				<tr>
					<th colspan="4" class="th3">MYSQL连接测试</th>
				</tr>
				<tr>
					<td>MYSQL服务器</td>
					<td><input type="text" name="mysqlHost" value="localhost" <?= $isMysql ?> /></td>
					<td> MYSQL用户名 </td>
					<td><input type="text" name="mysqlUser" <?= $isMysql ?> /></td>
				</tr>
				<tr>
					<td> MYSQL用户密码 </td>
					<td><input type="text" name="mysqlPassword" <?= $isMysql ?> /></td>
					<td> MYSQL数据库名称 </td>
					<td><input type="text" name="mysqlDb" />
						&nbsp;<input type="submit" class="myButton" value="CONNECT" <?= $isMysql ?> name="act" /></td>
				</tr>
				<?php if ("show" == $mysqlReShow) { ?>
					<tr>
						<td colspan="4"><?= $mysqlRe ?></td>
					</tr>
				<?php } ?>
				<tr>
					<th colspan="4" class="th3">MAIL邮件发送测试</th>
				</tr>
				<tr>
					<td>收信地址</td>
					<td colspan="3"><input type="text" name="mailReceiver" size="50" <?= $isMail ?> />
						&nbsp;<input type="submit" class="myButton" value="SENDMAIL" <?= $isMail ?> name="act" /></td>
				</tr>
				<?php if ("show" == $mailReShow) { ?>
					<tr>
						<td colspan="4"><?= $mailRe ?></td>
					</tr>
				<?php } ?>
				<tr>
					<th colspan="4" class="th3">函数支持状况</th>
				</tr>
				<tr>
					<td>函数名称</td>
					<td colspan="3"><input type="text" name="funName" size="50" />
						&nbsp;<input type="submit" class="myButton" value="FUNCTION_CHECK" name="act" /></td>
					<?php if ("show" == $funReShow) { ?>
				<tr>
					<td colspan="4"><?= $funRe ?></td>
				</tr>
			<?php } ?>
			</tr>

			<tr>
				<th colspan="4" class="th3">PHP配置参数状况</th>
			</tr>
			<tr>
				<td>参数名称</td>
				<td colspan="3"><input type="text" name="opName" size="40" />
					&nbsp;<input type="submit" class="myButton" value="CONFIGURATION_CHECK" name="act" /></td>
			</tr>
			<?php if ("show" == $opReShow) { ?>
				<tr>
					<td colspan="4"><?= $opRe ?></td>
				</tr>
			<?php } ?>
			</table>
			<!-- ============================================================= 页脚  ============================================================== -->
			<div id="footer">
				<p id="f1">
					<a name="bottom"></a>
					<a href="#top" class="arrow">55</a>
				</p>
			</div>
		</div>
	</form>
</body>

</html>
<?php
/*=============================================================    函数库  =============================================================*/
/*-------------------------------------------------------------------------------------------------------------
    检测函数支持
--------------------------------------------------------------------------------------------------------------*/
function isfun($funName)
{
	return (false !== function_exists($funName)) ? YES : NO;
}
/*-------------------------------------------------------------------------------------------------------------
    检测PHP设置参数
--------------------------------------------------------------------------------------------------------------*/
function getcon($varName)
{
	switch ($res = get_cfg_var($varName)) {
		case 0:
			return NO;
			break;
		case 1:
			return YES;
			break;
		default:
			return $res;
			break;
	}
}

/*-------------------------------------------------------------------------------------------------------------
    比例条
--------------------------------------------------------------------------------------------------------------*/
function bar($percent)
{
?>
	<ul class="bar">
		<li style="width:<?= $percent ?>%">&nbsp;</li>
	</ul>
<?php
}
/*-------------------------------------------------------------------------------------------------------------
    系统参数探测 LINUX
--------------------------------------------------------------------------------------------------------------*/
function sys_linux()
{
	// CPU
	if (false === ($str = @file("/proc/cpuinfo"))) return false;
	$str = implode("", $str);
	@preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
	@preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
	@preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
	if (false !== is_array($model[1])) {
		$res['cpu']['num'] = sizeof($model[1]);
		for ($i = 0; $i < $res['cpu']['num']; $i++) {
			//$res['cpu']['detail'][] = "类型：".$model[1][$i]." 缓存：".$cache[1][$i];
			$res['cpu']['model'][] = $model[1][$i];
			$res['cpu']['cache'][] = $cache[1][$i];
		}
		//if (false !== is_array($res['cpu']['detail'])) $res['cpu']['detail'] = implode("<br />", $res['cpu']['detail']);
		if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
		if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
	}

	// UPTIME
	if (false === ($str = @file("/proc/uptime"))) return false;
	$str = explode(" ", implode("", $str));
	$str = trim($str[0]);
	$min = $str / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days . "天";
	if ($hours !== 0) $res['uptime'] .= $hours . "小时";
	$res['uptime'] .= $min . "分钟";

	// MEMORY
	if (false === ($str = @file("/proc/meminfo"))) return false;
	$str = implode("", $str);
	preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);

	$res['memTotal'] = round($buf[1][0] / 1024, 2);
	$res['memFree'] = round($buf[2][0] / 1024, 2);
	$res['memCached'] = round($buf[3][0] / 1024, 2);
	$res['memUsed'] = ($res['memTotal'] - $res['memFree']);
	$res['memPercent'] = (floatval($res['memTotal']) != 0) ? round($res['memUsed'] / $res['memTotal'] * 100, 2) : 0;
	$res['memRealUsed'] = ($res['memTotal'] - $res['memFree'] - $res['memCached']);
	$res['memRealPercent'] = (floatval($res['memTotal']) != 0) ? round($res['memRealUsed'] / $res['memTotal'] * 100, 2) : 0;

	$res['swapTotal'] = round($buf[4][0] / 1024, 2);
	$res['swapFree'] = round($buf[5][0] / 1024, 2);
	$res['swapUsed'] = ($res['swapTotal'] - $res['swapFree']);
	$res['swapPercent'] = (floatval($res['swapTotal']) != 0) ? round($res['swapUsed'] / $res['swapTotal'] * 100, 2) : 0;

	// LOAD AVG
	if (false === ($str = @file("/proc/loadavg"))) return false;
	$str = explode(" ", implode("", $str));
	$str = array_chunk($str, 4);
	$res['loadAvg'] = implode(" ", $str[0]);

	return $res;
}
?>