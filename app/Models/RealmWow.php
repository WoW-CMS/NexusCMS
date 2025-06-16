<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ConnectsToExternalDatabase;

class RealmWow extends Model
{
    public function __construct(ConnectsToExternalDatabase $externalDatabase)
    {
        $this->externalDatabase = $externalDatabase;
    }

    public function getRealmList()
    {
        $connection = $this->externalDatabase->getConnection();
        $query = "SELECT name, realm_id FROM realms";
        $realms = $connection->select($query);

        return $realms;
    }
}
