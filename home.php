<?php

session_start();
require 'db_conn.php';


if ( isset( $_SESSION[ 'id' ] ) && isset( $_SESSION[ 'username' ] ) && isset( $_SESSION[ 'name' ] ) ) {
  ?>

<!DOCTYPE html>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<link href="button.css" rel="stylesheet" type="text/css">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<title>HOME</title>
</head>
<?php

if ( isset( $_POST[ 'insertdata' ] ) ) {

  $attraction = $_POST[ 'attraction' ];
  $name = $_POST[ 'name' ];
  $person = $_POST[ 'person' ];
  $username = $_POST[ 'username' ];
  $status = $_POST[ 'status' ];


  $sql_validate = "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending'";
  $sql_run = mysqli_query( $con, $sql_validate );

  if ( mysqli_num_rows( $sql_run ) > 0 ) {

  } else {
    $sql = mysqli_query( $con, "INSERT into crux(name, attraction, person, username, status) VALUES ('$name', '$attraction', '$person', '$username', '$status')" );
  }

  if ( $sql == true ) {
    echo '<script type = "text/javascript">';
    echo 'alert("Add Successfully");';
    echo 'window.location.href = "home.php"';
    echo '</script>';
  } else {

  }

}
?>

<body>
<div id="outer">
<div id="header">
  <div id="header-content">
    <button class="logout-btn" onclick="location.href='index.php';"><span class="material-symbols-rounded">
logout
</span></button>
    <h1>予約サイト</h1>
    <div class="userid">
      <h3> <?php echo $_SESSION['username']; ?> 様、ようこそ</h3>
      <!--<h3><?php echo $_SESSION['id']; ?></h3>-->
    </div>
    <div class="confirm">
      <p>あなたが予約しているのは</p>
      <center>
        <table class="wow">
          <?php
                include_once('db_conn.php');

                // ATTRACTION RESERVED
                $res_attraction = "";
                $sql = "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending'";
                $sql_run = mysqli_query($con, $sql);

                if (mysqli_num_rows($sql_run) > 0) {
                  foreach ($sql_run as $row) {
                    ?>
          <tr>
          <tbody>
          <td><?php echo $row["attraction"]; ?> 
              <!-- <?php echo $row["name"]; ?> --></td>
            <?php
                  }
                }

                $res_attraction = $row["attraction"];
                $_SESSION["res_attraction"] = $res_attraction;

                ?>
          </tr>
          <!-- ranking -->
          <?php
                $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending' AND attraction = 'AR'");
                if (mysqli_num_rows($sql_get1) > 0) {
                  while ($result = mysqli_fetch_assoc($sql_get1)) {
                    // echo '<h1>予約中</h1> ';
                    // echo $sql_count;
                  }
                  ?>
          <?php
                } else {
                }
                ?>
          </tbody>
          
        </table>
      </center>
      <p>です。</p>
      <p>WAITING IN LINE</p>
      <?php


            // POSITION
          
            $waitingnumber = "";

            $sql_position = "SELECT (@pos := @pos + 1) AS position, attraction, id, name FROM crux JOIN (SELECT @pos := 0) r WHERE attraction = '$res_attraction' AND status = 'pending' AND id <= '$_SESSION[id]'";
            $sql_run_position = mysqli_query($con, $sql_position);

            if (mysqli_num_rows($sql_run_position) > 0) {
              foreach ($sql_run_position as $row) {

                ?>
      <center>
        <table class="wow">
          <tr>
          <tbody>
          <td><!-- <?php echo $row["id"]; ?> --> 
              <!-- <?php echo $row["position"]; ?> --> 
              <!-- <?php echo $row["attraction"]; ?> --> 
              <!-- <?php echo $row["name"]; ?> --></td>
            </tbody>
          </tr>
        </table>
      </center>
      <?php
              }
              $waitingnumber = $row["position"];

              echo $waitingnumber;


            } else {
            }
            ?>
      <p>WAITING TIME</p>
      <?php

            // WAITING TIME
            $waitingtime = "";

            $sql_time = "SELECT count(id), id FROM crux  WHERE attraction = '$res_attraction' AND status = 'pending' AND id <= '$_SESSION[id]'";
            $sql_run_time = mysqli_query($con, $sql_time);

            if (mysqli_num_rows($sql_run_time) > 0) {
              foreach ($sql_run_time as $row) {

                ?>
      <center>
        <table class="wow">
          <tr>
          <tbody>
          <td><?php echo $row["id"]; ?> 
              <!-- <?php echo $row["position"]; ?> --> 
              <!-- <?php echo $row["attraction"]; ?> --> 
              <!-- <?php echo $row["name"]; ?> --></td>
            </tbody>
          </tr>
        </table>
      </center>
      <?php
              }
              $waitingnumber = $row["id"];

              // echo $waitingnumber;
          

            } else {
            }
            ?>
      <div id="response"></div>
      <script type="text/javascript">
              setInterval(function () {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", "response.php", false);
                xmlhttp.send(null);
                document.getElementById("response").innerHTML = xmlhttp.responseText;
              }, 1000);
            </script>
      </tr>
      <?php
            $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = ''");
            if (mysqli_num_rows($sql_get1) > 0) {
              while ($result = mysqli_fetch_assoc($sql_get1)) {
                echo '<h1>予約中</h1> ';
              }
              ?>
      <?php
            } else {
              ?>
    </div>
  </div>
</div>
<div id="attraction"> 
  <!--ARコンテンツ-->
  <button class="btn btn_ar attraction_btn md-btn learn-more" data-target="modal01">AR
  </button>
  
  <!--ARモーダル-->
  <div id="modal01" class="modal">
    <div class="md-overlay md-close"></div>
    <div class="md-contents">
      <div class="md-inner ar-inner">
        <div class="att-header">
          <div class="user"> <img alt="User icon" src="image/user.svg" /> <?php echo $_SESSION['username']; ?> </div>
          <h1 class="att-name">AR</h1>
          <a href="home.php" class="xmark"><img src="image/xmark.svg" alt="Xmark"></a> </div>
        <div class="att-main"> <img src="image/ar.jpg" width="96%" height="auto" alt="" />
          <p class="att-desc">ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。</p>
        </div>
        <!--AR予約フォーム-->
        <div class="yoyaku-form">
          <p class="att-col">プレイ時間　： 3分</p>
          
          <table>
            <tbody>
              <?php
                      $result = $con->query("SELECT * FROM crux where status = 'pending' AND attraction = 'AR'");
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          ?>
              <?php echo $row['id']; ?> <?php echo $row['person']; ?>
              <?php
                 }
                 } else {
                        ?>
              <tr>
                <td colspan="7" class="noyoyaku">誰も予約していません。</td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
          <?php
                  $result = mysqli_query($con, "SELECT count(id) FROM crux where status = 'pending' AND attraction = 'AR'") or die();
                  while ($rows = mysqli_fetch_array($result)) {
                    ?>
          <p class="att-col">現在予約中の人　： <?php echo $rows['count(id)']; ?> </p>
          <p class="att-col">現在の待ち時間　：
            <?php
                      // Waiting time per game
                      $x = 3;
                      // Waiting person
                      $x *= $rows['count(id)'];

                      echo $x;
                      ?>
            <!-- Person -->
            <?php } ?>
            分 </p>
          <form id="form-ar" class="form" action="home.php" method="post">
            
            <!--action属性に送信先のURLを追加--> 
            <!--       <label for="num">ID：</label>
                                                                <input id="num-ar" type="number" name="yoyaku-num-ar">-->
            
            <?php
                    $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending' AND (attraction = 'AR' OR attraction = 'VR' OR attraction = 'VTuber' OR attraction = 'Cardgame')");
                    if (mysqli_num_rows($sql_get1) > 0) {
                      while ($result = mysqli_fetch_assoc($sql_get1)) {
                        echo '<h1 class="now-yoyaku">予約中</h1> ';
                        echo '<button class="cancel-btn">キャンセル</button>';
                      }

                    } else {
                      ?>
            <select name="person" id="person" class="person" required>
              <option value="">(必須)プレイ人数</option>
              <option value="1">1人</option>
              <option value="2">2人</option>
              <option value="3">3人</option>
              <option value="4">4人</option>
              <option value="5">5人</option>
              <input type="hidden" id="attraction" name="attraction" value="AR">
              <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
              <input type="hidden" id="status" name="status" value="pending">
              <input type="hidden" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
            </select>
            <input type="submit" name="insertdata" value="予約する" class="yoyaku-btn"
                        onClick="javascript:return confirm('Are you sure to submit this');">
            <?php

                    }
                    ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!--VRコンテンツ-->
  <button class="btn btn_vr attraction_btn md-btn" data-target="modal02">VR </button>
  
  <!--VRモーダル-->
  <div id="modal02" class="modal">
    <div class="md-overlay md-close"></div>
    <div class="md-contents">
      <div class="md-inner vr-inner">
        <div class="att-header">
          <div class="user"> <img alt="User icon" src="image/user.svg" /> <?php echo $_SESSION['username']; ?> </div>
          <h1 class="att-name">VR</h1>
          <a href="home.php" class="xmark"><img src="image/xmark.svg" alt="Xmark"></a> </div>
        <div class="att-main"> <img src="image/ar.jpg" width="96%" height="auto" alt="" />
          <p class="att-desc">ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。</p>
        </div>
        <!--AR予約フォーム-->
        <div class="yoyaku-form">
          <p class="att-col">プレイ時間　： 3分</p>
          
          <!--待ち時間:&nbsp;&nbsp;&nbsp;分 --> 
          
          <!-- Number of Person AR-->
          <table>
            <thead>
              <!-- <tr>
                                                        <th>Log Id</th>
                                                        <th>Person</th>
                                                    </tr> -->
              
            </thead>
            <tbody>
              <?php
                      $result = $con->query("SELECT * FROM crux where status = 'pending' AND attraction = 'VR'");
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          ?>
              <!-- <tr>
                                                                                                        <td>
                                                                                                            <?php echo $row['id']; ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php echo $row['person']; ?>
                                                                                                        </td>
                                                                                                    </tr> -->
              <?php
                        }
                      } else {
                        ?>
              <tr>
                <td colspan="7" class="noyoyaku">誰も予約していません。</td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
          <?php
                  $result = mysqli_query($con, "SELECT count(id) FROM crux where status = 'pending' AND attraction = 'VR'") or die();
                  while ($rows = mysqli_fetch_array($result)) {
                    ?>
          <p class="att-col">現在予約中の人　： <?php echo $rows['count(id)']; ?> </p>
          <p class="att-col">現在の待ち時間　：
            <?php
                      // Waiting time per game
                      $x = 3;
                      // Waiting person
                      $x *= $rows['count(id)'];

                      echo $x;
                      ?>
            <!-- Person -->
            <?php } ?>
            分 </p>
          <form id="form-vr" class="form" action="home.php" method="post">
            
            <!--action属性に送信先のURLを追加--> 
            <!--       <label for="num">ID：</label>
                                                                <input id="num-ar" type="number" name="yoyaku-num-ar">-->
            
            <?php
                    $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending' AND (attraction = 'VR' OR attraction = 'AR' OR attraction = 'VTuber' OR attraction = 'Cardgame')");
                    if (mysqli_num_rows($sql_get1) > 0) {
                      while ($result = mysqli_fetch_assoc($sql_get1)) {
                        echo '<h1 class="now-yoyaku">予約中</h1> ';
                        echo '<button class="cancel-btn">キャンセル</button>';
                      }

                    } else {
                      ?>
            <select name="person" id="person" class="person" required>
              <option value="">(必須)プレイ人数</option>
              <option value="1">1人</option>
              <option value="2">2人</option>
              <option value="3">3人</option>
              <option value="4">4人</option>
              <option value="5">5人</option>
              <input type="hidden" id="attraction" name="attraction" value="VR">
              <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
              <input type="hidden" id="status" name="status" value="pending">
              <input type="hidden" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
            </select>
            <input type="submit" name="insertdata" value="予約する" class="yoyaku-btn"
                        onClick="javascript:return confirm('Are you sure to submit this');">
            <?php

                    }
                    ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!--Vtuberコンテンツ-->
  <button class="btn btn_vtuber attraction_btn md-btn" data-target="modal03">Vtuber </button>
  
  <!--Vtuberモーダル-->
  <div id="modal03" class="modal">
    <div class="md-overlay md-close"></div>
    <div class="md-contents">
      <div class="md-inner vtuber-inner">
        <div class="att-header">
          <div class="user"> <img alt="User icon" src="image/user.svg" /> <?php echo $_SESSION['username']; ?> </div>
          <h1 class="att-name">Vtuber</h1>
          <a href="home.php" class="xmark"><img src="image/xmark.svg" alt="Xmark"></a> </div>
        <div class="att-main"> <img src="image/ar.jpg" width="96%" height="auto" alt="" />
          <p class="att-desc">ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。</p>
        </div>
        <!--AR予約フォーム-->
        <div class="yoyaku-form">
          <p class="att-col">プレイ時間　： 3分</p>
          
          <!--待ち時間:&nbsp;&nbsp;&nbsp;分 --> 
          
          <!-- Number of Person AR-->
          <table>
            <thead>
              <!-- <tr>
                                                        <th>Log Id</th>
                                                        <th>Person</th>
                                                    </tr> -->
              
            </thead>
            <tbody>
              <?php
                      $result = $con->query("SELECT * FROM crux where status = 'pending' AND attraction = 'Vtuber'");
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          ?>
              <!-- <tr>
                                                                                                        <td>
                                                                                                            <?php echo $row['id']; ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php echo $row['person']; ?>
                                                                                                        </td>
                                                                                                    </tr> -->
              <?php
                        }
                      } else {
                        ?>
              <tr>
                <td colspan="7" class="noyoyaku">誰も予約していません。</td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
          <?php
                  $result = mysqli_query($con, "SELECT count(id) FROM crux where status = 'pending' AND attraction = 'VTUBER'") or die();
                  while ($rows = mysqli_fetch_array($result)) {
                    ?>
          <p class="att-col">現在予約中の人　： <?php echo $rows['count(id)']; ?> </p>
          <p class="att-col">現在の待ち時間　：
            <?php
                      // Waiting time per game
                      $x = 3;
                      // Waiting person
                      $x *= $rows['count(id)'];

                      echo $x;
                      ?>
            <!-- Person -->
            <?php } ?>
            分 </p>
          <form id="form-vtuber" class="form" action="home.php" method="post">
            
            <!--action属性に送信先のURLを追加--> 
            <!--       <label for="num">ID：</label>
                                                                <input id="num-ar" type="number" name="yoyaku-num-ar">-->
            
            <?php
                    $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending' AND (attraction = 'VTUBER' OR attraction = 'VR' OR attraction = 'AR' OR attraction = 'Cardgame')");
                    if (mysqli_num_rows($sql_get1) > 0) {
                      while ($result = mysqli_fetch_assoc($sql_get1)) {
                        echo '<h1 class="now-yoyaku">予約中</h1> ';
                        echo '<button class="cancel-btn">キャンセル</button>';
                      }

                    } else {
                      ?>
            <select name="person" id="person" class="person" required>
              <option value="">(必須)プレイ人数</option>
              <option value="1">1人</option>
              <option value="2">2人</option>
              <option value="3">3人</option>
              <option value="4">4人</option>
              <option value="5">5人</option>
              <input type="hidden" id="attraction" name="attraction" value="VTUBER">
              <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
              <input type="hidden" id="status" name="status" value="pending">
              <input type="hidden" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
            </select>
            <input type="submit" name="insertdata" value="予約する" class="yoyaku-btn"
                        onClick="javascript:return confirm('Are you sure to submit this');">
            <?php

                    }
                    ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!--Cardgameコンテンツ-->
  <button class="btn btn_cardgame attraction_btn md-btn" data-target="modal04">Cardgame </button>
  
  <!--Cardgameモーダル-->
  <div id="modal04" class="modal">
    <div class="md-overlay md-close"></div>
    <div class="md-contents">
      <div class="md-inner cardgame-inner">
        <div class="att-header">
          <div class="user"> <img alt="User icon" src="image/user.svg" /> <?php echo $_SESSION['username']; ?> </div>
          <h1 class="att-name">Cardgame</h1>
          <a href="home.php" class="xmark"><img src="image/xmark.svg" alt="Xmark"></a> </div>
        <div class="att-main"> <img src="image/ar.jpg" width="96%" height="auto" alt="" />
          <p class="att-desc">ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。ARの説明文が表示されます。</p>
        </div>
        <!--AR予約フォーム-->
        <div class="yoyaku-form">
          <p class="att-col">プレイ時間　： 3分</p>
          
          <!--待ち時間:&nbsp;&nbsp;&nbsp;分 --> 
          
          <!-- Number of Person AR-->
          <table>
            <thead>
              <!-- <tr>
                                                        <th>Log Id</th>
                                                        <th>Person</th>
                                                    </tr> -->
              
            </thead>
            <tbody>
              <?php
                      $result = $con->query("SELECT * FROM crux where status = 'pending' AND attraction = 'CARDGAME'");
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          ?>
              <!-- <tr>
                                                                                                        <td>
                                                                                                            <?php echo $row['id']; ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php echo $row['person']; ?>
                                                                                                        </td>
                                                                                                    </tr> -->
              <?php
                        }
                      } else {
                        ?>
              <tr>
                <td colspan="7" class="noyoyaku">誰も予約していません。</td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
          <?php
                  $result = mysqli_query($con, "SELECT count(id) FROM crux where status = 'pending' AND attraction = 'CARDGAME'") or die();
                  while ($rows = mysqli_fetch_array($result)) {
                    ?>
          <p class="att-col">現在予約中の人　： <?php echo $rows['count(id)']; ?> </p>
          <p class="att-col">現在の待ち時間　：
            <?php
                      // Waiting time per game
                      $x = 3;
                      // Waiting person
                      $x *= $rows['count(id)'];

                      echo $x;
                      ?>
            <!-- Person -->
            <?php } ?>
            分 </p>
          <form id="form-cardgame" class="form" action="home.php" method="post">
            
            <!--action属性に送信先のURLを追加--> 
            <!--       <label for="num">ID：</label>
                                                                <input id="num-ar" type="number" name="yoyaku-num-ar">-->
            
            <?php
                    $sql_get1 = mysqli_query($con, "SELECT * FROM crux WHERE username =  '$_SESSION[username]' AND status = 'pending' AND (attraction = 'Cardgame' OR attraction = 'VR' OR attraction = 'VTuber' OR attraction = 'AR')");
                    if (mysqli_num_rows($sql_get1) > 0) {
                      while ($result = mysqli_fetch_assoc($sql_get1)) {
                        echo '<h1 class="now-yoyaku">予約中</h1> ';
                        echo '<button class="cancel-btn">キャンセル</button>';
                      }

                    } else {
                      ?>
            <select name="person" id="person" class="person" required>
              <option value="">(必須)プレイ人数</option>
              <option value="1">1人</option>
              <option value="2">2人</option>
              <option value="3">3人</option>
              <option value="4">4人</option>
              <option value="5">5人</option>
              <input type="hidden" id="attraction" name="attraction" value="Cardgame">
              <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
              <input type="hidden" id="status" name="status" value="pending">
              <input type="hidden" id="name" name="name" value="<?php echo $_SESSION['name']; ?>">
            </select>
            <input type="submit" name="insertdata" value="予約する" class="yoyaku-btn"
                        onClick="javascript:return confirm('Are you sure to submit this');">
            <?php

                    }
                    ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Chris's code  <ul>
                                                                                                <li><a href='attraction_A.php'>Attraction A</a></li>
                                                                                                <li><a href='attraction_B.php'>Attraction B</a></li>
                                                                                                <li><a href='attraction_C.php'>Attraction C</a></li>
                                                                                                <li><a href='attraction_D.php'>Attraction D</a></li>
                                                                                              </ul>--> 
<footer></footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script type="text/javascript" src="script.js"></script>
</body>
<?php } ?>
</html>
<?php
} else {
  header("Location: index.php");
  exit();
}

?>
<!-- SELECT (@pos := @pos + 1) AS position, name FROM crux JOIN (SELECT @pos := 0) r WHERE attraction = 'AR' AND id <= '88' ;
  FOR POSITIONING SELECT count(id), name FROM crux WHERE attraction='AR' AND id < '88' +1; table 1=users table 2=crux
  SELECT T1.id FROM crux AS T1 JOIN users AS T2 ON T1.username=T2.username WHERE T2.username='karl' ; -->