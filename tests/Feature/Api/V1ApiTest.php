<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class V1ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_standard_payload_and_cliente_role(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Cliente Uno',
            'email' => 'cliente@example.com',
            'password' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'rol'],
                    'token',
                    'token_type',
                ],
            ])
            ->assertJsonPath('data.user.rol', 0);
    }

    public function test_cliente_cannot_create_products(): void
    {
        $cliente = User::factory()->create(['rol' => 0]);
        Sanctum::actingAs($cliente);

        $response = $this->withHeaders(['Accept' => 'application/json'])->post('/api/v1/products', [
            'nombre' => 'Producto',
            'descripcion' => 'Descripcion',
            'precio' => 10.5,
            'cantidad' => 2,
            'categoria' => 'General',
            'imagen' => UploadedFile::fake()->create('producto.jpg', 120, 'image/jpeg'),
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('error.code', 'forbidden');
    }

    public function test_admin_can_create_products(): void
    {
        $admin = User::factory()->create(['rol' => 1]);
        Sanctum::actingAs($admin);

        $response = $this->post('/api/v1/products', [
            'nombre' => 'Producto Admin',
            'descripcion' => 'Descripcion',
            'precio' => 15.0,
            'cantidad' => 5,
            'categoria' => 'General',
            'imagen' => UploadedFile::fake()->create('producto-admin.jpg', 120, 'image/jpeg'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.nombre', 'Producto Admin');
    }

    public function test_cliente_only_sees_own_orders(): void
    {
        $clienteA = User::factory()->create(['rol' => 0]);
        $clienteB = User::factory()->create(['rol' => 0]);

        Order::create([
            'user_id' => $clienteA->id,
            'estado' => 'pendiente',
            'fecha_pedido' => now(),
            'direccion_envio' => 'Calle A',
        ]);

        Order::create([
            'user_id' => $clienteB->id,
            'estado' => 'pendiente',
            'fecha_pedido' => now(),
            'direccion_envio' => 'Calle B',
        ]);

        Sanctum::actingAs($clienteA);
        $response = $this->getJson('/api/v1/orders');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.user_id', $clienteA->id);
    }

    public function test_legacy_routes_return_deprecation_headers(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'missing@example.com',
            'password' => 'password123',
        ]);

        $response->assertHeader('Deprecation', 'true')
            ->assertHeader('Sunset', 'Wed, 30 Apr 2026 23:59:59 GMT');
    }
}
