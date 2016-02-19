<?php
$mysqli = new mysqli("localhost", "root", "","shop");
if ($mysqli->connect_errno) {
    $mysqli = new mysqli("localhost", "root", "");
    $mysqli->real_query('CREATE DATABASE `shop`');
    $mysqli = new mysqli("localhost", "root", "", "shop");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
}
//----------------------Users
if (!$mysqli->query("CREATE TABLE IF NOT EXISTS Users(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `login` VARCHAR( 50 ) NOT NULL,
                                        `password` VARCHAR( 50 ) NOT NULL ,
                                        `dat_reg` DATE NOT NULL,
                                        `dat_vis` DATETIME NOT NULL,
                                        `ip` INT NOT NULL,
                                        `active_state` BOOLEAN NOT NULL DEFAULT '0'
                                        )
                                        ENGINE=InnoDB CHARACTER SET=UTF8")
    )
{
    echo "Failed to create table Users: (" . $mysqli->errno . ") " . $mysqli->error;
}

//----------------------Groups
if (!$mysqli->query("CREATE TABLE IF NOT EXISTS Groups(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `group_name` VARCHAR( 20 ) NOT NULL)
                                        ENGINE=InnoDB CHARACTER SET=UTF8")
) {
    echo "Failed to create table Groups: (" . $mysqli->errno . ") " . $mysqli->error;
}
//----------------------Partners
if (!$mysqli->query("CREATE TABLE IF NOT EXISTS Partners(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `partner_name` VARCHAR( 30 ) NOT NULL)
                                        ENGINE=InnoDB CHARACTER SET=UTF8")
) {
    echo "Failed to create table Partners: (" . $mysqli->errno . ") " . $mysqli->error;
}
//----------------------Items
if (!$mysqli->query("CREATE TABLE IF NOT EXISTS Items(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `item` VARCHAR( 50 ) NOT NULL,
                                        `cost` DOUBLE NOT NULL)
                                         ENGINE = InnoDB CHARACTER  SET = UTF8")
) {
    echo "Failed to create table Items: (" . $mysqli->errno . ") " . $mysqli->error;
}
//----------------------Orders
if ( !$mysqli->query("CREATE TABLE IF NOT EXISTS Orders(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `id_user` INT NOT NULL ,
                                        `id_partner` INT NOT NULL DEFAULT '0',
                                        `id_item` INT NOT NULL,
                                        `id_buy` INT NOT NULL,
                                        `count` INT NOT NULL,
                                        FOREIGN KEY(`id_user`) REFERENCES Users (`id`)
                                        ON UPDATE CASCADE
                                        ON DELETE RESTRICT,
                                        FOREIGN KEY(`id_partner`) REFERENCES Partners (`id`)
                                        ON UPDATE CASCADE
                                        ON DELETE RESTRICT,
                                        FOREIGN KEY (`id_item`) REFERENCES Items(`id`)
                                        ON UPDATE CASCADE
                                        ON DELETE RESTRICT
                                        )
                                        ENGINE = InnoDB CHARACTER SET = UTF8")
)
{
    echo "Failed to create table Orders: (" . $mysqli->errno . ") " . $mysqli->error;
}

//----------------------Users-Group

if ( !$mysqli->query("CREATE TABLE IF NOT EXISTS Users_Groups(`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                        `id_user` INT NOT NULL ,
                                        `id_group` INT NOT NULL ,
                                        FOREIGN KEY(`id_user`) REFERENCES Users (`id`)
                                        ON UPDATE CASCADE
                                        ON DELETE CASCADE,
                                        FOREIGN KEY (`id_group`) REFERENCES Groups (`id`)
                                        ON UPDATE CASCADE
                                        ON DELETE CASCADE)
                                        ENGINE = InniDB CHARACTER SET = UTF8")
)
{
    echo "Failed to create table Users_Groups: (" . $mysqli->errno . ") " . $mysqli->error;
}

//----INSERT---------------------

$query = "Select count(*) from `Groups`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0){
    $sql = "INSERT INTO Groups(group_name) VALUES ('temporary');";
    $sql.= "INSERT INTO Groups(group_name) VALUES ('regular');";
    $sql.= "INSERT INTO Groups(group_name) VALUES ('editors');";
    $sql.= "INSERT INTO Groups(group_name) VALUES ('admin');";
    if (!$mysqli->multi_query($sql)) {
        echo "Failed multi query to table Groups: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQLI_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}

$query = "Select count(*) from `Partners`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0) {
    $sql = "INSERT INTO Partners(partner_name) VALUES ('none');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_1');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_2');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_3');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_4');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_5');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_6');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_7');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_8');";
    $sql .= "INSERT INTO Partners(partner_name) VALUES ('partner_9');";
    if (!$mysqli->multi_query($sql)) {
        echo "Failed multi query to table Partners: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQLI_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}

$query = "Select count(*) from `Items`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0) {
    $sql = "INSERT INTO Items(item, cost) VALUES ('cat','11.5');";
    $sql .= "INSERT INTO Items(item, cost) VALUES ('dog','12.5');";
    if (!$mysqli->multi_query($sql)) {
        echo "Failed multi query to table Items: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQLI_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}

$query = "Select count(*) from `Users`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0) {
        $sql = "";
        $ip_f_p = 0;
        for ($i = 1; $i <= 20; $i++) {
            $login = "'" . uniqid() . "'";
            $query = 'SELECT login FROM Users WHERE login = "' . $login . '"';
            $result = $mysqli->query($query);
            $count = $result->num_rows;
            if ($count == 0) {
                $password = "'" . crypt(uniqid()) . "'";
                $start_interval = strtotime('1st January 2013');
                $end_interval = strtotime('1st January 2014');
                $dat_reg = rand($start_interval, $end_interval);
                $dat_vis = "'" . gmdate('Y-m-d H:i:s', rand($dat_reg, time())) . "'";
                $dat_reg = "'" . gmdate('Y-m-d H:i:s', $dat_reg) . "'";

                if ($i % 10000 == 0) {
                    $ip_f_p = rand(0, 255);
                }
                $ip = "'" . $ip_f_p . "." . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255) . "'";
                $active_state = rand(0, 1);
                $sql .= "INSERT INTO Users(`login`,`password`,`dat_reg`,`dat_vis`,`ip`,`active_state`
                              )
                                 VALUES (" . $login . "," . $password . "," . $dat_reg . "," . $dat_vis . "," . $ip . "," . $active_state . ");";
            } else {
                $i = $i - 1;
            }
        }
        if (!$mysqli->multi_query($sql)) {
            echo "Failed multi query to table Users: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        do {
            if ($res = $mysqli->store_result()) {
                var_dump($res->fetch_all(MYSQLI_ASSOC));
                $res->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
}

$query = "Select count(*) from `Users_Groups`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0){
    $sql="";
    for($i=1; $i<=20; $i++){
        $groups=rand(0,7);
        switch($groups){
            case 0:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','1');";
                break;
            case 1:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','2');";
                break;
            case 2:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','3');";
                break;
            case 3:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','4');";
                break;
            case 4:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','2');";
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','3');";
                break;
            case 5:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','2');";
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','1');";
                break;
            case 6:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','3');";
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','1');";
                break;
            case 7:
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','2');";
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','3');";
                $sql.="INSERT INTO Users_Groups(id_user, id_group) VALUES ('".$i."','1');";
                break;

        }
    }
    if (!$mysqli->multi_query($sql)) {
        echo "Failed multi query to table Groups: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQLI_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

}

$query = "Select count(*) from `Orders`";
$result = $mysqli->query($query);
$count = $result->fetch_row();

if($count[0]==0) {
    $sql="";
    $id_item=1;
    for($i=1; $i<=20; $i++){

            $col_purchases=rand(1,3);
            if($col_purchases==1){
                $sql .= "INSERT INTO Orders(id_user, id_partner, id_item, id_buy, count)
                                VALUES ('".rand(1,20)."','".rand(1,10)."','1','".$id_item."','".rand(1,30)."');";
                $id_item++;
            }
            if($col_purchases==2){
                $sql .= "INSERT INTO Orders(id_user, id_partner, id_item, id_buy, count)
                                VALUES ('".rand(1,20)."','".rand(1,10)."','2','".$id_item."','".rand(1,30)."');";
                $id_item++;
            }
            else{
                $id_user=rand(1,20);
                $id_partner=rand(1,10);
                $sql .= "INSERT INTO Orders(id_user, id_partner, id_item, id_buy, count)
                                VALUES ('".$id_user."','".$id_partner."','1','".$id_item."','".rand(1,30)."');";
                $sql .= "INSERT INTO Orders(id_user, id_partner, id_item, id_buy, count)
                                VALUES ('".$id_user."','".$id_partner."','2','".$id_item."','".rand(1,30)."');";
                $id_item++;
            }
    }
    if (!$mysqli->multi_query($sql)) {
        echo "Failed multi query to table Orders: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQLI_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}
//--------------Select
$res = $mysqli->query("SELECT login , SUM(count) as sum
                       FROM (users INNER JOIN orders ON users.id=orders.id_user)  INNER JOIN partners ON partners.id=orders.id_partner
                       WHERE active_state=1 AND partner_name='none'
                        GROUP BY login
                       ");
    //for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    for ($row_no = 0; $row_no < $res->num_rows; $row_no ++) {
        $res->data_seek($row_no);
        $row = $res->fetch_assoc();
        echo " login = " . $row['login'] ."  sum=".  $row['sum']."<br>";
    }


$res = $mysqli->query("SELECT login , SUM(count * cost) as all_cost
                       FROM ((users INNER JOIN orders ON users.id=orders.id_user)
                                    INNER JOIN partners ON partners.id=orders.id_partner)
                                    INNER JOIN items ON items.id = orders.id_item
                       WHERE active_state=1 AND partner_name='none'
                       GROUP BY login
                       ");
for ($row_no = 0; $row_no < $res->num_rows; $row_no ++) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " login = " . $row['login'] ."  all_cost=".  $row['all_cost']."<br>";
}

$res = $mysqli->query("SELECT partner_name, count(DISTINCT id_user) as col_users
                       FROM partners INNER JOIN orders ON partners.id=orders.id_partner
                       GROUP BY partner_name
                       ");
for ($row_no = 0; $row_no < $res->num_rows; $row_no ++) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " partner_name = " . $row['partner_name'] ."  col_users=".  $row['col_users']."<br>";
}

$res = $mysqli->query("SELECT partner_name , SUM(count * cost) as all_cost
                       FROM ((users INNER JOIN orders ON users.id=orders.id_user)
                                    INNER JOIN partners ON partners.id=orders.id_partner)
                                    INNER JOIN items ON items.id = orders.id_item
                       WHERE partner_name !='none'
                       GROUP BY partner_name
                       ");
for ($row_no = 0; $row_no < $res->num_rows; $row_no ++) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " partner_name = " . $row['partner_name'] ."  all_cost=".  $row['all_cost']."<br>";
}
?>