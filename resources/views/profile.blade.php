<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>  
</head>
<body>
@foreach ($users as $user)
	{{ $user->email }}
@endforeach
<button onclick="document.location='/myprofile/{{$user->id}}'">Все комментарии пользователя</button>
<div id="form" style="display:none;">
	<form method="POST">
		<input readonly type="text" name="parent_id" id="parent_id"><br>
		<input type="text" name="heading"><br>
		<input type="text" name="text"><br>
		<input type="submit" name="reply">
	</form>
</div>
<div>
	@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li style="color: red;">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
	<form method="POST">
		Напишите пост
		<input type="text" name="heading"><br>
		<input type="text" name="text"><br>
		<input type="submit" name="reply">
	</form>
</div>
<div id="kkk">
@foreach ($comments as $comment)
	<div>{{ $comment->heading }}
			<button class="reply" name="reply" id="{{$comment->id}}">Ответить</button>
		@if ($user->id == $comment->author or $user->id == $comment->profile)
			<button class="delete" name="delete" id="{{$comment->id}}">Удалить</button>
		@endif
	</div>
	<div>{{ $comment->text }}</div><br>
	<?$i++?>
	@break ($i == 5)
	@foreach($comments_kids as $kid)
		@if ($kid->parent_id == $comment->id)
			<div style="margin-left: 20px;">
				<div>{{ $kid->heading }}
					@if ($user->id == $kid->author or $user->id == $kid->profile)
						<button class="delete" name="delete" id="{{$kid->id}}">Удалить</button>
					@endif
				</div>
				<div>{{ $kid->text }}</div><br>
			</div>
			<?$i++?>
		@endif
		@break ($i == 5)
	@endforeach
@endforeach
</div>
<button class="all" data-id="{{ $user->id}}">Все комментарии</button>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(".delete").on("click", function(){
    let post_id = $(this).attr("id");
	$.ajax({
	  url: 'http://laravel/delete',
	  method: 'delete',
	  data: {
	  	post_id
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

function remove(i){
    let post_id = $(i).attr("id");
	$.ajax({
	  url: 'http://laravel/delete',
	  method: 'delete',
	  data: {
	  	post_id
	  },
	  success: function(response, statusText, status) {
	    console.log('Запрос успешно отправился, получаем ответ', response);
	  },
	  error: function(XHR) {
	    console.log('Ошибка запроса', XHR);
	  }
	});
	location.reload();
};

$(".reply").on("click", function(){
	let reply_id = $(this).attr("id");
	document.getElementById('parent_id').value=reply_id;
	let form = document.getElementById ('form');
	form.style.display="block";
	}
);
function reply(i){
	let reply_id = $(i).attr("id");
	document.getElementById('parent_id').value=reply_id;
	let form = document.getElementById ('form');
	form.style.display="block";
	}

let showed = 0;
$(".all").on("click", function(){
    let com_id = $(this).attr("data-id");
	$.ajax({
	  url: 'http://laravel/com/'+com_id,
	  method: 'post',
	  success: function(response, statusText, status) {
		if (showed !== 1) {
		    $( "#kkk" ).append(response.html);
		    showed = 1;
		    console.log(showed);
		}
	  }
	});
});
// $("#showAll").on("click", function(){
// 	var container = document.getElementById ('container');
// 	if (container.style.display !== 'none'){
//   	container.style.display="none";
// 	}else{
// 		container.style.display="block";
// 	}
// });
</script>
</body>
</html>