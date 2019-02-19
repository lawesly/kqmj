<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="refresh" content="60">
	<title>inoffice</title>
	<link rel="stylesheet" href="/application/views/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<script src="/application/views/bootstrap-3.3.5-dist/jquery.min.js"></script>
	<script src="/application/views/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<style>
.col-xs-1dot5 {
position: relative;
min-height: 1px;
padding-right: 5px;
padding-left: 5px;
}
</style>

</head>
<body>
<div class="container-fluid">
<p></p>
		<?php foreach($userinfoGroup as $userinfo): ?>
        	<div class='row'>
			<?php foreach($userinfo as $arr): ?>
			<div class="col-xs-2 col-md-2">
				<div style="position:relative">
					<?php if($arr['count'] == 1): ?>
					<img src="/application/views/images/3.png">
					<?php else: ?>
					<img src="/application/views/images/4.png">
					<?php endif; ?>
					<div style="position:absolute; z-index:2; left:5px; top:3px">
						<font color='white'><?php echo $arr['Name']; ?></font>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
<p></p>
<p>灰色代表未出勤,绿色代表出勤,请勿重复打卡,一分钟更新一次,此数据仅供参考</p>
</div>
</body>
</html>

