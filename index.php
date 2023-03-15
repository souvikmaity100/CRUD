<?php
    // Connecting to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbs";

    $alert = false;
    $update = false;
    $delete = false;
    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn){
        die("Sorry we failed to connect :". mysqli_connect_error()."<br>");
    }

    if(isset($_GET['delete'])){
        $sno = $_GET['delete'];
        $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
        $result = mysqli_query($conn, $sql);
        $delete = true;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset( $_POST['snoEdit'])) {
            // update note
            $title = $_POST['titleEdit'];
            $desc = $_POST['descEdit'];
            $sno = $_POST['snoEdit'];

            $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$desc' WHERE `sno` = $sno";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $update = true;
            }
            else{
                echo "The record was not inserted succesfully because of this error ->" . mysqli_error($conn);
            }
        }
        else{
            $title = $_POST['title'];
            $desc = $_POST['desc'];

            // Insert data in the db
            $sql = "INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, '$title', '$desc', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $alert = true;
            }
            else{
                echo "The record was not inserted succesfully because of this error ->" . mysqli_error($conn);
            }
        }
    }   

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes - Notes taking made easy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>
    <!--Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Notes</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/curd/index.php" method="post">
                        <input type="hidden" id="snoEdit" name="snoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Note title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="mb-3">
                            <label for="descEdit" class="form-label">Notes description</label>
                            <textarea class="form-control" id="descEdit" rows="3" name="descEdit"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update note</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">iNotes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/curd/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    
    <!-- Alert Section -->
    <?php
        if ($alert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Your note was inserted succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        if ($update) {
            echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Your note was updated succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        if ($delete) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Success! </strong>Your note was deleted succesfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    ?>

    <!-- Add note section -->
    <div class="container my-3">
        <form action="/curd/index.php" method="post">
            <h2>Add a note</h2>
            <div class="mb-3">
                <label for="noteTitle" class="form-label">Note title</label>
                <input type="text" class="form-control" id="noteTitle" name="title">
            </div>
            <div class="mb-3">
                <label for="noteDesc" class="form-label">Notes description</label>
                <textarea class="form-control" id="noteDesc" rows="3" name="desc"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add note</button>
        </form>
    </div>

    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM `notes`";
                    $result = mysqli_query($conn, $sql);
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                            <th scope="row">'. $no .'</th>
                            <td>'. $row['title'] .'</td>
                            <td>'. $row['description'] .'</td>
                            <td><button class="edit btn btn-sm btn-primary" id='. $row['sno'] .'>Edit</button> <button class="delete btn btn-sm btn-primary" id="d'. $row['sno'] .'">Delete</button></td>
                        </tr>';
                        $no = $no +1;
                    }
                ?>
                
                
                
            </tbody>
        </table>
    </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );


        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click",(e)=>{
                // console.log("edit", e.target.parentNode.parentNode);
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                desc = tr.getElementsByTagName("td")[1].innerText;
                titleEdit.value = title;
                descEdit.value = desc;
                snoEdit.value = e.target.id;
                // console.log(e.target.id);
                $('#editModal').modal('toggle');
            })
        });

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click",(e)=>{
                sno = e.target.id.substr(1,);
                if(confirm("Delete this note!")){
                    console.log("yes");
                    window.location = `/curd/index.php?delete=${sno}`;
                }
                else{
                    console.log("no");
                }
            })
        });
    </script>
</html>