<?php
namespace Tests\Unit;
use DTApi\Repository\UserRepository;
use DTApi\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test createOrUpdate function in UserRepository
     */
    public function testCreateOrUpdate()
    {
        // Create a test request data
        $requestData = [
            'role' => 'customer',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testpassword',
            // Add other required fields for the request
        ];

        // Create a new user model instance (or use factory to create a test user)
        $user = new User();

        // Create an instance of UserRepository and call the createOrUpdate function
        $userRepository = new UserRepository($user);
        $result = $userRepository->createOrUpdate(null, $requestData);

        // Assertions to check if the function works as expected
        $this->assertInstanceOf(User::class, $result); // Check if the result is an instance of User model
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']); // Check if the user was stored in the database

        // Add more assertions based on your specific use case and expected outcomes

        // For example, you can check if the user has the correct role attached
        $this->assertEquals('customer', $result->user_type);

        // You can also check if the password was properly hashed
        $this->assertTrue(app('hash')->check('testpassword', $result->password));
    }
}
