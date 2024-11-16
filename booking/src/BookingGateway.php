<?php

class BookingGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $query = "SELECT
            t.id, 
            c.name,
            t.day_of_week,
            t.start_time,
            t.end_time,
             c.capacity_per_class,
        (
            SELECT COUNT(*) 
            FROM bookings b
            WHERE b.classroom_id = t.classroom_id
            AND b.date >= CURDATE() 
              AND (
                  (b.date = CURDATE() AND b.start_time >= CURTIME()) 
                  OR b.date > CURDATE() 
              )
              AND b.start_time < t.end_time 
              AND b.end_time > t.start_time
              AND b.day_of_week = t.day_of_week
        ) AS current_bookings
            FROM 
                classrooms c
            JOIN 
                timetables t 
            ON c.id = t.classroom_id
            ;";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function checkId(array $data): array|bool
    {
        $query = "SELECT 
        t.classroom_id, 
        c.name, 
        t.start_time, 
        t.end_time, 
        t.day_of_week, 
        :date AS date, 
        :booked_by AS booked_by
        FROM timetables t
        JOIN classrooms c ON t.classroom_id = c.id
        WHERE t.id = :id;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":booked_by", $data["booked_by"]);
        $stmt->bindValue(":date", $data["date"]);
        $stmt->bindValue(":id", $data["id"]);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return ["error" => "The provided ID does not match any existing timetable."];
        }

        return $result;
    }

    public function checkOverlap(array $data): bool|array
    {
        // $query = "SELECT 
        // t.classroom_id, 
        // c.name, 
        // t.start_time, 
        // t.end_time, 
        // t.day_of_week, 
        // :date AS date, 
        // :booked_by AS booked_by
        // FROM timetables t
        // JOIN classrooms c ON t.classroom_id = c.id
        // WHERE t.id = :id
        // AND NOT EXISTS (
        //         SELECT 1
        //         FROM bookings b
        //         WHERE b.booked_by = :booked_by
        //             AND b.date = :date
        //             AND (
        //                 (b.start_time < t.end_time AND b.end_time > t.start_time)
        //             )
        //     )
        // AND DAYNAME(:date) = t.day_of_week;";

        $query = "SELECT 
        b.start_time, 
        b.end_time, 
        b.date, 
        b.booked_by
        FROM bookings b
        JOIN timetables t ON b.classroom_id = t.classroom_id
        WHERE t.id = :id
          AND b.date = :date
          AND b.booked_by = :booked_by
          AND (
            b.start_time < t.end_time AND b.end_time > t.start_time
          )
        AND DAYNAME(:date) = t.day_of_week;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":booked_by", $data["booked_by"]);
        $stmt->bindValue(":date", $data["date"]);
        $stmt->bindValue(":id", $data["id"]);
        $stmt->execute();

        $conflict = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conflict) {
            return [
                "error" => "Conflict detected: overlapping booking from "
                . $conflict['start_time'] . " to " . $conflict['end_time']
                    . " by " . $conflict['booked_by'] . "."
            ];
        }

        return true;
        
    }


    public function checkCapacity(array $data): bool|array
    {
        $query = "SELECT 
        c.capacity_per_class,
        (SELECT COUNT(*) 
         FROM bookings b
         WHERE b.date = :date
           AND b.classroom_id = t.classroom_id
           AND b.start_time < t.end_time
           AND b.end_time > t.start_time) AS current_bookings
        FROM timetables t
        JOIN classrooms c ON c.id = t.classroom_id
        WHERE t.id = :id;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $data["id"]);
        $stmt->bindValue(":date", $data["date"]);
        $stmt->execute();

        $capacity = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$capacity || !isset($capacity['current_bookings'])) {
            return ["error" => "The classroom associated with this timetable does not exist or cannot be determined."];
        }

        if ($capacity['current_bookings'] >= $capacity['capacity_per_class']) {
            return [
                "error" => "The classroom is already at full capacity ("
                . $capacity['current_bookings'] . "/" . $capacity['capacity_per_class'] . ")."
            ];
        }

        return true;
    }


    public function verifyAndFetch(array $data): array|bool
    {

        $idResult = $this->checkId($data);
        if (isset($idResult['error'])) {
            return $idResult;
        }

        $overlapResult = $this->checkOverlap($data);
        if (isset($overlapResult['error'])) {
            return $overlapResult;
        }

        $capacityResult = $this->checkCapacity($data);
        if (isset($capacityResult['error'])) {
            return $capacityResult;
        }
        return [
            "classroom_id" => $idResult["classroom_id"],
            "name" => $idResult["name"],
            "start_time" => $idResult["start_time"],
            "end_time" => $idResult["end_time"],
            "day_of_week" => $idResult["day_of_week"],
            "date" => $data["date"],
            "booked_by" => $data["booked_by"]
        ];
    }

    public function bookClass(array $data): void
    {
        $query = "INSERT INTO bookings (booked_by, classroom_id, date, start_time, end_time, day_of_week)
                VALUES (:booked_by, :classroom_id, :date, :start_time, :end_time, DAYNAME(:date));";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":booked_by", $data["booked_by"]);
        $stmt->bindValue(":classroom_id", $data["classroom_id"]);
        $stmt->bindValue(":date", $data["date"]);
        $stmt->bindValue(":start_time", $data["start_time"]);
        $stmt->bindValue(":end_time", $data["end_time"]);

        $stmt->execute();
    }

    public function canDeleteClass(int $id):array
    {
        $query = "SELECT date FROM bookings WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $bookedDate = new DateTime($result["date"]);
            $currentDate = new DateTime();

            $interval = $currentDate->diff($bookedDate);
            $hours = $interval->h + ($interval->days * 24);

            if ($hours < 24) {
                return ["error" => "Cannot delete because the current date exceeds 24 hours from the reservation date."]; 
            }
            return ["success" => true];
        } else {
            return ["error" => "Wrong id"]; 
        }
    }

    public function deleteClass(int $id): void
    {
        $query = "DELETE from bookings WHERE bookings.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }
}
