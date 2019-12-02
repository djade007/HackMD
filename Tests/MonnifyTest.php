<?php


use HMO\Monnify;
use PHPUnit\Framework\TestCase;

class MonnifyTest extends TestCase
{
    protected $monnify;

    public function setUp()
    {
        parent::setUp();

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();

        $this->monnify = new Monnify();
    }

    public function testLogin() {
        $this->assertTrue($this->monnify->login());
    }

    public function testCanReserveAccount() {
        $ref = $this->generateId();
        $account = $this->reserveAccount($ref);
        $this->assertEquals($ref, $account->accountReference);
    }

    public function testCanDeactivateReservedAccount() {
        $ref = $this->generateId();
        $accountNumber = $this->reserveAccount($ref)->accountNumber;

        $account = $this->monnify->deactivateAccount($accountNumber);

        $this->assertEquals($accountNumber, $account->accountNumber);
    }

    public function testCanLoadTransactions() {
        $ref = $this->generateId();
        $this->reserveAccount($ref);
        $res = $this->monnify->transactions($ref);
        $this->assertIsArray($res->content);
    }

    /*reserving an account with a reference number*/
    protected function reserveAccount($ref) {
        return $this->monnify->reserveAccount($ref, "hello@test.com");
    }

    /*Generate a random reference number*/
    protected function generateId() {
        return 'rrr-' . rand();
    }
}
