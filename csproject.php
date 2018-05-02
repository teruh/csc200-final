<!DOCTYPE html>
<html>
    <head>
        <title>DNNASA Database</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/custom.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: black;">
            <div class="container">
                <a class="navbar-brand" href="#">
                <img src="/img/moonico.png" width="30" height="30" class="d-inline-block align-top" alt="">
                </a>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link active" href="csproject.php">Home <span class="sr-only">(current)</span></a>
                        <a class="nav-item nav-link" href="about.php">About</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="jumbotron">
            <h1 class="display-1"><b>Definitely Not NASA</b></h1>
            <h2 class="display-4">Planetary Database</h2>
        </div>
        <div class='container'>
            <div class='card text-white' style="background-color: black; margin-bottom: 30px;">
                <div class="card-header" style='background: rgba(185, 185, 185, 1);'>Object Info</div>
                <span class="border border-dark rounded">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <select class="form-control" id="formControlSelect1" name="pl" onchange='this.form.submit()'>
                                <?php
                                    $host = "localhost";
                                    $dbname = "space";
                                    $dbuser = "***";
                                    $password = "***";
                                    
                                    $dsn = "mysql:host=$host;dbname=$dbname;";
                                    $db = new PDO($dsn, $dbuser, $password);
                                    $sql = "SELECT * FROM planet_table ORDER BY Name";
                                    
                                    $statement = $db->query($sql);
                                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    echo "<option value='NA'>Select object...</option>";
                                    
                                    foreach($results as $row) {
                                    	echo "<option value='".$row["Name"]."'>".$row["Name"]."</option>";
                                    }
                                    
                                    $db = null;
                                    $sql = null;
                                    ?>
                                </select>
                            </div>
                        </form>
                        <?php
                            $pl = strval($_GET['pl']);
                            
                            $host = "localhost";
                            $dbname = "space";
                            $dbuser = "***";
                            $password = "***";
                            
                            $dsn = "mysql:host=$host;dbname=$dbname;";
                            $db = new PDO($dsn, $dbuser, $password);
                            $sql =  "SELECT * FROM planet_table WHERE Name = '".$pl."'";
                            
                            $statement = $db->query($sql);
                            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($results as $row) {
                            	$oName = $row["Name"];
                            	$oType = $row["Type"];
                            	$oSize = $row["Diameter"];
                            	$oMoon = $row["Moons"];
                            	$oOrbit = $row["OrbitalPeriod"];
                            	$oRot = $row["RotationPeriod"];
                            	$oDistance = $row["DistanceFromSun"];
                            	$oTemp = $row["MeanTemperature"];
                            
                            echo "<div class='row'>";
                            	echo "<div class='col-8'>";
                            	echo "<h3>" . $oName . "<small class='text-muted'> (" . $oType . ", " . $oMoon . " moons)</small></h3><br/>";
                            
                            	if ($oDistance >= 1) {
                            		echo "<p>" . $oName . " is <b>" . $oDistance . "</b> miles away from the Sun at apogee.</p><br/>\n";
                            	}
                            	$cEarth = round($oSize / 7918);
                            	$cObject = round(7918 / $oSize);
                            	if($cEarth > 1) {
                            		echo "<p>" . $oName . " is about <b>" . $oSize . "</b> miles across. <b>" . $cEarth . "</b> Earths could line up along the diameter!</p><br/>\n";
                            	} elseif ($cEarth == 1 || $cObject == 1) {
                            		echo "<p>" . $oName . " is about <b>" . $oSize . "</b> miles across.</p><br/>\n";
                            	} else {
                            		echo "<p>" . $oName . " is about <b>" . $oSize . "</b> miles across. It could line up along Earth's diameter <b>" . $cObject . "</b> times!</p><br/>\n";
                            	}                            	
                            echo "<p>". $oName . " is about <b>" . $oTemp . "</b> degrees Farenheit on average. ";
                            	if($oTemp >= 100) {
                            		echo "That's hot!</p><br/>\n";
                            	} elseif ($oTemp <= 32) {
                            		echo "That's cold!</p><br/>\n";
                            	} else {
                            		echo "Comfortable!</p><br/>\n";
                            	}
                            	echo "<p>One day on " . $oName . " is about <b>" . $oRot . "</b> Earth hours. One year is about <b>" . $oOrbit . "</b> Earth days.<br/>\n";
                            	echo "</div>";
                            
                            	echo "<div class='col-4'>";
                            	echo "<span class='align-middle'><img src='/img/" . $oName . ".jpg' width='256' height='256' class='float-right' alt='". $oName . "'></span>";
                            	echo "</div>";
                            	echo "</div>";
                            }
                            ?>
                    </div>
                </span>
            </div>
        </div>
        <div class='container'>
            <div class="card-header" style='color: white; background: rgba(185, 185, 185, 1);'>Object Database</div>
            <table class='table table-hover table-dark' style='background: rgba(185, 185, 185, 0.4);'>
            <thead class='thead-light'>
                <tr>
                    <th>ID<a href='csproject.php?s=ID'>&#9650;</a></th>
                    <th>Name<a href='csproject.php?s=Name'>&#9650;</a></th>
                    <th>Type<a href='csproject.php?s=Type'>&#9650;</a></th>
                    <th>Diameter<a href='csproject.php?s=Diameter'>&#9650;</a></th>
                    <th>Moons<a href='csproject.php?s=Moons'>&#9650;</a></th>
                    <th>OrbitalPeriod<a href='csproject.php?s=OrbitalPeriod'>&#9650;</a></th>
                    <th>RotationPeriod<a href='csproject.php?s=RotationPeriod'>&#9650;</a></th>
                    <th>DistanceFromSun<a href='csproject.php?s=DistanceFromSun'>&#9650;</a></th>
                    <th>MeanTemperature<a href='csproject.php?s=MeanTemperature'>&#9650;</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $host = "localhost";
                    $dbname = "space";
                    $dbuser = "***";
                    $password = "***";
                    
                    $dsn = "mysql:host=$host;dbname=$dbname;";
                    $db = new PDO($dsn, $dbuser, $password);
                    $sql = "SELECT * FROM planet_table";
                    
                    if ($_GET['s'] == 'ID') {
                    	$sql .= " ORDER BY ID";
                    }
                    
                    if ($_GET['s'] == 'Name') {
                    	$sql .= " ORDER BY Name";
                    }
                    
                    if ($_GET['s'] == 'Type') {
                    	$sql .= " ORDER BY Type";
                    }
                    
                    if ($_GET['s'] == 'Diameter') {
                    	$sql .= " ORDER BY Diameter";
                    }
                    
                    if ($_GET['s'] == 'Moons') {
                    	$sql .= " ORDER BY Moons";
                    }
                    
                    if ($_GET['s'] == 'OrbitalPeriod') {
                    	$sql .= " ORDER BY OrbitalPeriod";
                    }
                    
                    if ($_GET['s'] == 'RotationPeriod') {
                    	$sql .= " ORDER BY RotationPeriod";
                    }
                    
                    if ($_GET['s'] == 'DistanceFromSun') {
                    	$sql .= " ORDER BY DistanceFromSun";
                    }
                    
                    if ($_GET['s'] == 'MeanTemperature') {
                    	$sql .= " ORDER BY MeanTemperature";
                    }
                    
                    $statement = $db->query($sql);
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($results as $row) {
                    	$oID = $row["ID"];
                    	$oName = $row["Name"];
                    	$oType = $row["Type"];
                    	$oSize = $row["Diameter"];
                    	$oMoon = $row["Moons"];
                    	$oOrbit = $row["OrbitalPeriod"];
                    	$oRot = $row["RotationPeriod"];
                    	$oDistance = $row["DistanceFromSun"];
                    	$oTemp = $row["MeanTemperature"];
                    
                    	echo"<tr>";
                    	echo "<td>" . $oID . "</td>";
                    	echo "<td><b>" . $oName . "</b></td>";
                    	echo "<td>" . $oType . "</td>";
                    	echo "<td>" . $oSize . " mi</td>";
                    	echo "<td>" . $oMoon . "</td>";
                    	echo "<td>" . $oOrbit . " days</td>";
                    	echo "<td>" . $oRot . " hours</td>";
                    	echo "<td>" . $oDistance . " mi</td>";
                    	echo "<td>" . $oTemp . " °F</td>";
                    	echo "</tr>";
                    }
                    echo "</tbody>
                    </table>";
                    
                    $db = null;
                    $sql = null;
                    ?>
        </div>
        <div class="sticky-bottom">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: black;">
        <div class="container">
        <span class="navbar-text">
        Made by Zach Clark // CSC 200 Final Project // <a href="http://github.com/teruh/csc200-final">GitHub Repo</a>
        </span>
        </div>
        </nav>
        </div>
    </body>
</html>