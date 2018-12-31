<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get("/halte/", function (Request $request, Response $response){
    $sql = "SELECT * FROM mytable";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/halte/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM mytable WHERE id=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/halte/search/", function (Request $request, Response $response, $args){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM mytable WHERE RUAS_JALAN LIKE '%$keyword%' OR TITIK_LOKASI LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

//add
$app->post("/halte/", function (Request $request, Response $response){

    $new_halte = $request->getParsedBody();

    $sql = "INSERT INTO mytable (RUAS_JALAN, TITIK_LOKASI, KETERANGAN) VALUE (:RUAS_JALAN, :TITIK_LOKASI, :KETERANGAN)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":RUAS_JALAN" => $new_halte["RUAS_JALAN"],
        ":TITIK_LOKASI" => $new_halte["TITIK_LOKASI"],
        ":KETERANGAN" => $new_halte["KETERANGAN"]
    ];

    if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

//EDIT
$app->put("/halte/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $new_halte = $request->getParsedBody();
    $sql = "UPDATE mytable SET RUAS_JALAN=:RUAS_JALAN, TITIK_LOKASI=:TITIK_LOKASI, KETERANGAN=:KETERANGAN WHERE id=:id";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id" => $id,
        ":RUAS_JALAN" => $new_halte["RUAS_JALAN"],
        ":TITIK_LOKASI" => $new_halte["TITIK_LOKASI"],
        ":KETERANGAN" => $new_halte["KETERANGAN"]
    ];

    if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});


//delete
$app->delete("/halte/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "DELETE FROM mytable WHERE id=:id";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id" => $id
    ];

    if($stmt->execute($data))
        return $response->withJson(["status" => "success", "data" => "1"], 200);

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

