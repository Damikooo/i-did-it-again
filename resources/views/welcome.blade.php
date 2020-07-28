<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Laravel Quickstart - Basic</title>

    <!-- CSS и JavaScript -->
  </head>

  <body>
    <div class="container">
      <nav class="navbar navbar-default">
        <!-- Содержимое Navbar -->
      </nav>
    </div>

    <!-- resources/views/tasks.blade.php -->


@section('content')
  <!-- Форма создания задачи... -->

  <!-- Текущие задачи -->
  @if (count($tasks) > 0)
    <div class="panel panel-default">
      <div class="panel-heading">
        Текущая задача
      </div>

      <div class="panel-body">
        <table class="table table-striped task-table">

          <!-- Заголовок таблицы -->
          <thead>
            <th>Task</th>
            <th>&nbsp;</th>
          </thead>

          <!-- Тело таблицы -->
          <tbody>
            @foreach ($tasks as $task)
              <tr>
                <!-- Имя задачи -->
                <td class="table-text">
                  <div>{{ $task->name }}</div>
                </td>

                <td>
                  <!-- TODO: Кнопка Удалить -->
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
   @endif
@endsection
  </body>
</html>