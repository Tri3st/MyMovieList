<?php

    function getImdbRecord($imdbid){
        $apikey="8fb94374";
        $imdbid = str_replace(' ', '%20', $imdbid);
        $path = "https://www.omdbapi.com/?apikey={$apikey}&t={$imdbid}";
        $json = file_get_contents($path);
        return json_decode($json, TRUE);
    }

    function showMovDetails($movdata){
        if (empty($movdata)){
            echo "<div class='movie-details'>movdata is empty</div>";
        } else {
            $ratingstring = "";
            $rt = $movdata["Ratings"];
            if (empty($rt)){
                $ratingstring = "not available";
            } else 
            if(count($rt)>1){
                foreach($rt as $rstr){
                    $ratingstring .= "{$rstr["Source"]} : {$rstr["Value"]}<br>"; 
                }
            } else if (count($rt)==1){
                $ratingstring = "{$rt[0]["Source"]} : {$rt[0]["Value"]}<br>";
            } 
            echo "<div class='movie-details'>";
            echo "<div id='movieTitle'><h3>{$movdata['Title']}</h3></div>";
            echo "<div id='movieGenre'><h4>{$movdata["Genre"]}</h4></div>";
            echo "<div id='movieYear'>Year : {$movdata['Year']}</div>";
            echo "<div id='movieRatings'>{$ratingstring}</div></div>";
        }
    }
    
    //in: string with genres seprated with a comma (how it comes from the api)
    //      1 or more "Drama, Thriller"
    // 0 for unknown
    //out: array of numbers (genres) for in the database (sorted)
    function genres($genrestring){
        $genreNumArray = array();
        $genrearray = array();
        if (strlen($genrestring)>0){
            $genrearray = explode(', ', $genrestring);
            foreach($genrearray as $str2){
                $str3 = genreType($str2);
                array_push($genreNumArray, $str3);
            }
        }
        sort($genreNumArray);
        return $genreNumArray;
    }
    
    //in: genre in string form
    //out: genre in number form acoording to imdb genres
    function genreType($genrestring){
        $genreNum = 0;
        if(!empty($genrestring)){
            switch(strtolower($genrestring)){
                case 'crime':
                    $genreNum = 1;
                    break;
                case 'drama':
                    $genreNum = 2;
                    break;
                case 'thriller':
                    $genreNum = 3;
                    break;
                case 'action':
                    $genreNum = 4;
                    break;
                case 'comedy':
                    $genreNum = 5;
                    break;
                case 'fanstasy':
                    $genreNum = 6;
                    break;
                case 'horror':
                    $genreNum = 7;
                    break;
                case 'mystery':
                    $genreNum = 8;
                    break;
                case 'romance':
                    $genreNum = 9;
                    break;
                case 'western':
                    $genreNum = 10;
                    break;
                case 'war':
                    $genreNum = 11;
                    break;
                case 'sci-fi':
                case 'scifi':
                    $genreNum = 12;
                    break;
                case 'adventure':
                    $genreNum = 13;
                    break;
                case 'animation':
                    $genreNum = 14;
                    break;
                case 'superhero':
                    $genreNum = 15;
                    break;
            }
        }
        return $genreNum;
    }
    
    // convert the ratings from IMDB(x/10), RottenTomatoes(%) and Metacritic(xx/100) to xx/100
    function ratingConv($ratingStr){
        if(strstr($ratingStr,'/')){
            $ratArr = array();
            $ratArr = explode('/', $ratingStr);
            if($ratArr[1] == 10){
                return $ratArr[0] * 10;
            } else if ($ratArr[1] == 100){
                return $ratArr[0];
            } else return -1;
        } else if (strstr($ratingStr, '%')){
            $ratArr = explode('%', $ratingStr);
            return $ratArr[0];
        } else return -1;
        
    }
    
    //make a nice table list of the db info
    function makeTable(){
        $sql = "";
    }
?>