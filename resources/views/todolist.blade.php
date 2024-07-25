<!DOCTYPE html>
<html>
    <head>
        <title>To-Do List</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">To-Do List</h1>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="task" class="form-control" placeholder="Task">
                    <textarea id="description" class="form-control mt-2" placeholder="Description"></textarea>
                    <input type="text" id="due_date" class="form-control mt-2" placeholder="Due Date">
                    <button id="add-task" class="btn btn-success mt-2">Add Task</button>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-between mb-2">
                        <input type="text" id="start_date" class="form-control w-25" placeholder="Start Date">
                        <input type="text" id="end_date" class="form-control w-25" placeholder="End Date">
                        <input type="text" id="search" class="form-control w-25" placeholder="Search by Task Name or Description">
                    </div>
<!--                    <div class="d-flex justify-content-between">
                        <select id="sort_by" class="form-control w-25">
                            <option value="due_date">Due Date</option>
                            <option value="created_at">Created At</option>
                        </select>
                    </div>-->
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Completed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="task-list">
                            <!-- Tasks will be populated here by AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script>
        $(document).ready(function () {
            //
            $('#due_date,#start_date, #end_date').datepicker({
                format: 'yyyy-mm-dd'
            });

            function fetchTasks() {
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();
                let sortBy = $('#sort_by').val();
                let search = $('#search').val();
                //console.log('Search is ',search);
                $.ajax({
                    url: '/tasks',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        sort_by: sortBy,
                        search: search
                    },
                    success: function (tasks) {
                       // console.log('Tasks-->', tasks);
                        let rows = '';
                        tasks.forEach(task => {
                            rows += `
                                <tr>
                                    <td>${task.task}</td>
                                    <td>${task.description}</td>
                                    <td>${task.due_date}</td>
                                    <td>${task.marked_as_completed ? 'Yes' : 'No'}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="markAsCompleted(${task.id})">Mark as Completed</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#task-list').html(rows);
                    }
                });
            }

            $('#add-task').click(function () {
                let task = $('#task').val();
                let description = $('#description').val();
                let dueDate = $('#due_date').val();
               // console.log('task value is -->', task, description, dueDate);

                $.ajax({
                    url: '/tasks',
                    type: 'POST',
                    data: {
                        task: task,
                        description: description,
                        due_date: dueDate,
                        marked_as_completed: 0
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        fetchTasks();
                        $('#task').val('');
                        $('#description').val('');
                        $('#due_date').val('');
                    }
                });
            });

            window.markAsCompleted = function (id) {
                $.ajax({
                    url: `/tasks/${id}`,
                    type: 'PUT',
                    data: {
                        marked_as_completed: 1
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        fetchTasks();
                    }
                });
            };

//            window.deleteTask = function (id) {
//                $.ajax({
//                    url: `/tasks/${id}`,
//                    type: 'DELETE',
//                    headers: {
//                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                    },
//                    success: function () {
//                        fetchTasks();
//                    }
//                });
//            };

            $('#start_date, #end_date, #sort_by ,#search').change(function () {
                fetchTasks();
            });

            fetchTasks();
        });
        </script>
    </body>
