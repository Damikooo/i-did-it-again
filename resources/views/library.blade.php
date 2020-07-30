<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>  
  <title></title>
</head>
<body>
@if ($myaccess == 1)
  @foreach($books as $book)
    {{ $book->title }}
    @if ($book->access == 0)
    <button class="share" id="{{ $book->id }}">Поделиться книгой</button>
    @else
    <button class="deny" id="{{ $book->id }}">Запретить доступ к книге</button>
    @endif
    <button class="remove" id="{{ $book->id }}">Удалить книгу</button><br>
    <a href="/book/{{ $book->id }}">Читать</a><br>
  @endforeach
@foreach ($errors->all() as $error)
  <li style="color: red;">{{ $error }}</li>
@endforeach
<form action="/bookwrite" method="POST">
  {{ csrf_field() }}
  Напишите книгу<br>
  <input type="text" name="title"><br>
  <textarea name="text" cols="100" rows="30"></textarea><br>
  <input type="submit" name="write"><br>
</form>
@else
  @foreach($books as $book)
    {{ $book->title }}<br>
    <a href="/book/{{ $book->id }}">Читать</a><br>
  @endforeach
@endif
@if($myaccess == 1)
  <script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $(".share").on("click", function(){
      let share = $(this).attr("id");
      let access = 1;
    $.ajax({
      url: 'http://laravel/library/{id}',
      method: 'post',
      data: {
        share, access
      }
    });
    location.reload();
  });
  $(".deny").on("click", function(){
      let share = $(this).attr("id");
      let access = 0;
    $.ajax({
      url: 'http://laravel/library/{id}',
      method: 'post',
      data: {
        share, access
      }
    });
    location.reload();
  });
  $(".remove").on("click", function(){
      let book_id = $(this).attr("id");
    $.ajax({
      url: 'http://laravel/bookremove',
      method: 'post',
      data: {
        book_id
      }
    });
    location.reload();
  });
  </script>
@endif
</body>
</html>