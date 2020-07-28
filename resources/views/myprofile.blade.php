<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>  
</head>
<body>
<button onclick="document.location='/profile/{{$id}}'">Страница пользователя</button>
<div id="kkk">
@foreach ($comments as $comment)
	<div>{{ $comment->heading }}</div>
	<div>{{ $comment->text }}</div><br>
@endforeach
</div>
</body>
</html>