<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class HawkeyeSetupTables extends Migration
{
/**
* Run the migrations.
*
* @return void
*/
public function up()
{
// Create table for storing roles
Schema::create('{{ $filesTable }}', function (Blueprint $table) {
$table->bigIncrements('id');
$table->string('name', 500);
$table->string('extension', 50);
$table->string('size', 50);
$table->string('ip', 50);
$table->dateTime('uploaded_at');
});

}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
Schema::drop('{{ $filesTable }}');
}
}