<?php

namespace App\Tests\Form\Type;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\Form\Test\TypeTestCase;

class PostTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'content' => 'Content of test post'
        ];

        $postToCompare = new Post();
        $form = $this->factory->create(PostType::class, $postToCompare);

        $post = new Post();
        $post->setContent($formData['content']);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($post, $postToCompare);

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
