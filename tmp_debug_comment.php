<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => false]);

$c = App\Models\RecipeCategory::create(['name' => 'Desserts', 'slug' => 'desserts']);
$f = App\Models\User::factory()->create();
$t = App\Models\User::factory()->create();
$r = App\Models\Recipe::factory()->create(['user_id' => $t->id, 'category_id' => $c->id, 'title' => 'Chocolate Cake']);

Illuminate\Support\Facades\Auth::loginUsingId($f->id);

$route = route('comments.store', $r);
echo "route=$route\n";
$request = Illuminate\Http\Request::create($route, 'POST', ['content' => 'Nice recipe!']);
$response = $app->handle($request);
echo "status=" . $response->getStatusCode() . "\n";

echo "comments=" . $r->fresh()->comments()->count() . "\n";

echo json_encode(App\Models\Notification::all()->toArray(), JSON_PRETTY_PRINT) . "\n";
