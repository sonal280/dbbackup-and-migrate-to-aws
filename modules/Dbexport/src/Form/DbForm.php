<?php

namespace Drupal\Dbexport\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LoginRedirectionForm.
 *
 * @package Drupal\sos_common\Form
 */
class DbForm extends ConfigFormBase
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

    public function variable_get($fist_param, $sec_param = "")
    {
        $r = \Drupal::state()->get($fist_param);
        return ($r == null) ? $sec_param : $r;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('ind_db_form.settings');
       $form['aws_region'] = [
            '#type' => 'textfield',
            '#title' => "AWS Region",
            '#description' => "aws region",
            '#default_value' => $this->variable_get("aws_region", ""),
        ];

        $form['aws_version'] = [
            '#type' => 'textfield',
            '#title' => "AWS version",
            '#description' => "aws version",
            '#default_value' => $this->variable_get("aws_version", ""),
        ];

        $form['aws_key'] = [
            '#type' => 'textfield',
            '#title' => "AWS key",
            '#description' => "aws key",
            '#default_value' => $this->variable_get("aws_key", ""),
        ];

        $form['aws_secret'] = [
            '#type' => 'textfield',
            '#title' => "AWS secret",
            '#description' => "aws secret",
            '#default_value' => $this->variable_get("aws_secret", ""),
        ];

        $form['aws_bucket'] = [
            '#type' => 'textfield',
            '#title' => "AWS bucket",
            '#description' => "aws bucket",
            '#default_value' => $this->variable_get("aws_bucket", ""),
        ];

        $form['aws_bucket_folder'] = [
            '#type' => 'textfield',
            '#title' => "AWS bucket folder",
            '#description' => "aws bucket folder",
            '#default_value' => $this->variable_get("aws_bucket_folder", ""),
        ];
       
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ); 
        return $form;

    }

    /**
     * {@inheritdoc}
     */


    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
      
            foreach ($form_state->getValues() as $key => $value) {

                $this->variable_set($key, $value);

        

                $this->config('Dbexport.settings')

                    ->save();

                parent::submitForm($form, $form_state);
           

            }
        

     
            \Drupal::messenger()->addMessage("updated successfully");





    }

    /**
     * Get Editable config names.
     *
     * @inheritDoc
     */
   

}