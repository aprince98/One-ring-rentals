<?php

namespace SilverStripe\Lessons;

use PageController;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;

class ArticlePageController extends PageController
{
    private static $allowed_actions = [
        'CommentForm',
    ];
    public function CommentForm()
    {
        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                TextField::create('Name','')
                    ->setAttribute('placeholder','Name*')
                    ->addExtraClass('form-control'),
                EmailField::create('Email','')
                    ->setAttribute('placeholder','Email*')
                    ->addExtraClass('form-control'),
                TextareaField::create('Comment','')
                    ->setAttribute('placeholder','Comment*')
                    ->addExtraClass('form-control')
            ),
            FieldList::create(
                FormAction::create('handleComment','Post Comment')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-default-color btn-lg')
            ),
            RequiredFields::create('Name','Email','Comment')
        );
    
        $form->addExtraClass('form-style');

        foreach($form->Fields() as $field) {
            $field->addExtraClass('form-control')
                   ->setAttribute('placeholder', $field->getName().'*');            
        }

        $data = $this->getRequest()->getSession()->get("FormData.{$form->getName()}.data");

        return $form;
    }

    public function handleComment($data, $form)
{
    $comment = ArticleComment::create();
        $comment-> ArticlePageID = $this->ID;
        $form->saveInto($comment);
        $comment->write();

        $form->sessionMessage('Thanks for your comment','good');

        return $this->redirectBack();
}
}