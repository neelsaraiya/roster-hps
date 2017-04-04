<?php

include 'functions.php';

$conn = dbconn();

$sql = "select * from timings  where date >=  CURDATE() order by date asc";
$result = $conn->query($sql);
?>
<html>
<script src="https://code.jquery.com/jquery-1.12.4.js" />
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" />
<link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
<body>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Date</th>
        <th>Roster</th>
    </tr>
    </thead>
    <tbody>
    <?php
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
            <tr>
                <td><?=$row['date'];?></td>
                <td><?=$row['roster'];?></td>
            </tr>
    <?php
    }
}
$conn->close();
?>
    </tbody>
</table>
</body>
</html>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>