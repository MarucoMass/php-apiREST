<?php

class BookingController
{

    public function __construct(private BookingGateway $gateway)
    {
        
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, ?string $id): void
    {
        switch($method){
            case 'DELETE':
                $canDelete = $this->gateway->canDeleteClass($id);
                if (isset($canDelete["success"])) {
                    $this->gateway->deleteClass($id);
                    http_response_code(200);
                    echo "Reservation deleted.";
                } else {
                    http_response_code(422);
                    echo $canDelete["error"];
                }
                break;
            default:
                http_response_code(405);
                header("Allow: DELETE");
        }
    }

    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case 'GET':
                $data = $this->gateway->getAll();
                if($data){
                    echo json_encode($data);
                } else {
                    http_response_code(404);
                }
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);

                $dataValidation = $this->validateData($data);

                if (!isset($dataValidation["success"])) {
                    http_response_code(422);
                    echo $dataValidation["error"];
                    return;
                } 

                $checkedData = $this->gateway->verifyAndFetch($data);

                    if(is_array($checkedData) && empty($checkedData["error"])){
                        $this->gateway->bookClass($checkedData);
                        http_response_code(201);
                        echo "Booking done";
                    } 
                    else {
                        http_response_code(422);
                        echo $checkedData["error"];
                    }
               
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function validateData(array $data):array
    {
        if (empty($data["id"]) || empty($data["booked_by"]) || empty($data["date"])) {
            return ["error" => "Please complete the require fields"];
        }

        $selectedDate = new DateTime($data["date"]);
        $currentDate = new DateTime();
        $limitDate = (clone $currentDate)->add(new DateInterval('P1M')); 

        if ($selectedDate > $limitDate) {
            return ["error" => "Please enter a date that does not exceed one month from today"];
        }
        if ($selectedDate < $currentDate)
        {
            return ["error" => "Please enter a date that is from today"];
        }
        return ["success" => "Data is validate"];
    }
}
