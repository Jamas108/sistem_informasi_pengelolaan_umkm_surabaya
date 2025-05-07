<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManajemenUserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::create([
            'username' => 'admin_test',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => Hash::make('password123'),
            'role' => 'adminkantor',
        ]);

        // Authenticate as admin
        $this->actingAs($this->adminUser);
    }

    /**
     * Test index method.
     */
    public function admin_kantor_dapat_akses_halaman_index()
    {
        // Create additional admin users for testing
        $adminKantor = User::create([
            'username' => 'admin_kantor_test',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => Hash::make('password123'),
            'role' => 'adminkantor',
        ]);


        // Make request to the index page
        $response = $this->get(route('manajemenuser.index'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('adminkantor.manajemen_user.index');
        $response->assertViewHas('user');
        $response->assertViewHas('pageTitle', 'Manajemen User');

        // Assert admin users are displayed
        $response->assertSee('admin_kantor_test');

    }

    /**
     * Test store method with valid data.
     */
    // public function test_membuat_data_user_valid()
    // {
    //     // Prepare data for the user
    //     $userData = [
    //         'username' => 'new_admin_test',
    //         'nik' => $this->faker->unique()->numerify('################'),
    //         'password' => 'password123',
    //         'password_confirmation' => 'password123',
    //         'role' => 'adminkantor',
    //     ];

    //     // Make request to store new user
    //     $response = $this->post(route('manajemenuser.store'), $userData);

    //     // Assert redirection
    //     $response->assertRedirect(route('manajemenuser.index'));
    //     $response->assertSessionHas('success', 'User berhasil ditambahkan');

    //     // Assert user was created in database
    //     $this->assertDatabaseHas('users', [
    //         'username' => 'new_admin_test',
    //         'nik' => $userData['nik'],
    //         'role' => 'adminkantor',
    //     ]);
    // }

    /**
     * Test store method with validation errors.
     */
    public function test_membuat_data_user_tidak_valid()
    {
        // Prepare data with validation errors
        $invalidData = [
            'username' => '', // Required field is empty
            'password' => 'short', // Too short
            'password_confirmation' => 'not_matching', // Does not match
            'role' => 'adminkantor',
        ];

        // Make request to store with invalid data
        $response = $this->post(route('manajemenuser.store'), $invalidData);

        // Assert redirection back with errors
        $response->assertRedirect();
        $response->assertSessionHasErrors(['username', 'password']);

        // Assert user was not created
        $this->assertDatabaseMissing('users', [
            'username' => '',
            'role' => 'adminkantor',
        ]);
    }


    /**
     * Test show method.
     */
    public function test_halaman_detail_user()
    {
        // Make request to show user details
        $response = $this->get(route('manajemenuser.show', $this->adminUser->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('adminkantor.manajemen_user.show');
        $response->assertViewHas('user');
        $response->assertViewHas('pageTitle', 'Detail User');

        // Assert the user details are displayed
        $response->assertSee($this->adminUser->username);
    }

    /**
     * Test edit method.
     */
    public function test_menampilkan_halaman_edit_user()
    {
        // Make request to edit user
        $response = $this->get(route('manajemenuser.edit', $this->adminUser->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('adminkantor.manajemen_user.edit');
        $response->assertViewHas('user');
        $response->assertViewHas('pageTitle', 'Edit User');

        // Assert the user details are displayed in form
        $response->assertSee($this->adminUser->username);
    }

    /**
     * Test update method with valid data.
     */
    public function test_edit_data_user()
    {
        // Prepare data for updating
        $updatedData = [
            'username' => 'admin_test_updated',
            'nik' => $this->faker->unique()->numerify('################'),
            'role' => 'adminlapangan',
        ];

        // Make request to update user
        $response = $this->put(route('manajemenuser.update', $this->adminUser->id), $updatedData);

        // Assert redirection
        $response->assertRedirect(route('manajemenuser.index'));
        $response->assertSessionHas('success', 'Data user berhasil diperbarui');

        // Refresh the model
        $this->adminUser->refresh();

        // Assert user was updated
        $this->assertEquals('admin_test_updated', $this->adminUser->username);
        $this->assertEquals('adminlapangan', $this->adminUser->role);
    }

    // /**
    //  * Test update method with duplicate username.
    //  */
    // public function test_update_fails_with_duplicate_username()
    // {
    //     // Create another user with known username
    //     User::create([
    //         'username' => 'another_admin',
    //         'nik' => $this->faker->unique()->numerify('################'),
    //         'password' => Hash::make('password123'),
    //         'role' => 'adminkantor',
    //     ]);

    //     // Prepare data with duplicate username
    //     $duplicateData = [
    //         'username' => 'another_admin', // Already exists
    //         'nik' => $this->adminUser->nik,
    //         'role' => $this->adminUser->role,
    //     ];

    //     // Make request to update with duplicate username
    //     $response = $this->put(route('manajemenuser.update', $this->adminUser->id), $duplicateData);

    //     // Assert redirection back with errors
    //     $response->assertRedirect();
    //     $response->assertSessionHasErrors('username');
    // }

    /**
     * Test destroy method.
     */
    public function test_hapus_data_user()
    {
        // Create a user to delete
        $userToDelete = User::create([
            'username' => 'user_to_delete',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => Hash::make('password123'),
            'role' => 'adminkantor',
        ]);

        // Make request to delete user
        $response = $this->delete(route('manajemenuser.destroy', $userToDelete->id));

        // Assert redirection
        $response->assertRedirect(route('manajemenuser.index'));
        $response->assertSessionHas('success', 'User berhasil dihapus');

        // Assert user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }


    /**
     * Clean up after testing.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}