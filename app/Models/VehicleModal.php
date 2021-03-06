<?php

namespace App\Models;

use Container;

/**
 * Class to make database calls
 * ORM to be established eventually for now functional programming
 */
class VehicleModal
{
    /**
     * Static function to create database entry
     *
     * @param String $registration
     * @param String $colour
     * @return void
     */
    public static function create($registration, $colour)
    {
        $firstOpenSlot;
        try {
            $query = "SELECT id AS Parking_Slot FROM slots WHERE active = 'FALSE' LIMIT 1 ";
            $statement = Container::get('database')->prepare($query);
            $statement->execute();
            $retrieved = $statement->fetchAll();
            if (count($retrieved) > 0) {
                $firstOpenSlot = $retrieved[0]['Parking_Slot'];
            } else {
                return "None";
            }

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        try {
            $query = "SELECT registration_number FROM vehicles WHERE registration_number = :registration";
            $statement = Container::get('database')->prepare($query);
            $statement->bindParam(':registration', $registration);
            $statement->execute();
            $existing = $statement->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        if (count($existing) === 0) {
            try {
                $query = "INSERT INTO vehicles(slots_id,registration_number,vehicle_colour) VALUES(:id,:registration,:colour)";
                $statement = Container::get('database')->prepare($query);
                $statement->bindParam(':id', $firstOpenSlot);
                $statement->bindParam(':registration', $registration);
                $statement->bindParam(':colour', $colour);
                $statement->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

        } else {
            return 'This is an existing vehicle!';
        }

        try {
            $query = "UPDATE slots SET active = 'TRUE' WHERE id = :id";
            $statement = Container::get('database')->prepare($query);
            $statement->bindParam(':id', $firstOpenSlot);
            $statement->execute();
            $existing = $statement->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return $firstOpenSlot;
    }
    /**
     * Static funtion to udpate database.
     *
     * @param String $registration
     * @param String $colour
     * @return void
     */
    public static function update($registration, $colour)
    {

        $firstOpenSlot;
        $slotsId = null;
        try {
            $query = "SELECT slots_id AS vehicles FROM vehicles WHERE registration_number = :registration";
            $statement = Container::get('database')->prepare($query);
            $statement->bindParam(':registration', $registration);
            $statement->execute();
            $retrieved = $statement->fetchAll();
            if (count($retrieved) === 0) {
                return "None";
            }
            if ($retrieved[0]['vehicles'] == null) {
                $slotsId = $retrieved[0]['vehicles'];
                try {
                    $query = "SELECT id AS Parking_Slot FROM slots WHERE active = 'FALSE' LIMIT 1";
                    $statement = Container::get('database')->prepare($query);
                    $statement->execute();
                    $retrieved = $statement->fetchAll();
                    if (count($retrieved) > 0) {
                        $firstOpenSlot = $retrieved[0]['Parking_Slot'];
                    } else {
                        return "None\n";
                    }

                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                try {
                    $query = "UPDATE vehicles SET slots_id = :id,registration_number = :registration,vehicle_colour = :colour WHERE registration_number = :registration";
                    $statement = Container::get('database')->prepare($query);
                    $statement->bindParam(':id', $firstOpenSlot);
                    $statement->bindParam(':registration', $registration);
                    $statement->bindParam(':colour', $colour);
                    $statement->execute();
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                try {
                    $query = "UPDATE slots SET active = 'TRUE' WHERE id = :id";
                    $statement = Container::get('database')->prepare($query);
                    $statement->bindParam(':id', $firstOpenSlot);
                    $statement->execute();
                    $existing = $statement->fetchAll();
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                return $firstOpenSlot;
            }

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        try {
            $query = "UPDATE vehicles SET registration_number = :registration,vehicle_colour = :colour WHERE registration_number = :registration";
            $statement = Container::get('database')->prepare($query);
            $statement->bindParam(':registration', $registration);
            $statement->bindParam(':colour', $colour);
            $statement->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return "Updated Vehicle but no open slot\n";
    }
    /**
     * Static function to delete entry in Database
     *
     * @param String $id
     * @return void
     */
    public static function delete($id)
    {

        $numberExists = false;
        try {
            $query = "SELECT id FROM slots WHERE id = :id";
            $statement = Container::get('database')->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $retrieved = $statement->fetchAll();
            if (count($retrieved) > 0) {
                $numberExists = true;
            } else {
                return "Does not exist";
            }

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        if ($numberExists) {

            try {
                $query = "UPDATE vehicles SET slots_id = NULL WHERE slots_id = :id";
                $statement = Container::get('database')->prepare($query);
                $statement->bindParam(':id', $id);
                $statement->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

            try {
                $query = "UPDATE slots SET active = 'FALSE' WHERE id = :id";
                $statement = Container::get('database')->prepare($query);
                $statement->bindParam(':id', $id);
                $statement->execute();
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $id;
        }

    }
    /**
     * Static function to check active column in slots table
     *
     * @return void
     */
    public static function status()
    {
        $firstOpenSlot;
        try {
            $query = "SELECT id AS Parking_Slot FROM slots WHERE active = 'FALSE' LIMIT 1 ";
            $statement = Container::get('database')->prepare($query);
            $statement->execute();
            $retrieved = $statement->fetchAll();
            if (count($retrieved) > 0) {
                return $retrieved[0]['Parking_Slot'];
            } else {
                return "None\n";
            }

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    }
    /**
     * General Queries to database for specific data
     *
     * @param [type] $data
     * @return void
     */
    public static function Query($data)
    {
        if ($data[0] === 'slot_numbers_for_cars_with_colour') {
            try {
                $query = "SELECT slots_id FROM vehicles WHERE vehicle_colour = '$data[1]' AND slots_id IS NOT NULL";
                $statement = Container::get('database')->prepare($query);
                $statement->execute();
                $retrieved = $statement->fetchAll();
                if (count($retrieved) === 0) {
                    return "None";
                }
                return $retrieved;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        if ($data[0] === 'slot_number_for_registration_number') {
            try {
                $query = "SELECT slots_id FROM vehicles WHERE registration_number = '$data[1]' AND slots_id IS NOT NULL";
                $statement = Container::get('database')->prepare($query);
                $statement->execute();
                $retrieved = $statement->fetchAll();
                if (count($retrieved) === 0) {
                    return "None";
                }
                return $retrieved;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

        }
        if ($data[0] === 'registration_numbers_for_cars_with_colour') {
            try {
                $query = "SELECT registration_number FROM vehicles WHERE slots_id IS NOT NULL AND vehicle_colour = '$data[1]'";
                $statement = Container::get('database')->prepare($query);
                $statement->execute();
                $retrieved = $statement->fetchAll();
                if (count($retrieved) === 0) {
                    return "None";
                }
                return $retrieved;
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

        }
    }
}
