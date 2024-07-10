<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="my-4">Todo List</h3>
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary">Back</a>
                </div>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="taskList">
                        @foreach($data as $tabledata)
                        <tr>
                            <td>{{ $tabledata->id }}</td>
                            <td>{{ $tabledata->description }}</td>
                            <td>{{ $tabledata->completed ? 'Done' : 'Pending' }}</td>
                            <td>
                                @if (!$tabledata->completed)
                                <a href="#" data-id="{{ $tabledata->id }}" class="text-success complete-task-btn"><i class="fa fa-check-square-o"></i></a>
                                @endif
                                <a href="#" data-id="{{ $tabledata->id }}" class="text-danger delete-task-btn"><i class="fas fa-times-square"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            /***************************************************
                Handle Delete Task
            ***************************************************/

            $("#taskList").on("click", ".delete-task-btn", function(event) {
                event.preventDefault();

                var deleteUrl = "{{ route('tasks.destroy', '') }}/";
                var taskId = $(this).data("id");

                if (confirm("Are you sure you want to delete this task?")) {
                    $.ajax({
                        type: "DELETE",
                        url: deleteUrl + taskId,
                        dataType: "json",
                        success: function(response) {
                            if (response.status == true) {
                                $("#taskList")
                                    .find('tr[data-task-id="' + taskId + '"] td:eq(2)')
                                    .remove();
                                alert(response.success);
                                window.location.href = "{{ route('tasks.list') }}";
                            }
                        },
                    });
                }
            });

            /***************************************************
                Handle Mark Completed
            ***************************************************/
            $(document).ready(function() {
                /***************************************************
                Set CSRF token at Ajax header
            ***************************************************/
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $("#taskList").on("click", ".complete-task-btn", function(event) {
                    event.preventDefault();

                    var completeUrl = "{{ route('tasks.complete', '') }}/";
                    var taskId = $(this).data("id");

                    $.ajax({
                        type: "PUT",
                        url: completeUrl + taskId,
                        dataType: "json",
                        success: function(response) {
                            if (response.status == true) {
                                alert(response.message);
                                $("#taskList")
                                    .find('tr[data-task-id="' + taskId + '"] td:eq(2)')
                                    .text("Done");
                                window.location.href = "{{ route('tasks.list') }}";
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error completing task:", error);
                        },
                    });
                });
            });
        });
    </script>
</body>

</html>
