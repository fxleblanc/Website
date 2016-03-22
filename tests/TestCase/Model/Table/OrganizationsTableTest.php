<?php
/**
 * Tests for OrganizationsTable
 *
 * @category Test
 * @package  Website
 * @author   Raphael St-Arnaud <am21830@ens.etsmtl.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 * @link     https://github.com/MaisonLogicielLibre/Site
 */

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrganizationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * Tests for OrganizationsTable
 *
 * @category Test
 * @package  Website
 * @author   Raphael St-Arnaud <am21830@ens.etsmtl.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL v3
 * @link     https://github.com/MaisonLogicielLibre/Site
 */
class OrganizationsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
    'app.type_users_users',
    'app.organizations',
    'app.organizations_Projects',
    'app.organizations_Owners',
    'app.organizations_Members',
    'app.users',
    'app.type_users',
    'app.svn_users',
    'app.svns',
    'app.universities',
    'app.projects',
    'app.projects_contributors',
    'app.projects_mentors',
    'app.missions'
    ];

    /**
     * SetUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Organizations') ? [] : ['className' => 'App\Model\Table\OrganizationsTable'];
        $this->Organizations = TableRegistry::get('Organizations', $config);
    }

    /**
     * Test getId
     *
     * @return void
     */
    public function testGetId()
    {
        $id = 1;
        $expected = 1;

        $org = $this->Organizations->get($id);

        $result = $org->getId();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getName
     *
     * @return void
     */
    public function testGetName()
    {
        $id = 1;
        $expected = 'MLL';

        $org = $this->Organizations->get($id);

        $result = $org->getName();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getWebsite
     *
     * @return void
     */
    public function testGetWebsite()
    {
        $id = 1;
        $expected = 'www.website.com';

        $org = $this->Organizations->get($id);

        $result = $org->getWebsite();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getKLogo
     *
     * @return void
     */
    public function testGetLogo()
    {
        $id = 1;
        $expected = '/img/logo.jpg';

        $org = $this->Organizations->get($id);

        $result = $org->getLogo();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getDescription
     *
     * @return void
     */
    public function testGetDescription()
    {
        $id = 1;
        $expected = 'Awesome';

        $org = $this->Organizations->get($id);

        $result = $org->getDescription();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getIsValidated
     *
     * @return void
     */
    public function testGetIsValidated()
    {
        $id = 1;
        $expected = 0;

        $org = $this->Organizations->get($id);

        $result = $org->getIsValidated();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getIsRejected
     *
     * @return void
     */
    public function testGetIsRejected()
    {
        $id = 1;
        $expected = 0;

        $org = $this->Organizations->get($id);

        $result = $org->getIsRejected();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getIsRejectedTrue
     *
     * @return void
     */
    public function testGetIsRejectedTrue()
    {
        $id = 3;
        $expected = 1;

        $org = $this->Organizations->get($id);

        $result = $org->getIsRejected();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editName
     *
     * @return void
     */
    public function testSetName()
    {
        $id = 1;
        $expected = 'ML2';

        $org = $this->Organizations->get($id);

        $result = $org->editName($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editWebsite
     *
     * @return void
     */
    public function testSetWebsite()
    {
        $id = 1;
        $expected = 'www.website.com';

        $org = $this->Organizations->get($id);

        $result = $org->editWebsite($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editLogo
     *
     * @return void
     */
    public function testSetlogo()
    {
        $id = 1;
        $expected = '/img/logo.png';

        $org = $this->Organizations->get($id);

        $result = $org->editLogo($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editDescription
     *
     * @return void
     */
    public function testSetDescription()
    {
        $id = 1;
        $expected = 'Even more awesome';

        $org = $this->Organizations->get($id);

        $result = $org->editDescription($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editIsValidated
     *
     * @return void
     */
    public function testSetIsValidated()
    {
        $id = 1;
        $expected = 1;

        $org = $this->Organizations->get($id);

        $result = $org->editIsValidated($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test editIsRejected
     *
     * @return void
     */
    public function testSetIsRejected()
    {
        $id = 1;
        $expected = 1;

        $org = $this->Organizations->get($id);

        $result = $org->editIsRejected($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test validation
     *
     * @return void
     */
    public function testValidation()
    {
        $validator = new Validator();

        $expected = $validator;

        $result = $this->Organizations->validationDefault($validator);

        $this->assertEquals($validator, $result);
    }

    /**
     * Test validation Website Rules
     *
     * @return void
     */
    public function testValidationWebsite()
    {
        $id = 1;
        $org = $this->Organizations->get($id);

        $this->Organizations->patchEntity($org, ['website' => 'www.website.com']);
        $result = $this->Organizations->save($org);

        if ($result != false) {
            $result = true;
        }

        $this->assertFalse($result);
    }

    public function testAddMember()
    {
        $id = 2;
        $org = $this->Organizations->get($id, ['contain' => ['Members', 'Owners']]);
        $this->assertEquals(1, count($org['members']));

        $org->addMember(3);
        $this->assertEquals(2, count($org['members']));
        $this->assertEquals(3, $org['members'][1]['id']);
    }

    public function testGetMembersId()
    {
        $id = 2;
        $expected = [$id];
        $org = $this->Organizations->get($id, ['contain' => ['Members', 'Owners']]);

        $membersId = $org->getMembersId();

        $this->assertEquals($expected, $membersId);
    }
}
