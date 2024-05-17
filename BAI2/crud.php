<?php
session_start();
 

include_once('./provider.php'); 	
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$errors = [];
	if (empty($_POST['id'])) {
		$errors['id'] = "id is required";
	}
	if (empty($_POST['flightNumber'])) {
		$errors['flightNumber'] = "flightNumber is required";
	}
	if (empty($_POST['departure'])) {
		$errors['departure'] = "departure is required";
	} 
	if (empty($_POST['destination'])) {
		$errors['destination'] = "destination is required";
	} 
	if (empty($_POST['price'])) {
		$errors['price'] = "price is required";
	} 
	  
}
function createRecord($conn, $data)
{
	 
    $checkQuery = "SELECT COUNT(*) FROM maybay WHERE Id = :Id";
    $checkStatement = $conn->prepare($checkQuery);
    $checkStatement->bindParam(':Id', $data['Id']);
    $checkStatement->execute();
    $count = $checkStatement->fetchColumn();

    if ($count > 0) {
        return false;
    }

    if (strlen($data['flightNumber']) < 5) {
        return false;
    }

    $query = "INSERT INTO maybay (Id, flightNumber, Departure, Destination, Price) VALUES (:id, :flightNumber, :departure, :destination, :price)";
    $statement = $conn->prepare($query);
    $statement->bindParam(':id', $data['id']);
    $statement->bindParam(':flightNumber', $data['flightNumber']);
    $statement->bindParam(':departure', $data['departure']);
    $statement->bindParam(':destination', $data['destination']);
    $statement->bindParam(':price', $data['price']);
    $statement->execute();

    return true;
}



function readRecords($conn)
{
	$query = "SELECT * FROM maybay";
	$statement = $conn->query($query);

	$records = $statement->fetchAll(PDO::FETCH_ASSOC);

	return $records;
}

function updateRecord($conn, $id, $data)
{
    $query = "UPDATE maybay SET Id = :Id, flightNumber = :flightNumber, departure = :departure, destination = :destination, price = :price WHERE id = :id";
    $statement = $conn->prepare($query);

    // Bind parameters
    $statement->bindParam(':Id', $data['Id']);
    $statement->bindParam(':flightNumber', $data['flightNumber']);
    $statement->bindParam(':departure', $data['departure']);
    $statement->bindParam(':destination', $data['destination']);
    $statement->bindParam(':price', $data['price']);
    $statement->bindParam(':id', $id);  

   
    if ($statement->execute()) {
        echo "Record updated successfully.";
    }
}




function deleteRecord($conn, $id)
{
	$query = "DELETE FROM maybay WHERE Id = :Id";
	$statement = $conn->prepare($query);

	$statement->bindParam(':Id', $id);

	$statement->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['create'])) {
		$data = [
			'id' => $_POST['id'],
			'flightNumber' => $_POST['flightNumber'],
			'departure' => $_POST['departure'],
			'destination' => $_POST['destination'],
			'price' => $_POST['price']
		];
		createRecord($conn, $data);
	} elseif (isset($_POST['update'])) {
		$id = $_POST['id'];
		$data = [
			'id' => $_POST['id'],
			'flightNumber' => $_POST['flightNumber']
		];
		updateRecord($conn, $id, $data);
	} elseif (isset($_POST['delete'])) {
		$id = $_POST['id'];
		deleteRecord($conn, $id);
	}
}

$records = readRecords($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CRUD</title>
	<link rel="stylesheet" href="style.css">
</head>
<style>
	.red{
		color: red;
	}
</style>
<body>


	<form method="POST" action="">
		<input type="text" name="id" placeholder="Id">
		<div class="red">
			<?php
			if (isset($errors['id'])) {
				echo $errors['id'];
			}
			?>
		</div>
		<input type="text" name="flightNumber" placeholder="flightNumber">
		<div class="red">
			<?php
			if (isset($errors['flightNumber'])) {
				echo $errors['flightNumber'];
			}
			?>
		</div>
		</div>
		<input type="text" name="departure" placeholder="Departure">
		<div class="red">
			<?php
			if (isset($errors['departure'])) {
				echo $errors['departure'];
			}
			?>
		</div>
		</div>
		<input type="text" name="destination" placeholder="Destination">
		<div class="red">
			<?php
			if (isset($errors['destination'])) {
				echo $errors['destination'];
			}
			?>
		</div>
		</div>
		<input type="text" name="price" placeholder="Price">
		<div class="red">
			<?php
			if (isset($errors['price'])) {
				echo $errors['price'];
			}
			?>
		</div>
		
		<button type="submit" name="create">Create</button>
	</form>

	<table border=".1">
		<tr>
			<th>Id</th>
			<th>flightNumber</th>
			<!-- <th>Departure</th>
			<th>Destination</th>
			<th>Price</th> -->

		</tr>
		<?php foreach ($records as $record) : ?>
			<tr>
				<td><?php echo $record['Id']; ?></td>
				<td><?php echo $record['flightNumber']; ?></td>
				<!-- <td><?php echo $record['Departure']; ?></td>
				<td><?php echo $record['Destination']; ?></td>
				<td><?php echo $record['Price']; ?></td> -->
				<td>
					<form method="POST" action="">
						<input type="text" name="id" value="<?php echo $record['Id']; ?>">
						<input type="text" name="flightNumber" value="<?php echo $record['flightNumber']; ?>">
						<button type="submit" name="update">Update</button>
						<button type="submit" name="delete">Delete</button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>

</html>