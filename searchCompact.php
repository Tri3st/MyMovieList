<?php
    include 'includes/head.php';
    include 'includes/header.php';


    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $movdata = array();
    $mtitle = "";
    
    if(isset($_POST['inputMovie'])){
        $mtitle = strip_tags($_POST["inputMovie"]);
        $movdata = getImdbRecord($mtitle);
    }
   
?>

    <div class="mainTitle"><h1>Film Lijstje</h1><br><br></div>
    <div class="mainInput">
    <form id="#movieform" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label for="inpMov">Film : </label>
      <input id="inpMov" type="text" name="inputMovie" value="<?php echo (isset($_POST['inputMovie'])?$_POST['inputMovie']:'') ?>" placeholder="Movie..." required>
      <input id="movbtn" type="submit" name="inputBtn" value="Zoek!">
      <input type="submit" name="addInfo" id="addInfo" value="<?php echo (isset($_POST['inputMovie'])?"Add " . $movdata['Title']:'Find a movie first') ?>">
    </form>
    </div>
    <?php 
    if(isset($_POST["inputMovie"])){
        showMovDetails($movdata);
    } else {
        echo "<div class='movie-details'>";
        echo "<div id='movieTitle'><h3>Title</h3></div>";
        echo "<div id='movieGenre'><h4>Genre</h4></div>";
        echo "<div id='movieYear'>Year : 0000</div>";
        echo "<div id='movieRatings'>Ratings</div></div>";
    }
    ?>
    <div class="testInfo">
    <?php
    if(isset($_POST['addInfo'])){
        $yearint = intval($movdata['Year']);
        $genreArray = genres($movdata['Genre']);
        
        $sql = "INSERT INTO films (title, year, plot) VALUES ('{$movdata['Title']}', {$yearint}, '{$movdata['Plot']}')";
        
        //Add the movie in the db
        if (mysqli_query($conn, $sql)) {
            echo "Record created successfully"."<br>";
            echo "{$movdata['Title']} {$movdata['Year']}<br>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql2 = "SELECT id FROM films WHERE title = '{$movdata['Title']}'";
        $result = mysqli_query($conn, $sql2);
        $idnum = 0; //later put the movie_id in this one. also for ratings.
        //get genres and add them to db
        $sql4 = "INSERT INTO genrefilm VALUES ";
        if($result){
            $rs = mysqli_fetch_row($result);
            $idnum= intval($rs[0]); 
        }
        foreach($genreArray as $gnum){
            $lastnum = $genreArray[count($genreArray)-1];
            $sql4 .= " ({$gnum}, {$idnum})" . ($gnum==$lastnum?"":",");
        }
        mysqli_query($conn, $sql4);
        //Ratings :
        
        $sql5 = "INSERT INTO rating VALUES ";
        if($movdata['Ratings']){
            foreach($movdata['Ratings'] as $rat){
                $end = $movdata['Ratings'][count($movdata['Ratings'])-1];
                switch($rat['Source']){
                    case 'Internet Movie Database':
                        $imdbval = ratingConv($rat['Value']);
                        $sql5 .= "(1, {$idnum}, {$imdbval})";
                        break;
                    case 'Rotten Tomatoes':
                        $rtval = ratingConv($rat['Value']);
                        $sql5 .= "(2, {$idnum}, {$rtval})";
                        break;
                    case 'Metacritic':
                        $mcval = ratingConv($rat['Value']);
                        $sql5 .= "(3, {$idnum}, {$mcval})";
                        break;
                }
                $sql5 .= $end==$rat?"":",";
            }
        }
        mysqli_query($conn, $sql5);
    } 
    ?>
    </div>
    <div>
    <?php echo "{$movdata['Title']} {$movdata['Year']}<br>";?>
    </div>
    
<?php 
    include 'includes/footer.php';
?>