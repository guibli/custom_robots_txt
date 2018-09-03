<?php 
/**
 * @file
 * Contains \Drupal\custom_robots_txt\Form\CustomRobotsTxtForm.
 */

namespace Drupal\custom_robots_txt\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure custom settings for this site.
 */
class CustomRobotsTxtForm  extends FormBase {

/**
 * Returns a unique string identifying the form.
 *
 * @return string
 * The unique string identifying the form.
 */
public function getFormId() {
    return 'custom_robots_txt_admin_form';
}

/**
 * Form constructor.
 *
 * @param array $form
 * An associative array containing the structure of the form.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 * The current state of the form.
 *
 * @return array
 * The form structure.
 */
public function buildForm(array $form, FormStateInterface $form_state) {
    $public_path = \Drupal::service('file_system')->realpath(file_default_scheme() . "://");
    $robots = file_get_contents ($public_path.'/robots.txt');
    
    $form = array(
        'robotstxt' => array(
        '#type' => 'textarea', 
        '#title' => t('Edit robots.txt'),
        '#default_value' => $robots,
        '#description' => t('Edit custom robots.txt file'),
        '#rows' => 20
        ),
    );
    $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create'),
        '#button_type' => 'primary',
      ];
      return $form;    
}

/**
 * Form submission handler.
 *
 * @param array $form
 * An associative array containing the structure of the form.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 * The current state of the form.
 */
public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $create = file_save_data ($form_state->cleanValues()->getValue('robotstxt'),'public://robots.txt',FILE_EXISTS_REPLACE);
    if($create){
        drupal_set_message(t('Robots.txt file created in /files'),'status');
    }else{
        drupal_set_message(t('Robots.txt file creation failed'),'error');
    }
    drupal_flush_all_caches();

}
}