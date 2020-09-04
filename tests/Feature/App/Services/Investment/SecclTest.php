<?php

namespace Tests\Feature\App\Services\Investment;

use App\Services\Investment\Seccl;
use App\Services\Investment\AdapterConfig;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SecclTest extends TestCase
{
    private $adapter;

    public function setUp(): void
    {
        parent::setUp();

        $config = new AdapterConfig();
        $config
            ->setBaseUri(config('investment.seccl.base_uri'))
            ->setId(config('investment.seccl.id'))
            ->setFirmId(config('investment.seccl.firm_id'))
            ->setPassword(config('investment.seccl.password'));

        $client = new Client;

        $this->adapter = new Seccl($config, $client);
    }

    public function testCanAuthenticate()
    {
        self::assertIsString($this->adapter->getAccessToken());
    }

    public function testCanCreateClient()
    {
        $client = new \App\Services\Investment\Models\Client($this->adapter);
        $client
            ->setFirmId('CRADL')
            ->setNodeId(["0"])
            ->setClientType('Individual')
            ->setTitle('Miss')
            ->setFirstName('Peggy')
            ->setSurname('Hill')
            ->setGender('Female')
            ->setCurrency('GBP')
            ->setAddressDetail([
                'department' => 'Test',
                'flatNumber' => '12',
                'buildingName' => 'Testing Tower',
                'buildingNumber' => '1',
                'address1' => 'Manchester Road',
                'address2' => 'Arlen',
                'country' => 'GB',
                'postCode' => 'DN11 6RJ',
            ])
            ->setNationality('GB')
            ->setLanguage('en')
            ->setEmail('peggy.hill' . mt_rand(999, 999999) . '@test.com')
            ->setMobile([
                'number' => '07777000000',
                'locale' => 'en-GB',
                'isMobile' => true,
            ])
            ->setCommunicationPrefs([
                'emailPrefs' => [
                    'profileUpdates' => true,
                    'accountUpdated' => true,
                    'newAccount' => true,
                ],
            ])
            ->setNationalInsuranceNo('XB' . mt_rand(10, 99) . mt_rand(1000, 9999) . 'D')
            ->setDateOfBirth('1970-10-01')
            ->setBankDetails([
                'sortCode' => '999999',
                'accountNumber' => '99999998',
            ])
            ->setTaxDomicile('GB')
            ->setTermsAccepted(true)
            ->setMarketingConsent(true)
            ->setClientSeenFaceToFace(true)
            ->setAmlData([
                'evidenceSeen' => true,
            ]);

        $response = $client->create();

        $this->assertSame($response->getStatusCode(), Response::HTTP_CREATED);
    }

    public function testCanGetClient()
    {
        $client = new \App\Services\Investment\Models\Client($this->adapter);

        $client->setFirmId('CRADL');

        $client->findbyClientId('00DDB94');

        self::assertSame('00DDB94', $client->getId());
    }

    public function testCanListClients()
    {
        $client = new \App\Services\Investment\Models\Client($this->adapter);

        $client->setFirmId('CRADL');

        $clients = $client->list();

        self::assertSame($client->getFirmId(), $clients['data'][0]['firmId']);
    }

    public function testCanListClientsWithFilter()
    {
        $client = new \App\Services\Investment\Models\Client($this->adapter);

        $client->setFirmId('CRADL');

        $clients = $client->list([
            'surname' => 'Hill',
        ]);

        self::assertSame($client->getFirmId(), $clients['data'][0]['firmId']);
    }

    public function testCanUpdateAClient()
    {
        $client = new \App\Services\Investment\Models\Client($this->adapter);

        $client->setFirmId('CRADL');

        $client->findByClientId('00DDB94');

        $client->setSurname('Test1');

        self::assertSame($client->getSurname(), 'Test1');

        $client->update();

        self::assertSame($client->getSurname(), 'Test1');

        $client->setSurname('Test2');

        $client->findByClientId('00DDB94');

        self::assertSame($client->getSurname(), 'Test1');
    }
}
