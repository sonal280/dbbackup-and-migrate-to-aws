<?php

namespace Drupal\Dbexport\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\file\Entity\File;

/**
 * Class LoginRedirectionForm.
 *
 * @package Drupal\sos_common\Form
 */
class DbUploadForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'DbForm';
    }

  
    protected function getEditableConfigNames() {
        return ['Dbexport.settings'];
    }

    public function variable_set($fist_param, $sec_param)
    {
        return \Drupal::state()->set($fist_param, $sec_param);
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['#attributes']['enctype'] = 'multipart/form-data';
        $validators = [
            'file_validate_extensions' => ['sql'],
        ];
        $form['file_upload'] = array(
            '#title' => t('Upload file'),
            '#name' => 'upload_file',
            '#type'  => 'managed_file',
            '#upload_validators' => $validators,
            '#upload_location' => 'public://my_files/',
        );

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Upload'),
            '#button_type' => 'primary',
        ); 
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('file_upload') == null) {
            $form_state->setErrorByName('file_upload', $this->t('Please upload File'));
        }
    }

    /**
     * {@inheritdoc}
     */


    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $file = $form_state->getValue('file_upload');
        $formfile = File::load(reset($file));
        $formfile->setPermanent();
        $filename = $formfile->getFilename();

        $this->variable_set("filename",$filename);
        \Drupal::logger('Dbexport')->error('Database Imported');
    }

    /**
     * Get Editable config names.
     *
     * @inheritDoc
     */
   

}