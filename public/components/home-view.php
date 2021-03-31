<?php

use Custom\DB;

require_once __DIR__ . '/../classes/FB.php';
require_once __DIR__ . '/../classes/DB.php';

try {
    $fb = Custom\Fb::init();
    $response = $fb->get('/me?fields=name,email', (string) $_SESSION['facebook_access_token']);
    $user = $response->getGraphUser();

    if(!isset($_SESSION['logged-in-user'])){
        $_SESSION['logged-in-user'] = $user->getEmail();
    }

} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$db = new Custom\DB();

$connection = $db->connect();
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//get logged in user info (id, height (if set), email)

$statement = $connection->prepare('SELECT * FROM user WHERE email = :email');
$statement->bindValue(':email', $user->getEmail());
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$result = $result[0];

//get last user weight
if(count($result) > 0){
    $weight = $connection->prepare('SELECT weight, date FROM weight WHERE userId = :id ORDER BY date DESC');
    $weight->bindValue(':id', $result['id']);
    $weight->execute();
    $weightResult = $weight->fetch(PDO::FETCH_ASSOC);
}


if(count($result) === 0){
    $statement = $connection->prepare('INSERT INTO user (email) VALUES (?)');
    $insert = $statement->execute([$user->getEmail()]);

    $result = ['email' => $user->getEmail(), 'height' => null];
}

?>

<div class="h-full flex flex-col">
    <div class="w-full bg-gray-700 p-10 flex justify-between">
        <h2 class="text-4xl ml-10">BMI Calculator</h2>
        <h2 class="hidden md:block text-3xl mr-48" id="welcome"></h2>
        <a href="<?php echo 'https://' . $_SERVER['SERVER_NAME'] . '/logout/' ?>"><i title="Sign out" class="fas fa-sign-out-alt"></i></a>
    </div>
    <div class="md:w-4/5 w-full h-full border shadow-xl m-auto">
        <div>
            <div class="bg-gray-600 py-8">
                <h3 class="text-center text-2xl">Current user information</h3>
                <div class="grid grid-cols-2 text-center">
                    <div>
                        Height: <span id="user-height"></span>

                        <div>
                            <label class="text-center my-4 text-black" for="height">
                                <input class="p-1" type="number" step="1" value="170" min="80" max="250" id="height">
                            </label>
                            <input class="text-black p-1" type="button" id="saveHeight" value="Save height">
                            <input class="hidden" type="button" id="updateHeight" value="Update height">
                        </div>
                    </div>
                    <div>
                        Last recorded weight: <span id="last-user-weight"></span><br><span id="last-update"></span>
                    </div>
                </div>
            </div>
            <form
                    class="bg-gray-400 grid grid-cols-2 m-auto p-10 text-black"
                    action="/public/api" method="POST">
                <label class="row-span-2 text-center">Weight: <br>
                    <input type="number" name="weight" id="weight" value="70" step="0.1">
                </label>
                <label class="row-span-2 text-center">Date (default = today): <br>
                    <input type="date" name="date" id="date">
                </label>
                <div class="col-span-2 flex justify-center">
                    <input
                            class="bg-green-700 p-2 rounded-2xl text-2xl text-white"
                            id="calculateBMI" type="button" value="Calculate BMI">
                    <input
                            class="bg-blue-700 p-2 rounded-2xl text-2xl text-white"
                            id="submitWeight" type="submit" value="Save weight">
                </div>
                <div class="col-span-2 text-center mt-4" id="bmi-result"></div>
            </form>

            <div id="canvas-container">
                <canvas
                        class="bg-white"
                        id="chart">
                </canvas>
            </div>

            <div class="bg-gray-700 p-8 text-center">
                <label for="reset"></label><select class="p-1 text-red-500" name="reset" id="reset">
                    <option value="weight">Reset weight</option>
                    <option value="height">Reset height</option>
                </select>
                <button class="bg-red-700 p-1" id="reset-button">Reset</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script type="module" src="../assets/js/main.js"></script>

<script>
    window.userName = '<?php echo $user->getName() ?>';
    <?php
        if($result['height']){
            echo 'window.userHeight = ' . $result['height'] . ';';
        }

        if($weightResult['weight']){
            echo 'window.lastWeight = ' . $weightResult['weight'] . ';';
            echo 'window.lastUpdate = new Date("' . $weightResult['date'] . '");';
        }
    ?>
    //TODO delete User class?
    class User {
        constructor(email, height = null) {
            this.email = email;
            this.height = height;
        }
    }
</script>

