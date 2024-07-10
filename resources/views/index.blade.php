<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>To Do List</title>
    <style>
        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group .form-control {
            flex-grow: 1;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 m-auto">
                <br/>
                <h3>PHP - Simple To Do List App</h3>
                <br/>
                <form action="{{ route('tasks.store') }}" method="POST" class="input-group" id="addTaskForm">
                    @csrf
                    <input type="text" class="form-control" name="task" id="taskInput" placeholder="Add Task">
                    <input type="submit" class="btn btn-primary" name="submit" value="Add Task">
                </form>
                <br/>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 m-auto">
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

        <div class="row">
            <div class="col-md-2 m-auto">
            <a href="{{ route('tasks.list') }}" class="btn btn-primary form-control">Show All</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            /***************************************************
                Set CSRF token at Ajax header
            ***************************************************/
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            /***************************************************
                Handle Task Submission
            ***************************************************/
            $("#addTaskForm").submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                var url = $(this).attr("action");

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            var newTask = response.data;

                            $("#taskList").append(`
                            <tr>
                                <td>${newTask.id}</td>
                                <td>${newTask.description}</td>
                                <td>${newTask.completed ? "Done" : "Pending"}</td>
                                <td>
                                    <a href="/edit/${
                                        newTask.id
                                    }" class="text-success"><i class="fa fa-check-square-o"></i></a>
                                    <a href="/delete/${
                                        newTask.id
                                    }" class="text-danger"><i class="fas fa-times-square"></i></a>
                                </td>
                            </tr>
                        `);
                            $("#taskInput").val("");
                            alert(response.success);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error completing task:", error);
                        alert("Task already Exists");
                    },
                });
            });

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
                                window.location.href = "{{ route('tasks.index') }}";
                            }
                        },
                    });
                }
            });

            /***************************************************
                Handle Mark Completed
            ***************************************************/
            $(document).ready(function() {
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
                                window.location.href = "{{ route('tasks.index') }}";
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
