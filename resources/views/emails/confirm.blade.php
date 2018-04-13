<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>注册确认链接</title>
</head>
<body>
	<h1>感谢你在 sample 网站进行注册！</h1>
	<p>
		请点击以下的链接完成注册：
		<a href="{{ route('confirm_email',$user->activation_token) }}">
			{{ route('confirm_email',$user->activation_token) }}
		</a>
	</p>
	<p>
		如果这不是你本人操作，请忽略本邮件
	</p>
</body>
</html>