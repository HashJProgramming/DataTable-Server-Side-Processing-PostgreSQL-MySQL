<!doctype html>
<html lang="en">

<head>
    <title>PosgresSQL - DataTable</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CS -->
    <link
        rel="stylesheet"
        href="assets/bootstrap/css/bootstrap.min.css" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container">
            <table class="table table-hover table-bordered my-0" id="dataTable">
                <thead>

                </thead>
                <tbody>

                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            columns = [{
                    title: "FIRSTNAME",
                    data: 0
                },
                {
                    title: "LASTNAME",
                    data: 1
                },
                {
                    title: "MIDDLENAME",
                    data: 2
                },
                {
                    title: "SUFFIX",
                    data: 3
                },
                {
                    title: "PHONE",
                    data: 4
                },
            ];
            api = "script.php";
            initializeDataTable(api, columns);
        });
    </script>
</body>

</html>