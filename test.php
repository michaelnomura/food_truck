<!DOCTYPE html>
<head>
<title>Food Truck</title>
</head>
<body>

<h2>Menu Items:</h2>
    <?php 
    $tacos = $_POST[tacoCount];
    echo "test";
    echo "Hello World" . $_POST[tacoCount];
    ?>
<form action="" method="post">
    Taco(s) <br /><input type="number" name="item" value="tacoCount"><br />
    Extras:<br />
    Cheese <input type="checkbox" name="cheese" value="Cheese"><br />
    Beans <input type="checkbox" name="beans" value="Beans"><br />
    Onions <input type="checkbox" name="onions" value="Onions"><br />
    Sundae(s) <br /><input type="number" name="item" value="sundaeCount"><br />
    Extras:<br />
    Chocolate Syrup <input type="checkbox" name="chocSyrup" value="Chocolate Syrup"><br />
    Nuts <input type="checkbox" name="nuts" value="Nuts"><br />
    Salad(s) <br /><input type="number" name="item" value="saladCount"><br />
    Extras:<br />
    Crutons <input type="checkbox" name="crutons" value="Crutons"><br />
    Chicken <input type="checkbox" name="chicken" value="Chicken"><br />
    <input type="submit" />
    </form>
</body>

