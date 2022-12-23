<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;

class ConferenceControllerTest extends WebTestCase //PantherTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        // $client = static::createPantherClient([
        //     'external_base_uri' => $_SERVER['SYMFONY_PROJECT_DEFAULT_ROUTE_URL']
        // ]);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
        //echo $client->getResponse();
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/conference/amsterdam-2019');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Fabien',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'me@automat.ed',
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 2 comments")');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertCount(2, $crawler->filter('h4'));
        
        //$client->clickLink('View');
        $client->click($crawler->filter('h4 + p a')->link());
        
        $this->assertPageTitleContains('Amsterdam');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Amsterdam 2019');
        $this->assertSelectorExists('div:contains("There are 1 comments")');
    }
}