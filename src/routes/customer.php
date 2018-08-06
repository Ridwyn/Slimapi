<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app =new \Slim\App;

//to display error trace ad msg
$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

//Enable cross platform
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});


//Get All customers
$app->get('/api/customers', function(Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try{
        //Get the db object
        $db= new db();
        //Connect 
        $db =$db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db =null;
        echo json_encode($customers);
    } catch(PDOException $e){
            echo'{"error":{"test":'.$e->getMessage().'}';
    }

});

//Get All single Customer
$app->get('/api/customer/{id}', function(Request $request, Response $response) {
    $id= $request->getAttribute('id');  //getttribute is used for the URL to get thw number put into the url
    $sql = "SELECT * FROM customers WHERE id =$id";

    try{
        //Get the db object
        $db= new db();
        //Connect 
        $db =$db->connect();

        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db =null;
        echo json_encode($customer);
    } catch(PDOException $e){
            echo'{"error":{"test":'.$e->getMessage().'}';
    }

});

//ADD a  Customer
$app->post('/api/customer/add', function(Request $request, Response $response) {
    $first_name= $request->getParam('first_name'); // getParam is used to get the param within the db
    $last_name= $request->getParam('last_name');
    $phone= $request->getParam('phone');
    $email= $request->getParam('email');
    $address= $request->getParam('address');
    $city= $request->getParam('city');
    $state= $request->getParam('state');


    $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,state)VALUES
    (:first_name,:last_name,:phone,:email,:address,:city,:state)";

    try{
        //Get the db object
        $db= new db();
        //Connect 
        $db =$db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);

        $stmt->execute();

        echo '{"notice:"{"text": "Customer added"}';

    } catch(PDOException $e){
            echo'{"error":{"test":'.$e->getMessage().'}';
    }

});



//Update a  Customer
$app->put('/api/customer/update/{id}', function(Request $request, Response $response) {
    $id= $request->getAttribute('id');
    $first_name= $request->getParam('first_name'); // getParam is used to get the param within the db
    $last_name= $request->getParam('last_name');
    $phone= $request->getParam('phone');
    $email= $request->getParam('email');
    $address= $request->getParam('address');
    $city= $request->getParam('city');
    $state= $request->getParam('state');


    $sql = "UPDATE customers SET
            first_name = :first_name,
            last_name = :last_name,
            phone = :phone,
            email = :email,
            address = :address,
            city = :city,
            state = :state
    WHERE id =$id";

    try{
        //Get the db object
        $db= new db();
        //Connect 
        $db =$db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);

        $stmt->execute();

        echo '{"notice:"{"text": "Customer Updated"}';

    } catch(PDOException $e){
            echo'{"error":{"test":'.$e->getMessage().'}';
    }

});

//Delete Customer
$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response) {
    $id= $request->getAttribute('id');  //getttribute is used for the URL to get thw number put into the url
    $sql = "DELETE FROM customers WHERE id =$id";

    try{
        //Get the db object
        $db= new db();
        //Connect 
        $db =$db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '{"notice:"{"text": "Customer Deleted"}';
    } catch(PDOException $e){
            echo'{"error":{"test":'.$e->getMessage().'}';
    }

});
