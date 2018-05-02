<!DOCTYPE html>
<html>
    <head>
        <title>About</title>
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
                        <a class="nav-item nav-link" href="csproject.php">Home <span class="sr-only">(current)</span></a>
                        <a class="nav-item nav-link active" href="about.php">About</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="jumbotron">
            <h2 class="display-4">About</h2>
        </div>
        <div class='container'>
            <div class="card">
                <h5 class="card-header">Setup</h5>
                <div class="card-body">
                    First, I configured a DigitalOcean droplet with a LAMP (Linux, Apache, MySQL, PHP) stack. 
                    This was done using Ubuntu. The following commands were run (in this order):
                    <pre><code>
                    $ sudo apt-get install apache2
                    $ sudo apt-get install mysql-server
                    $ sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql
                    $ mysql_secure_installation
                    </code></pre>
                    Bootstrap CSS files were also added to the webserver.
                </div>
                <h5 class="card-header">Database</h5>
                <div class="card-body">
                    After MySQL was up and running, I created the database:
                    <pre><code>
                    mysql&gt; CREATE DATABASE space;</pre>
                    </code>
                    Then I created a table (with no relationships) with data from all Planets and Dwarf Planets, and some natural satellites:
                    <pre><code>
                    mysql&gt; CREATE TABLE planet_table(
                    ID INT NOT NULL AUTO_INCREMENT,
                    Name VARCHAR(255),
                    Type ENUM(
                        'Terrestrial Planet',
                        'Gas Planet',
                        'Dwarf Planet',
                        'Star',
                        'Natural Satellite'
                    ),
                    Moons INT,
                    OrbitalPeriod BIGINT,
                    DistanceFromSun BIGINT,
                    MeanTemperature SMALLINT,
                    PRIMARY KEY(ID)
                    );</code></pre>
                    After manually entering each table row, the database was ready to be displayed on the website.
                </div>
                <h5 class="card-header">Website</h5>
                <div class="card-body">
                    Apache2 was configured to work with PHP, which would be use to retrieve MySQL data. The connection to the database was created with PHP:
                    <pre><code>
                        &lt;?php
                            $host = "localhost";
                            $dbname = "space";
                            $dbuser = "***";
                            $password = "***"; 

                            $dsn = "mysql:host=$host;dbname=$dbname;";
                            $db = new PDO($dsn, $dbuser, $password);
                            <b>$sql = "SELECT * FROM planet_table";</b>   

                            $statement = $db->query($sql);
                            $results = $statement->fetchAll(PDO::FETCH_ASSOC);...                          
                        </code></pre>
                    Note the bolded line. This could be changed to something like <code>$sql = "SELECT * FROM planet_table WHERE ID = 7;"</code> and it would return only results for Deimos. 
                    After the query is completed, a <i>foreach</i> loop executes that creates a new HTML table row for each row in the database.
                    <pre><code>
                        foreach ($results as $row) {
                            echo"&lt;tr&gt;";
                            echo "&lt;td&gt;" . $row["ID"] . "&lt;/td&gt;";
                            echo "&lt;td&gt;&lt;b&gt;" . $row["Name"] . "&lt;/b&gt;&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["Type"] . "&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["Diameter"] . " mi&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["Moons"]. "&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["OrbitalPeriod"] . " days&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["RotationPeriod"]. " hours&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["DistanceFromSun"] . " mi&lt;/td&gt;";
                            echo "&lt;td&gt;" . $row["MeanTemperature"] . " °F&lt;/td&gt;";
                            echo "</tr>";
                        }</code></pre>
                    The table can also be sorted by clicking the triangle next to the column name. This done by using <code>&lt;a href=&gt;</code> to add a parameter to the URL. Once PHP can find this parameter, the SQL query changes and sorts into ascending order by default. More on this later. 
                    <pre><code>
                            $sql = "SELECT * FROM planet_table";
                    
                            if ($_GET['s'] == 'ID') {
                                $sql .= " ORDER BY ID";
                            }    
                        </code></pre>
                    The HTML form, like the HTML table, uses a <i>foreach</i> loop that creates a selectable line for each row stored in the database table.
                    <pre><code>
                            $sql = "SELECT * FROM planet_table ORDER BY Name";
                            echo "&lt;option value='NA'&gt;Select object...&lt;/option&gt;";
                                    
                            foreach($results as $row) {
                                echo "&lt;option value='".$row["Name"]."'&gt;".$row["Name"]."&lt;/option&gt;";
                            }
                        </code></pre>
                    The SQL query here is a bit different, as we only want the names on the dropdown, nothing else. Next comes displaying data that the user requests. Using JavaScript, the form automatically sends this data when the name is clicked. Each option stores a value which is equal to the name in the database. This, of course, is done in the <i>foreach</i> loop above. This value is inserted into the URL as a parameter. For example, in <code>teruh.co/csproject.php?pl=Neptune</code>, the <code>?pl=Neptune</code> parameter specifes the name of the planet the user wants to retrieve. This parameter is used to alter the query. If we use the Neptune example, the query becomes: 
                    <pre><code>
                            $pl = strval($_GET['pl']);
                            $sql =  "SELECT * FROM planet_table WHERE Name = '".$pl."'";
                        </code></pre>
                    Note that <code>$_GET['pl']</code> searches for the parameter in the URL, converts it to a variable which is used in the WHERE portion of the query.
                    <pre><code>
                            if ($oDistance >= 1) {
                                echo "&lt;p&gt;" . $oName . " is &lt;b&gt;" . $oDistance . "&lt;/b&gt; miles away from the Sun at apogee.&lt;/p&gt;&lt;br/&gt;";
                            }   
                        </code></pre>
                    The <i>if</i> statement was used here because we wouldn't want the user to see this message when they select The Sun (as that would output "The Sun is 0 miles away from the Sun at apogee." Gross!).
                    Another <i>if</i> statement was used in the temperature portion. 
                    <pre><code>
                            if($oTemp >= 100) {
                                echo "That's hot!&lt;/p&gt;&lt;br/&gt;";
                            } elseif ($oTemp <= 32) {
                                 echo "That's cold!&lt;/p&gt;&lt;br/&gt;";
                            } else {
                                echo "Comfortable!&lt;/p&gt;&lt;br/&gt;";
                            }
                        </code></pre>
                    The planetary images are named as each planet name appears in the database. To retrieve the image, we concatenate the name from the row in the HTML code.
                    <pre><code>
                            echo "&lt;span class='align-middle'&gt;
                            &lt;img src='/img/" . $oName . ".jpg' width='256' height='256' class='float-right' alt='". $oName . "'&gt;
                            &lt;/span&gt;";
                        </code></pre>
                </div>
            </div>
        </div>
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