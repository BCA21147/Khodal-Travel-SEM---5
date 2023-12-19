<?php

include('../db_connection.php');

extract($_GET);
extract($_POST);

if (isset($_POST['delete_id'])) {
    $query = "SELECT * FROM `employee` WHERE `emp_type` LIKE '[$delete_id]|[%%]';";
    $result = mysqli_query($con, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (file_exists("../../../Images/" . $row['profile_image'])) {
                unlink("../../../Images/" . $row['profile_image']);
            }
            if (file_exists("../../../Images/" . $row['id_image'])) {
                unlink("../../../Images/" . $row['id_image']);
            }
        }
        $query = "DELETE coupon.* FROM coupon,trip,employee WHERE employee.emp_type LIKE '%[$delete_id]|[%%]%' AND LOCATE(CONCAT('[',employee.id,']'),employee) AND LOCATE(CONCAT('[',trip.id,']'),trip);";
        $query .= "DELETE trip.* FROM trip INNER JOIN employee WHERE employee.emp_type LIKE '%[$delete_id]|[%%]%' AND LOCATE(CONCAT('[',employee.id,']'),employee);";
        $query .= "DELETE FROM `employee_type` WHERE `id` = '$delete_id';";
        $query .= "DELETE FROM `employee` WHERE `emp_type` LIKE '%[$delete_id]|[%%]%';";
        mysqli_multi_query($con, $query);
    }
}

if (isset($_GET['id'])) {

    $query = "SELECT * FROM `employee_type` WHERE id = $id";
    $result_emp_type = mysqli_query($con, $query);
    $query = "SELECT * FROM `employee` WHERE `emp_type` LIKE '[$id]|[%%]'";
    $result_emp = mysqli_query($con, $query);
    $query = "SELECT trip.* FROM trip INNER JOIN employee WHERE employee.emp_type LIKE '%[$id]|[%%]%' AND LOCATE(CONCAT('[',employee.id,']'),employee) GROUP BY trip.id;";
    $result_trip = mysqli_query($con, $query);
    $query = "SELECT coupon.* FROM coupon,trip,employee WHERE employee.emp_type LIKE '%[$id]|[%%]%' AND LOCATE(CONCAT('[',employee.id,']'),employee) AND LOCATE(CONCAT('[',trip.id,']'),trip) GROUP BY coupon.id;";
    $result_coupon = mysqli_query($con, $query);

    if ($result_emp_type && $result_emp && $result_trip && $result_coupon) {
        $emp_type = mysqli_fetch_assoc($result_emp_type);
        ?>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>DELETE - EMPLOYEE TYPE</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>

                <h5>The Following Data Will Be Deleted.</h5>

                <div class='py-2'>
                    <div class='py-1 px-3 display-5' style='background-color: rgba(108, 117, 125,.3);font-weight: bold'>Employee
                        Type
                    </div>
                    <div class='table-responsive py-2'>
                        <table class='table table-hover table-bordered'>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Employee Type</th>
                                    <th>Employee Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $emp_type['id']; ?>.
                                    </td>
                                    <td>
                                        <?php echo $emp_type['emp_type']; ?>
                                    </td>
                                    <td>
                                        <?php echo $emp_type['emp_details']; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php
                if (mysqli_num_rows($result_emp) != 0) {
                    ?>
                    <div class='py-2'>
                        <div class='py-1 px-3 display-5' style='background-color: rgba(108, 117, 125,.3);font-weight: bold'>Employee
                        </div>
                        <div class='table-responsive py-2'>
                            <table class='table table-hover table-bordered'>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Profile Pic.</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Mobile No.</th>
                                        <th>Email</th>
                                        <th>Blood Group</th>
                                        <th>NID/Passport Type</th>
                                        <th>NID/Passport Number</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>ZIP Code</th>
                                        <th>Address</th>
                                        <th>NID/Passport Pic.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($emp = mysqli_fetch_assoc($result_emp)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $emp['id']; ?>.
                                            </td>
                                            <td>
                                                <div class='row d-flex overflow-auto' style='flex-wrap: nowrap;'>
                                                    <?php
                                                    if ($emp['profile_image'] != "" && file_exists("../../../Images/" . $emp['profile_image'])) {
                                                        ?>
                                                        <div class='col-12 d-flex align-items-center py-3'>
                                                            <img src='../Images/<?php echo $emp['profile_image']; ?>' alt='' class='w-100'
                                                                style='border-radius: 10px'>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class='d-flex justify-content-center w-100'>
                                                            <div class='row d-flex overflow-auto p-2 m-0'
                                                                style='flex-wrap: nowrap;border: 2px dashed gray'>
                                                                <div class='w-100'>
                                                                    <center>No Images Founded.</center>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $emp['first_name'] . ' ' . $emp['last_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo explode('|', $emp['emp_type'])[1]; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['mobile_no']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['email']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['blood_group']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['id_type']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['id_number']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['country']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['city']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['zip_code']; ?>
                                            </td>
                                            <td>
                                                <?php echo $emp['address']; ?>
                                            </td>
                                            <td>
                                                <div class='row d-flex overflow-auto' style='flex-wrap: nowrap;'>
                                                    <?php
                                                    if ($emp['id_image'] != "" && file_exists("../../../Images/" . $emp['id_image'])) {
                                                        ?>
                                                        <div class='col-12 d-flex align-items-center py-3'>
                                                            <img src='../Images/<?php echo $emp['id_image']; ?>' alt='' class='w-100'
                                                                style='border-radius: 10px'>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class='d-flex justify-content-center w-100'>
                                                            <div class='row d-flex overflow-auto p-2 m-0'
                                                                style='flex-wrap: nowrap;border: 2px dashed gray'>
                                                                <div class='w-100'>
                                                                    <center>No Images Founded.</center>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <?php
                if (mysqli_num_rows($result_trip) > 0) {
                    ?>
                    <div class='py-2'>
                        <div class='py-1 px-3 display-5' style='background-color: rgba(108, 117, 125,.3);font-weight: bold'>Trip
                        </div>
                        <div class='table-responsive py-2'>
                            <?php
                            while ($trip = mysqli_fetch_assoc($result_trip)) {
                                ?>
                                <table class='table table-bordered'>
                                    <tr>
                                        <th>No.</th>
                                        <th>Trip Section</th>
                                    </tr>
                                    <tr>
                                        <td rowspan="9">
                                            <?php echo $trip['id']; ?>.
                                        </td>
                                        <td>
                                            <table class="table m-0">
                                                <tr>
                                                    <th>Pick UP</th>
                                                    <th>Drop</th>
                                                    <th>Stoppage Point</th>
                                                    <th>Schedule Time</th>
                                                    <th>Start Date</th>
                                                    <th>Status</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo substr(explode("|", $trip['trip_pick_up'])[1], 1, -1); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo substr(explode("|", $trip['trip_drop'])[1], 1, -1); ?>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <?php
                                                            for ($data = 0; $data < sizeof(unserialize($trip['stoppage_point'])); $data++) {
                                                                ?>
                                                                <li>
                                                                    <?php echo substr(explode("|", unserialize($trip['stoppage_point'])[$data])[1], 1, -1); ?>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <?php echo substr(explode("|", $trip['schedule_time'])[1], 1, -1); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date("d-M-Y", strtotime($trip['start_date'])); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo ($trip['status'] == 1) ? "Active" : "Disable"; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Boarding && Dropping Point</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table m-0">
                                                <tr>
                                                    <th>Boarding Point</th>
                                                    <th>Dropping Point</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="table m-0 table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Time</th>
                                                                    <th>Bus Stand</th>
                                                                    <th>Details</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                for ($data = 0; $data < sizeof(unserialize($trip['boarding_point'])); $data++) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo unserialize($trip['boarding_point'])[$data]['boarding_time_12_hour']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo substr(explode("|", unserialize($trip['boarding_point'])[$data]['boarding_bus_stand'])[1], 1, -1); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo (unserialize($trip['boarding_point'])[$data]['boarding_details'] != "") ? unserialize($trip['boarding_point'])[$data]['boarding_details'] : "—"; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table class="table m-0 table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Time</th>
                                                                    <th>Bus Stand</th>
                                                                    <th>Details</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                for ($data = 0; $data < sizeof(unserialize($trip['dropping_point'])); $data++) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo unserialize($trip['dropping_point'])[$data]['dropping_time_12_hour']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo substr(explode("|", unserialize($trip['dropping_point'])[$data]['dropping_bus_stand'])[1], 1, -1); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo (unserialize($trip['dropping_point'])[$data]['dropping_details'] != "") ? unserialize($trip['dropping_point'])[$data]['dropping_details'] : "—"; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Seat, Fair, Time</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table m-0">
                                                <tr>
                                                    <th>Children Seat</th>
                                                    <th>Children Fair</th>
                                                    <th>Special Seat</th>
                                                    <th>Special Fair</th>
                                                    <th>Adult Seat</th>
                                                    <th>Distance</th>
                                                    <th>Approximate Time</th>
                                                    <th>Weekend</th>
                                                    <th>Facility</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo $trip['children_seat']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['children_fair']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['special_seat']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['special_fair']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['adult_fair']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['distance']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['approximate_time']; ?>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <?php
                                                            for ($data = 0; $data < sizeof(unserialize($trip['weekend'])); $data++) {
                                                                ?>
                                                                <li>
                                                                    <?php echo unserialize($trip['weekend'])[$data]; ?>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <?php
                                                            for ($data = 0; $data < sizeof(unserialize($trip['facility'])); $data++) {
                                                                ?>
                                                                <li>
                                                                    <?php echo substr(explode("|", unserialize($trip['facility'])[$data])[1], 1, -1); ?>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Vehical</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table m-0">
                                                <tr>
                                                    <th>Fleet Type</th>
                                                    <th>Vehical List</th>
                                                    <th>Company Name</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo substr(explode("|", $trip['fleet_type'])[1], 1, -1); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo substr(explode("|", $trip['vehical_list'])[1], 1, -1); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $trip['company_name']; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Employee</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table m-0">
                                                <tr>
                                                    <?php
                                                    foreach (unserialize($trip['employee']) as $key => $value) {
                                                        ?>
                                                        <th>
                                                            <?php echo strtoupper($key); ?>
                                                        </th>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    foreach (unserialize($trip['employee']) as $key => $value) {
                                                        ?>
                                                        <td>
                                                            <ul>
                                                                <?php
                                                                for ($data = 0; $data < sizeof($value); $data++) {
                                                                    if (substr(explode("|", $value[$data])[0], 1, -1) != "-") {
                                                                        ?>
                                                                        <li>
                                                                            <?php echo substr(explode("|", $value[$data])[1], 1, -1) . " " . substr(explode("|", $value[$data])[2], 1, -1); ?>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </ul>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <?php
                if (mysqli_num_rows($result_coupon) > 0) {
                    ?>
                    <div class='py-2'>
                        <div class='py-1 px-3 display-5' style='background-color: rgba(108, 117, 125,.3);font-weight: bold'>Coupon
                        </div>
                        <div class='table-responsive py-2'>
                            <table class='table table-hover table-bordered'>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Code</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Amount</th>
                                        <th>Trip Name</th>
                                        <th>Terms & Conditions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($coupon = mysqli_fetch_assoc($result_coupon)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $coupon['id']; ?>.
                                            </td>
                                            <td>
                                                <?php echo $coupon['code']; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d-m-Y', strtotime($coupon['start_date'])); ?>
                                            </td>
                                            <td>
                                                <?php echo date('d-m-Y', strtotime($coupon['end_date'])); ?>
                                            </td>
                                            <td>
                                                <?php echo $coupon['amount']; ?> ₹
                                            </td>
                                            <td>
                                                <?php echo substr(explode("|", $coupon['trip'])[1], 1, -1) . " - " . substr(explode("|", $coupon['trip'])[2], 1, -1); ?>
                                            </td>
                                            <td>
                                                <?php echo $coupon['terms_conditions']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-danger' data-dismiss='modal'
                    onclick='employee_type_delete_data(<?php echo $emp_type['id']; ?>)'>Confirm</button>
            </div>
        </div>
        <?php
    }
}

?>