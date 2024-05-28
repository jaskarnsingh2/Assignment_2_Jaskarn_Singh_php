<?php

/*******w******** 
    
    Name: Jaskarn Singh
    Date: 27-05-24
    Description: PHP script to generate an order invoice with validation.

****************/

// Define and validate input variables
$fullName = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING);
$postalCode = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/')));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$creditCardNumber = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{10}$/')));
$creditCardMonth = filter_input(INPUT_POST, 'expmonth', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 12)));
$creditCardYear = filter_input(INPUT_POST, 'expyear', FILTER_VALIDATE_INT);
$creditCardType = isset($_POST['cardtype']) ? $_POST['cardtype'] : null;

$currentYear = date('Y');
$validCreditCardYear = ($creditCardYear >= $currentYear && $creditCardYear <= $currentYear + 5);

// Define and validate quantity inputs
$quantity1 = filter_input(INPUT_POST, 'qty1', FILTER_VALIDATE_INT);
$quantity2 = filter_input(INPUT_POST, 'qty2', FILTER_VALIDATE_INT);
$quantity3 = filter_input(INPUT_POST, 'qty3', FILTER_VALIDATE_INT);
$quantity4 = filter_input(INPUT_POST, 'qty4', FILTER_VALIDATE_INT);
$quantity5 = filter_input(INPUT_POST, 'qty5', FILTER_VALIDATE_INT);

$quantityFields = [
    $quantity1 => ['productName' => 'iMac', 'price' => 1899.99],
    $quantity2 => ['productName' => 'Razer Mouse', 'price' => 79.99],
    $quantity3 => ['productName' => 'WD HDD', 'price' => 179.99],
    $quantity4 => ['productName' => 'Nexus', 'price' => 249.99],
    $quantity5 => ['productName' => 'Drums', 'price' => 119.99],
];

// Calculate total sum
$totalSum = 0;
foreach ($quantityFields as $quantity => $details) {
    if ($quantity > 0) {
        $totalSum += $quantity * $details['price'];
    }
}

// Validation errors
$errors = [];
if (!$fullName) $errors[] = "Invalid full name.";
if (!$address) $errors[] = "Invalid address.";
if (!$city) $errors[] = "Invalid city.";
if (!$province) $errors[] = "Invalid province.";
if (!$postalCode) $errors[] = "Invalid postal code.";
if (!$email) $errors[] = "Invalid email.";
if (!$creditCardNumber) $errors[] = "Invalid credit card number.";
if (!$creditCardMonth) $errors[] = "Invalid credit card month.";
if (!$validCreditCardYear) $errors[] = "Invalid credit card year.";
if (!$creditCardType) $errors[] = "Credit card type not selected.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Thanks for your order!</title>
</head>
<body>
    <div class="invoice">
        <?php if (empty($errors)): ?>
            <h2>Thanks for your order <?= htmlspecialchars($fullName) ?>.</h2>
            <h3>Here's a summary of your order:</h3>

            <table>
                <tbody>
                    <tr>
                        <td colspan="2">Address Information</td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><?= htmlspecialchars($address) ?></td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td><?= htmlspecialchars($city) ?></td>
                    </tr>
                    <tr>
                        <td>Province:</td>
                        <td><?= htmlspecialchars($province) ?></td>
                    </tr>
                    <tr>
                        <td>Postal Code:</td>
                        <td><?= htmlspecialchars($postalCode) ?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?= htmlspecialchars($email) ?></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quantityFields as $quantity => $details): ?>
                        <?php if ($quantity > 0): ?>
                            <tr>
                                <td><?= htmlspecialchars($quantity) ?></td>
                                <td><?= htmlspecialchars($details['productName']) ?></td>
                                <td><?= '$' . number_format($details['price'], 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Totals</td>
                        <td><?= '$' . number_format($totalSum, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

            <p>Thank you for your order!</p>
        <?php else: ?>
            <h2>There were errors with your submission:</h2>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
