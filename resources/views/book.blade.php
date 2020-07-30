<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>  
	<title></title>
</head>
<body>
@if ($book->author !== Auth::id())
{{ $book->title }}<br>
{{ $book->text }}<br>
<a href="/library/{{ $book->author }}">Вернуться в библиотеку</a>
@else
<input type="text" id="title" value="{{ $book->title }}"><br>
<textarea id="text" cols="100" rows="30">{{ $book->text }}</textarea><br>
<button class="edit" id="{{ $book->id }}">Отправить</button>
<a href="/library/{{ $id }}">Вернуться в библиотеку</a>
<script type="text/javascript">
	$.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
    $(".edit").on("click", function(){
    let book_id = $(this).attr("id");
	let title = document.getElementById('title').value;
	let text = document.getElementById('text').value;
	$.ajax({
	  url: 'http://laravel/bookedit',
	  method: 'post',
	  data: {
	  	book_id, title, text
	  },
	  success: function(response, statusText, status) {
	    console.log('Запрос успешно отправился, получаем ответ', response);
	  },
	  error: function(XHR) {
	    console.log('Ошибка запроса', XHR);
	  }
	});
	location.reload();
  });
</script>
@endif
</body>
</html>