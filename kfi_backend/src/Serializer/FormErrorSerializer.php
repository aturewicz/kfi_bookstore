<?php

namespace App\Serializer;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * Serializes invalid Form instances.
 */
class FormErrorSerializer
{

    public function convertFormToArray(FormInterface $data)
    {
        $form = $errors = [];

        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }

        if ($errors) {
            $form['errors'] = $errors;
        }

        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                if (count($this->convertFormToArray($child)) > 0) {
                    $children[$child->getName()] = $this->convertFormToArray($child);
                }
            }
        }

        if ($children) {
            $form['children'] = $children;
        }

        return $form;
    }

    private function getErrorMessage(FormError $error)
    {
        return $error->getMessage();
    }
}