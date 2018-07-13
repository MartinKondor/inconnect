<?php

namespace App\Tests\Form\Type;

use App\Entity\Page;
use App\Form\PageType;
use Symfony\Component\Form\Test\TypeTestCase;

class PageTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'page_name' => 'Test Page',
            'page_type' => 'test',
            'contact_email' => 'test@john.test',
            'password' => 'test'
        ];

        $pageToCompare = new Page();
        $form = $this->factory->create(PageType::class, $pageToCompare);

        $page = new Page();
        $page->setPageName($formData['page_name'])
            ->setPageType($formData['page_type'])
            ->setContactEmail($formData['contact_email'])
            ->setPassword($formData['password']);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($page, $pageToCompare);

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
