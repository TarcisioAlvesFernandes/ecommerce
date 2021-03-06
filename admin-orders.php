<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Order;
use \Hcode\Model\Cart;
use \Hcode\Model\OrderStatus;


$app->get("/admin/orders/:idorder/status", function($idorder){

    User::verifyLogin();

    $order = new Order();
    $order->get((int)$idorder);
    
    $page = new PageAdmin();   

    $page->setTpl("order-status", [        
        'order'=>$order->getValues(),
        'status'=>OrderStatus::listAll(),
        'msgSuccess'=>Order::getSuccess(),
        'msgError'=>Order::getError()
    ]);

});

$app->post("/admin/orders/:idorder/status", function($idorder){

    User::verifyLogin();

    if(!isset($_POST['idstatus']) || $_POST['idstatus'] == ''){
        Order::setError("Houve um erro, favor preencher o campo corretamente");
        header("Location: /admin/orders/:idorder/status");    
        exit;
    }

    $order = new Order();
    $order->get((int)$idorder);
    $order->setidstatus((int)$_POST['idstatus']);

    $order->save();

    Order::setSuccess("O Status foi atualizado com sucesso.");
    
    header("Location: /admin/orders/".$order->getidorder()."/status");
    exit;

});

$app->get("/admin/orders/:idorder/delete", function($idorder){

    User::verifyLogin();

    $order = new Order();

    $order->get((int)$idorder);

    if(!isset($idorder) || $idorder == ''){
        //User::setError("Houve um erro, tente novamente.");
        header("Location: /admin/orders");    
        exit;
    }

    if($order->getidorder() == ''){
        Order::setError("Esse pedido não existe.");
        header("Location: /admin/orders");    
        exit;
    }
    
    $order->delete();

    Order::setSuccess("O pedido foi excluido com sucesso.");

    header("Location: /admin/orders");
    exit;

});

$app->get("/admin/orders/:idorder", function($idorder){

    User::verifyLogin();

    $order = new Order();
    $order->get((int)$idorder);

    $cart = $order->getCart();
    
    $page = new PageAdmin();

    $page->setTpl("order", [
        "order"=>$order->getValues(),
        "cart"=>$cart->getValues(),
        "products"=>$cart->getProducts()
    ]);

});

$app->get("/admin/orders", function(){
    

    User::verifyLogin();
	
	$search = (isset($_GET['search'])) ? $_GET['search'] : '';
	$page = (isset($_GET['page'])) ? $_GET['page'] : 1;

	if($search != ''){
		$pagination = Order::getPageSearch($search, $page);
	}else{
		$pagination = Order::getPage($page);
	}	
	
	$pages = [];

	for ($x = 0; $x < $pagination['pages']; $x++){

		array_push($pages,[
			"href"=>"/admin/orders?". http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);		
	}		

    $page = new PageAdmin();

    $page->setTpl("orders", [
        "orders"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages,
        "msgError"=>Order::getError(),
        "msgSuccess"=>Order::getSuccess()
    ]);

});





?>

